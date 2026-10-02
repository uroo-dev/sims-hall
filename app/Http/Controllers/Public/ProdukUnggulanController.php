<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Dudi;
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

        $dudis = Dudi::forLandingPage()->orderBy('nama_dudi')->get();

        if ($dudis->isEmpty()) {
            $dudis = Dudi::whereNotNull('logo')->orderBy('nama_dudi')->get();
        }

        return view('Public.produk_unggulan', [
            'produkUnggulan' => $produkUnggulan,
            'dokumentasiUrl' => $produkUnggulan->dokumentasi_urls,
            'jurusanList' => $jurusan,
            'jurusanBerproduk' => $jurusan->filter(fn (Jurusan $item): bool => $item->produk->isNotEmpty()),
            'dudis' => $dudis,
        ]);
    }
}
