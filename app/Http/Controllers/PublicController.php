<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Aula;
use App\Models\Dudi;
use App\Models\Ekstrakurikuler;
use App\Models\Facility;
use App\Models\InformasiPpdb;
use App\Models\Jurusan;
use App\Models\KategoriArtikel;
use App\Models\Lowongan;
use App\Models\PaketPeminjaman;
use App\Models\PaymentConfiguration;
use App\Models\Peminjaman;
use App\Models\PenempatanPkl;
use App\Models\Ppdb;
use App\Models\Ppdb_jurusan;
use App\Models\Ppdb_master;
use App\Models\ProdukUnggulan;
use App\Models\Sekolah;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PublicController extends Controller
{
    public function landing()
    {
        // Data Utama
        $sekolah = Sekolah::first() ?? new Sekolah;

        // Data Pendukung
        $jurusans = Jurusan::with('produk')->orderBy('jurusanID')->get();
        $ekstrakurikulers = Ekstrakurikuler::all();
        $prestasies = $this->prestasiesTerbaru(3);
        $produkUnggulan = ProdukUnggulan::current();

        // Hanya DUDI yang benar-benar disetujui tampil di landing. Tanpa filter
        // ini semua mitra — termasuk yang baru diinput BKK dan belum disetujui —
        // ikut bocor ke halaman publik.
        $dudis = Dudi::forLandingPage()->orderBy('nama_dudi')->get();

        // Data Peminjaman Aula (Display Only)
        $aulas = Aula::all();
        $paketPeminjamans = PaketPeminjaman::all();

        // Data PPDB (Gunakan master & jurusan dari modul PPDB baru, dengan fallback model lama)
        $ppdbMaster = Ppdb_master::first();
        $ppdbJurusans = Ppdb_jurusan::orderBy('id')->get();
        $ppdb = $ppdbMaster ?: Ppdb::first();
        $informasiPpdbs = InformasiPpdb::all();

        return view('Public.landing', compact(
            'sekolah',
            'jurusans',
            'ekstrakurikulers',
            'prestasies',
            'produkUnggulan',
            'dudis',
            'aulas',
            'paketPeminjamans',
            'ppdb',
            'ppdbMaster',
            'ppdbJurusans',
            'informasiPpdbs'
        ));
    }

    /**
     * Artikel prestasi untuk ditampilkan di halaman publik.
     *
     * Data prestasi tidak lagi hidup di tabel `prestasis`: sebuah prestasi
     * adalah artikel berstatus published yang masuk kategori "Prestasi".
     */
    protected function prestasiesTerbaru(?int $limit = null)
    {
        return Artikel::with('kategori')
            ->prestasi()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->when($limit, fn ($q) => $q->limit($limit))
            ->get();
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
            // Semua mitra yang dipublikasikan di halaman ini.
            'total_dudi' => $dudis->count(),
            // Hanya yang sudah ditandai resmi oleh sekolah. Dipisahkan dari
            // `total_dudi` supaya label "Mitra Resmi" di publik tidak menghitung
            // mitra terdaftar (is_mitra_resmi = false) sebagai mitra resmi.
            'total_dudi_resmi' => $dudis->where('is_mitra_resmi', true)->count(),
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
            // Lihat catatan pada PublicController::pkl() — label "mitra industri
            // resmi" hanya boleh memakai angka yang benar-benar berstatus resmi.
            'total_dudi_resmi' => $dudis->where('is_mitra_resmi', true)->count(),
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

    /**
     * Halaman publik daftar artikel & informasi sekolah.
     */
    public function informasi(Request $request)
    {
        $search = $request->query('search') ?? $request->query('q');
        $currentKategori = $request->query('kategori');

        $query = Artikel::with(['kategori', 'author'])
            ->where('status', 'published');

        if ($currentKategori) {
            $query->whereHas('kategori', function ($q) use ($currentKategori) {
                $q->where('slug', $currentKategori);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('ringkasan', 'like', "%{$search}%")
                    ->orWhere('konten', 'like', "%{$search}%");
            });
        }

        $artikels = $query->latest('published_at')->paginate(9)->withQueryString();
        $kategoris = KategoriArtikel::orderBy('nama')->get();

        return view('Public.informasi', compact('artikels', 'kategoris', 'currentKategori', 'search'));
    }

    /**
     * Halaman publik detail artikel/berita.
     */
    public function informasiDetail(string $slug)
    {
        $artikel = Artikel::with(['kategori', 'author'])
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment total views
        $artikel->increment('views');

        // Artikel populer / berita lainnya untuk sidebar
        $artikelPopulers = Artikel::where('status', 'published')
            ->where('id', '!=', $artikel->id)
            ->orderByDesc('views')
            ->take(3)
            ->get();

        // Data master PPDB untuk banner sidebar
        $ppdbMaster = Ppdb_master::first();

        return view('Public.informasi-detail', compact('artikel', 'artikelPopulers', 'ppdbMaster'));
    }

    /**
     * XML Sitemap Dinamis untuk Mesin Pencari (Google, Bing, dll).
     */
    public function sitemap(): Response
    {
        $urls = [
            ['loc' => url('/'), 'lastmod' => now()->toDateString(), 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => route('profil'), 'lastmod' => now()->toDateString(), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => route('kesiswaan'), 'lastmod' => now()->toDateString(), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('produk-unggulan'), 'lastmod' => now()->toDateString(), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('layanan-peminjaman'), 'lastmod' => now()->toDateString(), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('ppdb'), 'lastmod' => now()->toDateString(), 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => route('pkl'), 'lastmod' => now()->toDateString(), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('bkk'), 'lastmod' => now()->toDateString(), 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => route('informasi'), 'lastmod' => now()->toDateString(), 'changefreq' => 'daily', 'priority' => '0.9'],
        ];

        // Artikel Berita / Pengumuman Terbit
        $artikels = Artikel::where('status', 'published')->latest('published_at')->get(['slug', 'updated_at']);
        foreach ($artikels as $artikel) {
            $urls[] = [
                'loc' => route('informasi.show', $artikel->slug),
                'lastmod' => $artikel->updated_at ? $artikel->updated_at->toDateString() : now()->toDateString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        // Lowongan Kerja BKK Aktif
        $lowongans = Lowongan::active()->get(['id', 'updated_at']);
        foreach ($lowongans as $lowongan) {
            $urls[] = [
                'loc' => route('bkk.detail', $lowongan->id),
                'lastmod' => $lowongan->updated_at ? $lowongan->updated_at->toDateString() : now()->toDateString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        // Mitra DUDI / PKL Landing
        $dudis = Dudi::forLandingPage()->get(['id', 'updated_at']);
        foreach ($dudis as $dudi) {
            $urls[] = [
                'loc' => route('pkl.detail', $dudi->id),
                'lastmod' => $dudi->updated_at ? $dudi->updated_at->toDateString() : now()->toDateString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        }

        return response()->view('Public.sitemap', compact('urls'))
            ->header('Content-Type', 'text/xml; charset=utf-8');
    }
}
