<?php

namespace App\Http\Controllers;

use App\Models\KategoriArtikel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KategoriArtikelController extends Controller
{
    /**
     * Tampilkan daftar kategori artikel.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $query = KategoriArtikel::withCount('artikels')->latest();

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%");
        }

        $kategoris = $query->paginate(10)->withQueryString();

        return view('Admin.kategori_artikel.index', compact('kategoris', 'search'));
    }

    /**
     * Simpan kategori artikel baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.max' => 'Nama kategori maksimal 100 karakter.',
        ]);

        $baseSlug = Str::slug($validated['nama']);
        $slug = $baseSlug ?: 'kategori';
        $count = 1;
        while (KategoriArtikel::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.(++$count);
        }

        KategoriArtikel::create([
            'nama' => $validated['nama'],
            'slug' => $slug,
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        return redirect()->route('admin.kategori-artikel.index')
            ->with('success', 'Kategori artikel berhasil ditambahkan!');
    }

    /**
     * Perbarui data kategori artikel.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $kategori = KategoriArtikel::findOrFail($id);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.max' => 'Nama kategori maksimal 100 karakter.',
        ]);

        $data = [
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ];

        if ($validated['nama'] !== $kategori->nama) {
            $baseSlug = Str::slug($validated['nama']);
            $slug = $baseSlug ?: 'kategori';
            $count = 1;
            while (KategoriArtikel::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $baseSlug.'-'.(++$count);
            }
            $data['slug'] = $slug;
        }

        $kategori->update($data);

        return redirect()->route('admin.kategori-artikel.index')
            ->with('success', 'Kategori artikel berhasil diperbarui!');
    }

    /**
     * Hapus kategori artikel.
     */
    public function destroy(int $id): RedirectResponse
    {
        $kategori = KategoriArtikel::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.kategori-artikel.index')
            ->with('success', 'Kategori artikel berhasil dihapus!');
    }
}
