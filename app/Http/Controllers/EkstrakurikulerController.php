<?php

namespace App\Http\Controllers;

use App\Models\Ekstrakurikuler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EkstrakurikulerController extends Controller
{
    /**
     * Tampilkan daftar ekstrakurikuler & form tambah
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $ekstrakurikulers = Ekstrakurikuler::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('sekolah', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->latest('ekstrakurikulerID')
            ->paginate(10)
            ->withQueryString();

        return view('Admin.kesiswaan.ekstrakurikuler.index', [
            'ekstrakurikulers' => $ekstrakurikulers,
            'search' => $search,
        ]);
    }

    /**
     * Simpan data ekstrakurikuler baru
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'sekolah' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $data = [
            'nama' => $validated['nama'],
            'sekolah' => $validated['sekolah'] ?? 'SMKN 2 Karanganyar',
            'deskripsi' => $validated['deskripsi'] ?? null,
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('ekstrakurikuler/logo', 'public');
        }

        if ($request->hasFile('dokumentasi')) {
            $data['dokumentasi'] = $request->file('dokumentasi')->store('ekstrakurikuler/dokumentasi', 'public');
        }

        Ekstrakurikuler::create($data);

        return redirect()
            ->route('admin.kesiswaan.ekstrakurikuler.index')
            ->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    /**
     * Perbarui data ekstrakurikuler
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $ekskul = Ekstrakurikuler::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'sekolah' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $data = [
            'nama' => $validated['nama'],
            'sekolah' => $validated['sekolah'] ?? $ekskul->sekolah,
            'deskripsi' => $validated['deskripsi'] ?? null,
        ];

        // Ganti logo jika ada upload baru
        if ($request->hasFile('logo')) {
            if ($ekskul->logo && Storage::disk('public')->exists($ekskul->logo)) {
                Storage::disk('public')->delete($ekskul->logo);
            }
            $data['logo'] = $request->file('logo')->store('ekstrakurikuler/logo', 'public');
        }

        // Ganti dokumentasi jika ada upload baru
        if ($request->hasFile('dokumentasi')) {
            if ($ekskul->dokumentasi && Storage::disk('public')->exists($ekskul->dokumentasi)) {
                Storage::disk('public')->delete($ekskul->dokumentasi);
            }
            $data['dokumentasi'] = $request->file('dokumentasi')->store('ekstrakurikuler/dokumentasi', 'public');
        }

        $ekskul->update($data);

        return redirect()
            ->route('admin.kesiswaan.ekstrakurikuler.index')
            ->with('success', 'Ekstrakurikuler berhasil diperbarui.');
    }

    /**
     * Hapus data ekstrakurikuler
     */
    public function destroy($id): RedirectResponse
    {
        $ekskul = Ekstrakurikuler::findOrFail($id);

        // Hapus file dari storage
        if ($ekskul->logo && Storage::disk('public')->exists($ekskul->logo)) {
            Storage::disk('public')->delete($ekskul->logo);
        }
        if ($ekskul->dokumentasi && Storage::disk('public')->exists($ekskul->dokumentasi)) {
            Storage::disk('public')->delete($ekskul->dokumentasi);
        }

        $ekskul->delete();

        return redirect()
            ->route('admin.kesiswaan.ekstrakurikuler.index')
            ->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }
}
