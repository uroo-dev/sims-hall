<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\ProdukUnggulan;
use Illuminate\View\View;

class ProdukUnggulanController extends Controller
{
    /**
     * Tampilkan landing page produk unggulan.
     */
    public function index(): View
    {
        $produkUnggulan = ProdukUnggulan::current();

        $jurusan = Jurusan::query()
            ->with(['produk' => fn ($produk) => $produk->orderByDesc('produkID')])
            ->orderBy('jurusanID')
            ->get();

        return view('Public.produk_unggulan', [
            'produkUnggulan' => $produkUnggulan,
            'dokumentasiUrl' => $produkUnggulan->dokumentasi_urls,
            'jurusanList' => $jurusan,
            'jurusanBerproduk' => $jurusan->filter(fn (Jurusan $item): bool => $item->produk->isNotEmpty()),
        ]);
    }
}
