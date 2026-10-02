<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdukUnggulanRequest;
use App\Models\Jurusan;
use App\Models\Produk;
use App\Models\ProdukUnggulan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProdukUnggulanController extends Controller
{
    /**
     * Tampilkan dashboard produk unggulan.
     */
    public function index(): View
    {
        $produkUnggulan = ProdukUnggulan::current();

        $dokumentasiUrl = array_map(
            fn (string $path): string => Storage::disk('public')->url($path),
            $produkUnggulan->dokumentasi_list,
        );

        $totalProduk = Produk::query()->count();

        $produkPerJurusan = Jurusan::query()
            ->withCount('produk')
            ->orderByDesc('produk_count')
            ->get();

        $produkList = Produk::query()
            ->with('jurusan')
            ->orderByDesc('produkID')
            ->paginate(10)
            ->withQueryString();

        return view('Admin.produk_unggulan.index', [
            'produkUnggulan' => $produkUnggulan,
            'dokumentasiUrl' => $dokumentasiUrl,
            'totalProduk' => $totalProduk,
            'produkPerJurusan' => $produkPerJurusan,
            'produkList' => $produkList,
        ]);
    }

    /**
     * Simpan pengaturan landing page produk unggulan.
     */
    public function update(ProdukUnggulanRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['judul', 'deskripsi']);

        $produkUnggulan = ProdukUnggulan::current();

        $paths = $request->dokumentasiTetap();

        foreach ((array) $request->file('dokumentasi', []) as $file) {
            $paths[] = $file->store('produk-unggulan', 'public');
        }

        $data['dokumentasi'] = implode(',', $paths);

        $produkUnggulan->fill($data)->save();

        return redirect()
            ->route('produk-unggulan.index')
            ->with('success', 'Pengaturan produk unggulan berhasil disimpan.');
    }

    /**
     * Hapus data produk unggulan (reset pengaturan landing page).
     */
    public function destroy(): RedirectResponse
    {
        $produkUnggulan = ProdukUnggulan::query()->first();

        $produkUnggulan?->delete();

        return redirect()
            ->route('produk-unggulan.index')
            ->with('success', 'Data produk unggulan berhasil dihapus.');
    }
}
