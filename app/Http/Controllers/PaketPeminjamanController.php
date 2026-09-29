<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\PaketPeminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaketPeminjamanController extends Controller
{
    /**
     * Menampilkan daftar paket peminjaman aula dengan optimasi query (Eager Loading bebas N+1).
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        // Optimasi: Pilih kolom spesifik dan eager load fasilitas terkait (hanya id dan judul)
        $pakets = PaketPeminjaman::query()
            ->select(['id', 'nama_paket', 'kategori', 'harga', 'harga_dp', 'deskripsi', 'created_at', 'updated_at'])
            ->with(['facilities:id,judul'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kategori', 'like', "%{$search}%")
                        ->orWhere('nama_paket', 'like', "%{$search}%")
                        ->orWhereHas('facilities', function ($fq) use ($search) {
                            $fq->where('judul', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        // Ambil daftar fasilitas untuk dropdown pilihan di modal tambah & edit (1 query ringan)
        $availableFacilities = Facility::query()
            ->select(['id', 'judul'])
            ->orderBy('judul')
            ->get();

        return view('Admin.paket.index', compact('pakets', 'availableFacilities', 'search'));
    }

    /**
     * Menyimpan data paket peminjaman baru beserta fasilitas detailnya.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_paket' => ['nullable', 'string', 'max:150'],
            'kategori' => ['required', 'in:unggulan,terjangkau,standar 1,standar 2,standar 3'],
            'harga' => ['required', 'numeric', 'min:0'],
            'harga_dp' => ['nullable', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'facility_ids' => ['required', 'array', 'min:1'],
            'facility_ids.*' => ['exists:facilities,id'],
        ], [
            'kategori.required' => 'Kategori paket wajib dipilih.',
            'kategori.in' => 'Kategori paket tidak valid.',
            'harga.required' => 'Harga paket wajib diisi.',
            'harga.numeric' => 'Harga paket harus berupa angka valid.',
            'harga.min' => 'Harga paket tidak boleh bernilai negatif.',
            'harga_dp.numeric' => 'Harga deposit (DP) harus berupa angka valid.',
            'harga_dp.min' => 'Harga deposit (DP) tidak boleh bernilai negatif.',
            'facility_ids.required' => 'Pilih minimal satu fasilitas untuk paket ini.',
            'facility_ids.min' => 'Pilih minimal satu fasilitas untuk paket ini.',
            'facility_ids.*.exists' => 'Fasilitas yang dipilih tidak ditemukan.',
        ]);

        DB::transaction(function () use ($validated) {
            $paket = PaketPeminjaman::create([
                'nama_paket' => $validated['nama_paket'] ?? null,
                'kategori' => $validated['kategori'],
                'harga' => $validated['harga'],
                'harga_dp' => $validated['harga_dp'] ?? null,
                'deskripsi' => $validated['deskripsi'] ?? null,
            ]);

            // Sinkronisasi tabel detail paket peminjaman
            $paket->facilities()->sync($validated['facility_ids']);
        });

        return redirect()
            ->route('admin.paket.index')
            ->with('success', 'Paket peminjaman aula berhasil ditambahkan.');
    }

    /**
     * Memperbarui data paket peminjaman dan fasilitas detailnya.
     */
    public function update(Request $request, PaketPeminjaman $paket): RedirectResponse
    {
        $validated = $request->validate([
            'nama_paket' => ['nullable', 'string', 'max:150'],
            'kategori' => ['required', 'in:unggulan,terjangkau,standar 1,standar 2,standar 3'],
            'harga' => ['required', 'numeric', 'min:0'],
            'harga_dp' => ['nullable', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'facility_ids' => ['required', 'array', 'min:1'],
            'facility_ids.*' => ['exists:facilities,id'],
        ], [
            'kategori.required' => 'Kategori paket wajib dipilih.',
            'kategori.in' => 'Kategori paket tidak valid.',
            'harga.required' => 'Harga paket wajib diisi.',
            'harga.numeric' => 'Harga paket harus berupa angka valid.',
            'harga.min' => 'Harga paket tidak boleh bernilai negatif.',
            'harga_dp.numeric' => 'Harga deposit (DP) harus berupa angka valid.',
            'harga_dp.min' => 'Harga deposit (DP) tidak boleh bernilai negatif.',
            'facility_ids.required' => 'Pilih minimal satu fasilitas untuk paket ini.',
            'facility_ids.min' => 'Pilih minimal satu fasilitas untuk paket ini.',
            'facility_ids.*.exists' => 'Fasilitas yang dipilih tidak ditemukan.',
        ]);

        DB::transaction(function () use ($paket, $validated) {
            $paket->update([
                'nama_paket' => $validated['nama_paket'] ?? null,
                'kategori' => $validated['kategori'],
                'harga' => $validated['harga'],
                'harga_dp' => $validated['harga_dp'] ?? null,
                'deskripsi' => $validated['deskripsi'] ?? null,
            ]);

            // Sinkronisasi tabel detail paket peminjaman
            $paket->facilities()->sync($validated['facility_ids']);
        });

        return redirect()
            ->route('admin.paket.index')
            ->with('success', 'Paket peminjaman aula berhasil diperbarui.');
    }

    /**
     * Menghapus paket peminjaman aula beserta fasilitas detailnya.
     */
    public function destroy(PaketPeminjaman $paket): RedirectResponse
    {
        // Proteksi: periksa apakah paket sedang digunakan dalam riwayat peminjaman
        $isUsedInBooking = DB::table('peminjamans')
            ->where('paket_peminjaman_id', $paket->id)
            ->exists();

        if ($isUsedInBooking) {
            return redirect()
                ->route('admin.paket.index')
                ->with('error', 'Paket tidak dapat dihapus karena masih digunakan pada data peminjaman aula.');
        }

        DB::transaction(function () use ($paket) {
            $paket->facilities()->detach();
            $paket->delete();
        });

        return redirect()
            ->route('admin.paket.index')
            ->with('success', 'Paket peminjaman aula berhasil dihapus.');
    }
}
