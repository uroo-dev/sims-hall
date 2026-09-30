<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\Jurusan;
use App\Models\Ekstrakurikuler;
use App\Models\Prestasi;
use App\Models\ProdukUnggulan;
use App\Models\Dudi;
use App\Models\Aula;
use App\Models\PaketPeminjaman;
use App\Models\Ppdb;
use App\Models\InformasiPpdb;

class PublicController extends Controller
{
    public function landing()
    {
        // Data Utama
        $sekolah = Sekolah::first() ?? new Sekolah();
        
        // Data Pendukung
        $jurusans = Jurusan::all();
        $ekstrakurikulers = Ekstrakurikuler::all();
        $prestasis = Prestasi::orderBy('created_at', 'desc')->take(2)->get();
        $produkUnggulans = ProdukUnggulan::all();
        $dudis = Dudi::all();
        
        // Data Peminjaman Aula (Display Only)
        $aulas = Aula::all();
        $paketPeminjamans = PaketPeminjaman::all();
        
        // Data PPDB
        $ppdb = Ppdb::first();
        $informasiPpdbs = InformasiPpdb::all();

        return view('Public.landing', compact(
            'sekolah',
            'jurusans',
            'ekstrakurikulers',
            'prestasis',
            'produkUnggulans',
            'dudis',
            'aulas',
            'paketPeminjamans',
            'ppdb',
            'informasiPpdbs'
        ));
    }
}