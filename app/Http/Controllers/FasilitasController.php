<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FasilitasController extends Controller
{
    /**
     * Menampilkan daftar fasilitas aula dengan optimasi query.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        // Optimasi: Pilih kolom spesifik dan gunakan withCount untuk mencegah N+1 query
        $facilities = Facility::query()
            ->select(['id', 'judul', 'deskripsi', 'created_at', 'updated_at'])
            ->withCount('paketPeminjamans')
            ->when($search, function ($query, $search) {
                $query->where('judul', 'like', "%{$search}%");
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('Admin.fasilitas.index', compact('facilities', 'search'));
    }

    /**
     * Menyimpan data fasilitas baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:250'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
        ], [
            'judul.required' => 'Judul fasilitas wajib diisi.',
            'judul.max' => 'Judul fasilitas maksimal 250 karakter.',
            'deskripsi.max' => 'Deskripsi fasilitas maksimal 2000 karakter.',
        ]);

        Facility::create($validated);

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas aula berhasil ditambahkan.');
    }

    /**
     * Memperbarui data fasilitas yang sudah ada.
     */
    public function update(Request $request, Facility $facility): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:250'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
        ], [
            'judul.required' => 'Judul fasilitas wajib diisi.',
            'judul.max' => 'Judul fasilitas maksimal 250 karakter.',
            'deskripsi.max' => 'Deskripsi fasilitas maksimal 2000 karakter.',
        ]);

        $facility->update($validated);

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas aula berhasil diperbarui.');
    }

    /**
     * Menghapus fasilitas dari database.
     */
    public function destroy(Facility $facility): RedirectResponse
    {
        $facility->delete();

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas aula berhasil dihapus.');
    }
}
