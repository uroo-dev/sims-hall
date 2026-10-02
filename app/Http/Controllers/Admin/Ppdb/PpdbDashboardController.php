<?php

namespace App\Http\Controllers\Admin\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\Ppdb_master;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PpdbDashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard PPDB.
     */
    public function index(): View
    {
        $master = Ppdb_master::first();

        return view('Admin.ppdb.dashboard', [
            'master' => $master,
            'bannerUrl' => $master?->banner_img
                ? Storage::disk('public')->url($master->banner_img)
                : null,
        ]);
    }

    /**
     * Simpan atau perbarui data master PPDB.
     */
    public function masterUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string'],
            'banner_img' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_gambar' => ['nullable', 'boolean'],
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'banner_img.image' => 'Banner harus berupa gambar.',
            'banner_img.mimes' => 'Format banner harus JPG, PNG, atau WEBP.',
            'banner_img.max' => 'Ukuran banner maksimal 2 MB.',
        ]);

        $master = Ppdb_master::first();
        $pathLama = $master?->banner_img;

        if ($request->hasFile('banner_img')) {
            $path = $request->file('banner_img')->store('ppdb', 'public');
        } elseif ($request->boolean('hapus_gambar')) {
            $path = null;
        } else {
            $path = $pathLama;
        }

        if ($pathLama && $pathLama !== $path) {
            Storage::disk('public')->delete($pathLama);
        }

        $master ??= new Ppdb_master;
        $master->fill([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'banner_img' => $path,
        ])->save();

        return redirect()
            ->back()
            ->with('success', 'Data master PPDB berhasil disimpan.');
    }
}
