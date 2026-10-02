<?php

namespace App\Http\Controllers;

use App\Models\Ekstrakurikuler;
use App\Models\Kesiswaan;
use App\Models\Prestasi;
use App\Models\TataTertib;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KesiswaanController extends Controller
{
    /**
     * Dashboard Kesiswaan Admin
     */
    public function index(): View
    {
        $kesiswaan = Kesiswaan::current();
        $ekstrakurikulers = Ekstrakurikuler::query()->latest('ekstrakurikulerID')->take(6)->get();
        $totalTataTertib = TataTertib::count();

        // Count prestasi untuk chart
        $prestasiAkademik = Prestasi::where('kategori', 'like', '%non%')->count();
        $totalPrestasi = Prestasi::count();
        $prestasiNonAkademik = $prestasiAkademik;
        $prestasiAkademikOnly = $totalPrestasi - $prestasiNonAkademik;

        return view('Admin.kesiswaan.dashboard', [
            'kesiswaan' => $kesiswaan,
            'ekstrakurikulers' => $ekstrakurikulers,
            'totalTataTertib' => $totalTataTertib,
            'prestasiAkademik' => $prestasiAkademikOnly,
            'prestasiNonAkademik' => $prestasiNonAkademik,
            'dokumentasiUrls' => $kesiswaan->dokumentasi_urls,
        ]);
    }

    /**
     * Update Pengaturan Kesiswaan (Judul, Deskripsi, Dokumentasi)
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'dokumentasi' => 'nullable|array|max:2',
            'dokumentasi.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'hapus_dokumentasi' => 'nullable|array',
        ]);

        $kesiswaan = Kesiswaan::current();
        $currentDocs = $kesiswaan->dokumentasi_list;

        if (empty($currentDocs)) {
            $currentDocs = [
                'assets/prestasi/banner_terbaru_2.png',
                'assets/prestasi/banner_terbaru_1.png',
            ];
        }

        // Upload dokumentasi baru per slot (0 atau 1)
        if ($request->hasFile('dokumentasi')) {
            foreach ($request->file('dokumentasi') as $index => $file) {
                if ($file && $file->isValid()) {
                    if (isset($currentDocs[$index]) && ! str_starts_with($currentDocs[$index], 'assets/') && Storage::disk('public')->exists($currentDocs[$index])) {
                        Storage::disk('public')->delete($currentDocs[$index]);
                    }
                    $path = $file->store('kesiswaan', 'public');
                    $currentDocs[$index] = $path;
                }
            }
        }

        $kesiswaan->update([
            'judul' => $request->input('judul'),
            'deskripsi' => $request->input('deskripsi'),
            'dokumentasi' => json_encode(array_values($currentDocs)),
        ]);

        return redirect()
            ->route('admin.kesiswaan.index')
            ->with('success', 'Pengaturan Kesiswaan berhasil disimpan.');
    }
}
