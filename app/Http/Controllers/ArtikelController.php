<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\KategoriArtikel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArtikelController extends Controller
{
    /**
     * Tampilkan daftar artikel.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $kategoriId = $request->query('kategori_id');

        $query = Artikel::with(['kategori', 'author'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('ringkasan', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['draft', 'published'], true)) {
            $query->where('status', $status);
        }

        if ($kategoriId) {
            $query->where('kategori_artikel_id', $kategoriId);
        }

        $artikels = $query->paginate(10)->withQueryString();
        $kategoris = KategoriArtikel::orderBy('nama')->get();

        return view('Admin.artikel.index', compact('artikels', 'kategoris', 'search', 'status', 'kategoriId'));
    }

    /**
     * Tampilkan form pembuatan artikel baru.
     */
    public function create(): View
    {
        $kategoris = KategoriArtikel::orderBy('nama')->get();

        return view('Admin.artikel.create', compact('kategoris'));
    }

    /**
     * Upload gambar dari editor WYSIWYG ke storage lokal (public disk).
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
        ], [
            'image.required' => 'File gambar wajib dipilih.',
            'image.image' => 'File harus berupa gambar valid.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, webp, atau gif.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $path = $request->file('image')->store('artikel/konten', 'public');
        $url = asset('storage/'.$path);

        return response()->json([
            'success' => true,
            'url' => $url,
            'path' => $path,
        ]);
    }

    /**
     * Simpan artikel baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori_artikel_id' => ['required', 'exists:kategori_artikels,id'],
            'ringkasan' => ['nullable', 'string', 'max:500'],
            'konten' => ['required', 'string'],
            'status' => ['required', 'in:draft,published'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'judul.required' => 'Judul artikel wajib diisi.',
            'kategori_artikel_id.required' => 'Kategori artikel wajib dipilih.',
            'konten.required' => 'Konten artikel wajib diisi.',
            'status.required' => 'Status artikel wajib ditentukan.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $baseSlug = Str::slug($validated['judul']);
        $slug = $baseSlug ?: 'artikel';
        $count = 1;
        while (Artikel::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.(++$count);
        }

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('artikel', 'public');
        }

        $konten = $this->processKontenImages($validated['konten']);

        Artikel::create([
            'kategori_artikel_id' => $validated['kategori_artikel_id'],
            'user_id' => Auth::id(),
            'judul' => $validated['judul'],
            'slug' => $slug,
            'ringkasan' => $validated['ringkasan'] ?? Str::limit(strip_tags($konten), 150),
            'konten' => $konten,
            'gambar' => $gambarPath,
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil dibuat!');
    }

    /**
     * Tampilkan detail atau preview artikel.
     */
    public function show(int $id): View
    {
        $artikel = Artikel::with(['kategori', 'author'])->findOrFail($id);

        return view('Admin.artikel.show', compact('artikel'));
    }

    /**
     * Tampilkan form edit artikel.
     */
    public function edit(int $id): View
    {
        $artikel = Artikel::findOrFail($id);
        $kategoris = KategoriArtikel::orderBy('nama')->get();

        return view('Admin.artikel.edit', compact('artikel', 'kategoris'));
    }

    /**
     * Perbarui data artikel.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $artikel = Artikel::findOrFail($id);

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori_artikel_id' => ['required', 'exists:kategori_artikels,id'],
            'ringkasan' => ['nullable', 'string', 'max:500'],
            'konten' => ['required', 'string'],
            'status' => ['required', 'in:draft,published'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'judul.required' => 'Judul artikel wajib diisi.',
            'kategori_artikel_id.required' => 'Kategori artikel wajib dipilih.',
            'konten.required' => 'Konten artikel wajib diisi.',
            'status.required' => 'Status artikel wajib ditentukan.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $konten = $this->processKontenImages($validated['konten']);

        $data = [
            'kategori_artikel_id' => $validated['kategori_artikel_id'],
            'judul' => $validated['judul'],
            'ringkasan' => $validated['ringkasan'] ?? Str::limit(strip_tags($konten), 150),
            'konten' => $konten,
            'status' => $validated['status'],
        ];

        // Update slug jika judul berubah
        if ($validated['judul'] !== $artikel->judul) {
            $baseSlug = Str::slug($validated['judul']);
            $slug = $baseSlug ?: 'artikel';
            $count = 1;
            while (Artikel::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $baseSlug.'-'.(++$count);
            }
            $data['slug'] = $slug;
        }

        // Handle published_at logic
        if ($validated['status'] === 'published' && ! $artikel->published_at) {
            $data['published_at'] = now();
        } elseif ($validated['status'] === 'draft') {
            $data['published_at'] = null;
        }

        // Handle gambar upload
        if ($request->hasFile('gambar')) {
            if ($artikel->gambar && Storage::disk('public')->exists($artikel->gambar)) {
                Storage::disk('public')->delete($artikel->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('artikel', 'public');
        }

        $artikel->update($data);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil diperbarui!');
    }

    /**
     * Hapus artikel.
     */
    public function destroy(int $id): RedirectResponse
    {
        $artikel = Artikel::findOrFail($id);

        if ($artikel->gambar && Storage::disk('public')->exists($artikel->gambar)) {
            Storage::disk('public')->delete($artikel->gambar);
        }

        $artikel->delete();

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }

    /**
     * Konversi base64 image (jika di-paste langsung ke editor) menjadi file fisik di storage lokal.
     */
    private function processKontenImages(string $konten): string
    {
        if (! str_contains($konten, 'data:image/')) {
            return $konten;
        }

        return preg_replace_callback(
            '/data:image\/([a-zA-Z0-9\+\-]+);base64,([a-zA-Z0-9\/+=\s]+)/i',
            function ($matches) {
                $ext = strtolower($matches[1]);
                if ($ext === 'jpeg') {
                    $ext = 'jpg';
                }
                $base64Data = preg_replace('/\s+/', '', $matches[2]);
                $binary = base64_decode($base64Data);
                if ($binary === false) {
                    return $matches[0];
                }

                $filename = 'artikel/konten/editor_'.Str::random(20).'.'.$ext;
                Storage::disk('public')->put($filename, $binary);

                return asset('storage/'.$filename);
            },
            $konten
        );
    }
}
