<?php

namespace App\Http\Controllers\Public\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\Ppdb_informasi;
use App\Models\Ppdb_jalur;
use App\Models\Ppdb_jurusan;
use App\Models\Ppdb_master;
use App\Models\Ppdb_persyaratan;
use App\Models\Ppdb_tanggal_penting;
use Illuminate\View\View;

class PpdbController extends Controller
{
    /**
     * Tampilkan halaman publik PPDB.
     */
    public function index(): View
    {
        $jurusans = Ppdb_jurusan::orderBy('id')->get();
        $jalurs = Ppdb_jalur::orderBy('id')->get();

        return view('Public.ppdb.index', [
            'master' => Ppdb_master::first(),
            'informasi' => Ppdb_informasi::first(),
            'persyaratan' => Ppdb_persyaratan::orderBy('id')->get(),
            'agendas' => Ppdb_tanggal_penting::orderBy('tanggal_mulai')->get(),
            'jurusans' => $jurusans,
            'jalurs' => $jalurs,
            'totalDayaTampung' => $jurusans->sum('daya_tampung'),
            'totalPercentase' => $jalurs->sum('percentase'),
        ]);
    }
}
