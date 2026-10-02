<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AulaController extends Controller
{
    /**
     * Tampilkan formulir konfigurasi data dan profil aula.
     */
    public function index(): View
    {
        $aula = Aula::query()->first() ?? new Aula;

        return view('Admin.peminjaman.aula.index', compact('aula'));
    }

    /**
     * Simpan pembaruan data dan konfigurasi aula.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:250'],
            'judul' => ['nullable', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
            'dokumentasi' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'dokumentasi_2' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ], [
            'nama.required' => 'Nama aula/gedung wajib diisi.',
            'nama.max' => 'Nama aula maksimal 250 karakter.',
            'judul.max' => 'Tagline/judul promosi maksimal 150 karakter.',
            'deskripsi.max' => 'Deskripsi aula maksimal 5000 karakter.',
            'dokumentasi.image' => 'File foto utama harus berupa gambar.',
            'dokumentasi.mimes' => 'Format foto utama harus jpeg, png, jpg, atau webp.',
            'dokumentasi.max' => 'Ukuran foto utama maksimal 3MB.',
            'dokumentasi_2.image' => 'File foto pendukung harus berupa gambar.',
            'dokumentasi_2.mimes' => 'Format foto pendukung harus jpeg, png, jpg, atau webp.',
            'dokumentasi_2.max' => 'Ukuran foto pendukung maksimal 3MB.',
        ]);

        $aula = Aula::query()->first();

        if (! $aula) {
            $aula = new Aula;
        }

        $aula->nama = $validated['nama'];
        $aula->judul = $validated['judul'] ?? null;
        $aula->deskripsi = $validated['deskripsi'] ?? null;

        // Upload Foto Dokumentasi Utama
        if ($request->hasFile('dokumentasi')) {
            // Hapus file lama jika disimpan di disk public storage
            if ($aula->dokumentasi && Storage::disk('public')->exists($aula->dokumentasi)) {
                Storage::disk('public')->delete($aula->dokumentasi);
            }

            $path = $request->file('dokumentasi')->store('aula', 'public');
            $aula->dokumentasi = $path;
        }

        // Upload Foto Dokumentasi Pendukung
        if ($request->hasFile('dokumentasi_2')) {
            if ($aula->dokumentasi_2 && Storage::disk('public')->exists($aula->dokumentasi_2)) {
                Storage::disk('public')->delete($aula->dokumentasi_2);
            }

            $path2 = $request->file('dokumentasi_2')->store('aula', 'public');
            $aula->dokumentasi_2 = $path2;
        }

        $aula->save();

        return redirect()
            ->route('admin.aula.index')
            ->with('success', 'Konfigurasi data informasi aula berhasil disimpan.');
    }
}
