<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Dudi;
use App\Models\Ekstrakurikuler;
use App\Models\Facility;
use App\Models\InformasiPpdb;
use App\Models\Jurusan;
use App\Models\Lowongan;
use App\Models\PaketPeminjaman;
use App\Models\PaymentConfiguration;
use App\Models\Peminjaman;
use App\Models\PenempatanPkl;
use App\Models\Ppdb;
use App\Models\Prestasi;
use App\Models\ProdukUnggulan;
use App\Models\Sekolah;
use App\Models\Siswa;
use Carbon\Carbon;

class PublicController extends Controller
{
    public function landing()
    {
        // Data Utama
        $sekolah = Sekolah::first() ?? new Sekolah;

        // Data Pendukung
        $jurusans = Jurusan::all();
        $ekstrakurikulers = Ekstrakurikuler::all();
        $prestasis = Prestasi::orderBy('created_at', 'desc')->take(2)->get();
        $produkUnggulans = ProdukUnggulan::all();

        // Hanya DUDI yang benar-benar disetujui tampil di landing. Tanpa filter
        // ini semua mitra — termasuk yang baru diinput BKK dan belum disetujui —
        // ikut bocor ke halaman publik.
        $dudis = Dudi::forLandingPage()->orderBy('nama_dudi')->get();

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

    /**
     * Halaman publik PKL (Praktik Kerja Lapangan).
     */
    public function pkl()
    {
        $sekolah = Sekolah::first() ?? new Sekolah;
        $jurusans = Jurusan::all();

        $dudis = Dudi::forLandingPage()
            ->with(['jurusan', 'penempatanFix'])
            ->withCount('penempatanFix')
            ->orderBy('nama_dudi')
            ->get();

        $rekap = [
            'total_dudi' => $dudis->count(),
            'siswa_fix' => PenempatanPkl::status(PenempatanPkl::STATUS_FIX)->count(),
            'siswa_menunggu' => PenempatanPkl::status(PenempatanPkl::STATUS_PENGAJUAN)->count(),
            'siswa_belum_pkl' => Siswa::belumPkl()->count(),
        ];

        $totalKuota = (int) $dudis->sum('kuota_maksimal');
        $kuotaTerpakai = (int) $dudis->sum('penempatan_fix_count');

        return view('Public.pkl', compact(
            'sekolah',
            'jurusans',
            'dudis',
            'rekap',
            'totalKuota',
            'kuotaTerpakai'
        ));
    }

    /**
     * Halaman detail Mitra DUDI / PKL.
     */
    public function pklDetail(Dudi $dudi)
    {
        abort_unless($dudi->tampil_di_landing, 404);

        $dudi->load(['jurusan', 'penempatanFix']);

        $penempatans = PenempatanPkl::with(['siswa', 'guru', 'suratPengajuan'])
            ->where('dudi_id', $dudi->id)
            ->whereIn('status_penempatan', [PenempatanPkl::STATUS_FIX, PenempatanPkl::STATUS_PENGAJUAN])
            ->latest()
            ->get();

        $programs = array_values(array_filter([
            $dudi->program_1,
            $dudi->program_2,
            $dudi->program_3,
        ]));

        if (empty($programs)) {
            $programs = [
                'Teknisi Ahli Industri',
                'Quality Control & Maintenance',
                'Digital Systems & Administration',
            ];
        }

        $siswaFixCount = $dudi->penempatanFix()->count();

        return view('Public.pkl-detail', compact(
            'dudi',
            'penempatans',
            'programs',
            'siswaFixCount'
        ));
    }

    /**
     * Halaman publik BKK (Bursa Kerja Khusus).
     */
    public function bkk()
    {
        $sekolah = Sekolah::first() ?? new Sekolah;
        $jurusans = Jurusan::all();

        $lowongans = Lowongan::active()
            ->with('dudi:id,nama_dudi,kota,logo,bidang_usaha,deskripsi')
            ->orderBy('deadline')
            ->get();

        $totalLowongan = $lowongans->count();
        $totalPerusahaan = Dudi::has('lowongans')->count();

        return view('Public.bkk', compact(
            'sekolah',
            'jurusans',
            'lowongans',
            'totalLowongan',
            'totalPerusahaan'
        ));
    }

    /**
     * Halaman detail Lowongan Kerja BKK.
     */
    public function bkkDetail(Lowongan $lowongan)
    {
        abort_unless($lowongan->is_active, 404);

        $lowongan->load('dudi');

        $lowonganLainnya = Lowongan::active()
            ->where('id', '!=', $lowongan->id)
            ->with('dudi')
            ->take(3)
            ->get();

        return view('Public.bkk-detail', compact(
            'lowongan',
            'lowonganLainnya'
        ));
    }

    /**
     * Halaman publik PKL & BKK (Legacy Hub).
     *
     * Data yang ditampilkan bukan dummy: mitra DUDI yang memang disetujui
     * tampil di landing (`tampil_di_landing`), lowongan yang masih aktif
     * (deadline belum lewat), dan rekap angka penempatan siswa — semuanya
     * diambil lewat scope yang sama dengan modul PKL & BKK di dashboard,
     * supaya angka publik tidak pernah berbeda dengan angka internal.
     */
    public function pklBkk()
    {
        $dudis = Dudi::forLandingPage()
            ->with('jurusan')
            ->withCount('penempatanFix')
            ->orderBy('nama_dudi')
            ->get();

        $lowongans = Lowongan::active()
            ->with('dudi:id,nama_dudi,kota,logo')
            ->orderBy('deadline')
            ->get();

        $rekap = [
            'siswa_fix' => PenempatanPkl::status(PenempatanPkl::STATUS_FIX)->count(),
            'siswa_menunggu' => PenempatanPkl::status(PenempatanPkl::STATUS_PENGAJUAN)->count(),
            'siswa_belum_pkl' => Siswa::belumPkl()->count(),
            'total_dudi' => $dudis->count(),
            'total_lowongan' => $lowongans->count(),
        ];

        // Kapasitas versus terpakai, dihitung hanya dari DUDI yang tampil publik.
        $totalKuota = (int) $dudis->sum('kuota_maksimal');
        $kuotaTerpakai = (int) $dudis->sum('penempatan_fix_count');

        return view('Public.pkl-bkk', compact(
            'dudis',
            'lowongans',
            'rekap',
            'totalKuota',
            'kuotaTerpakai',
        ));
    }

    /**
     * Halaman publik Layanan Peminjaman Aula.
     */
    public function layananPeminjaman()
    {
        $sekolah = Sekolah::first() ?? new Sekolah;
        $aula = Aula::first() ?? new Aula;
        $paketPeminjamans = PaketPeminjaman::with('facilities')->get();
        $facilities = Facility::all();
        $paymentConfig = PaymentConfiguration::current();

        $peminjamans = Peminjaman::whereNotIn('status', ['rejected', 'cancelled'])
            ->get(['id', 'nama', 'tanggal_mulai', 'tanggal_selesai', 'status']);

        $bookedDates = [];
        foreach ($peminjamans as $peminjaman) {
            if ($peminjaman->tanggal_mulai && $peminjaman->tanggal_selesai) {
                $start = Carbon::parse($peminjaman->tanggal_mulai)->startOfDay();
                $end = Carbon::parse($peminjaman->tanggal_selesai)->startOfDay();

                while ($start->lte($end)) {
                    $key = $start->format('Y-m-d');
                    $bookedDates[$key] = [
                        'status' => $peminjaman->status,
                        'nama' => $peminjaman->nama,
                    ];
                    $start->addDay();
                }
            }
        }

        return view('Public.layanan-peminjaman', compact(
            'sekolah',
            'aula',
            'paketPeminjamans',
            'facilities',
            'paymentConfig',
            'bookedDates'
        ));
    }
}
