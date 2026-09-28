<?php

namespace App\Http\Controllers;

use App\Http\Requests\LowonganRequest;
use App\Http\Requests\UpdateDudiRequest;
use App\Models\Dudi;
use App\Models\Lowongan;
use App\Models\PenempatanPkl;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * BKK = Bursa Kerja Khusus / Career Center.
 *
 * Tanggung jawab:
 * - Rekap status PKL per kelas & jurusan
 * - Master DUDI + proses ACC tayang di Landing Page
 * - Career Center: CRUD lowongan kerja
 */
class BkkController extends Controller
{
    /**
     * Rekap status PKL per kelas / jurusan.
     *
     * Setiap baris = 1 kombinasi kelas + jurusan, berisi total siswa,
     * jumlah FIX, menunggu, ditolak, dan siswa yang belum punya tempat PKL.
     */
    public function index(Request $request): View
    {
        $filterJurusan = $request->string('jurusan')->trim()->toString();
        $filterKelas = $request->string('kelas')->trim()->toString();
        $filterStatus = $request->string('status')->trim()->toString();

        $query = Siswa::query()
            ->withCount([
                'penempatanFix as fix_count',
                'penempatanPkls as menunggu_count' => fn ($q) => $q->where('status_penempatan', PenempatanPkl::STATUS_PENGAJUAN),
                'penempatanPkls as ditolak_count' => fn ($q) => $q->where('status_penempatan', PenempatanPkl::STATUS_DITOLAK),
            ]);

        if ($filterJurusan !== '') {
            $query->where('jurusan', $filterJurusan);
        }

        if ($filterKelas !== '') {
            $query->where('kelas', $filterKelas);
        }

        // Ambil sekali lalu dipakai untuk rekap & summary (hemat 4x query).
        $siswas = $query->get();

        // Filter status operates pada himpunan siswa yang dihitung di rekap.
        $filtered = match ($filterStatus) {
            'fix' => $siswas->filter(fn (Siswa $s) => $s->fix_count > 0),
            'menunggu' => $siswas->filter(fn (Siswa $s) => $s->menunggu_count > 0),
            'ditolak' => $siswas->filter(fn (Siswa $s) => $s->ditolak_count > 0),
            'belum' => $siswas->filter(fn (Siswa $s) => $s->fix_count === 0),
            default => $siswas,
        };

        $rekap = $filtered
            ->groupBy(fn (Siswa $s) => $s->kelas.' - '.$s->jurusan)
            ->map(function ($group) {
                $total = $group->count();
                $fix = (int) $group->sum('fix_count');
                $menunggu = (int) $group->sum('menunggu_count');
                $ditolak = (int) $group->sum('ditolak_count');

                return (object) [
                    'kelas' => $group->first()->kelas,
                    'jurusan' => $group->first()->jurusan,
                    'total' => $total,
                    'fix' => $fix,
                    'menunggu' => $menunggu,
                    'ditolak' => $ditolak,
                    'belum_pkl' => $total - $fix,
                    'persentase_fix' => $total > 0 ? round($fix / $total * 100) : 0,
                ];
            })
            ->values()
            ->sortByDesc('persentase_fix');

        $summary = (object) [
            'total_siswa' => $siswas->count(),
            'total_fix' => (int) $siswas->sum('fix_count'),
            'total_menunggu' => (int) $siswas->sum('menunggu_count'),
            'total_ditolak' => (int) $siswas->sum('ditolak_count'),
            'total_dudi' => Dudi::count(),
            'total_mitra_resmi' => Dudi::mitraResmi()->count(),
            'total_lowongan_aktif' => Lowongan::active()->count(),
        ];

        return view('Admin.bkk.dashboard', [
            'rekap' => $rekap,
            'summary' => $summary,
            'filterJurusan' => $filterJurusan,
            'filterKelas' => $filterKelas,
            'filterStatus' => $filterStatus,
            'listJurusan' => Siswa::distinct()->orderBy('jurusan')->pluck('jurusan'),
            'listKelas' => Siswa::distinct()->orderBy('kelas')->pluck('kelas'),
            'pageTitle' => 'Dashboard BKK & PKL',
        ]);
    }

    /**
     * Master DUDI: daftar + proses ACC tayang di Landing Page.
     */
    public function dudi(): View
    {
        $dudis = Dudi::query()
            ->withCount(['penempatanFix', 'lowongans'])
            ->orderByDesc('is_mitra_resmi')
            ->orderBy('nama_dudi')
            ->get();

        return view('Admin.bkk.dudi', [
            'dudis' => $dudis,
            'pageTitle' => 'Data DUDI',
        ]);
    }

    /**
     * ACC / batal-ACC DUDI untuk tampil di Landing Page.
     * Bila dinonaktifkan, siswa FIX di DUDI ini otomatis hilang dari landing.
     */
    public function updateDudi(UpdateDudiRequest $request, Dudi $dudi): RedirectResponse
    {
        $data = $request->validated();

        $dudi->fill([
            'tampil_di_landing' => (bool) $data['tampil_di_landing'],
            'is_mitra_resmi' => array_key_exists('is_mitra_resmi', $data)
                ? (bool) $data['is_mitra_resmi']
                : $dudi->is_mitra_resmi,
            'kuota_maksimal' => array_key_exists('kuota_maksimal', $data) && $data['kuota_maksimal'] !== null
                ? (int) $data['kuota_maksimal']
                : $dudi->kuota_maksimal,
        ])->save();

        return back()->with('success', sprintf(
            'DUDI "%s" berhasil %s untuk Landing Page.',
            $dudi->nama_dudi,
            $dudi->tampil_di_landing ? 'di-ACC' : 'dinonaktifkan dari'
        ));
    }

    /**
     * Career Center - daftar lowongan kerja (termasuk yang sudah expired).
     */
    public function lowongan(Request $request): View
    {
        $filterTipe = $request->string('tipe')->trim()->toString();
        $filterStatus = $request->string('status')->trim()->toString();
        $search = $request->string('q')->trim()->toString();

        $query = Lowongan::with('dudi');

        if ($filterTipe !== '') {
            $query->tipe($filterTipe);
        }

        if ($filterStatus === 'aktif') {
            $query->active();
        } elseif ($filterStatus === 'expired') {
            $query->expired();
        } elseif ($filterStatus === 'nonaktif') {
            $query->where('is_active', false);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%{$search}%")
                    ->orWhere('posisi', 'like', "%{$search}%");
            });
        }

        return view('Admin.bkk.lowongan', [
            'lowongans' => $query->orderByDesc('is_active')->orderBy('deadline')->get(),
            'dudis' => Dudi::orderBy('nama_dudi')->get(),
            'filterTipe' => $filterTipe,
            'filterStatus' => $filterStatus,
            'search' => $search,
            'pageTitle' => 'Career Center - Lowongan Kerja',
        ]);
    }

    /**
     * Form tambah / edit lowongan.
     */
    public function lowonganForm(Request $request): View
    {
        $lowongan = $request->route('lowongan');

        return view('Admin.bkk.lowongan-form', [
            'lowongan' => $lowongan instanceof Lowongan ? $lowongan : null,
            'dudis' => Dudi::orderBy('nama_dudi')->get(),
            'pageTitle' => $lowongan ? 'Edit Lowongan Kerja' : 'Tambah Lowongan Kerja',
        ]);
    }

    public function storeLowongan(LowonganRequest $request): RedirectResponse
    {
        $lowongan = Lowongan::create($request->validated());

        return redirect()
            ->route('pkl.lowongan.index')
            ->with('success', "Lowongan \"{$lowongan->posisi}\" di {$lowongan->nama_perusahaan} berhasil ditambahkan.");
    }

    public function updateLowongan(LowonganRequest $request, Lowongan $lowongan): RedirectResponse
    {
        $lowongan->update($request->validated());

        return redirect()
            ->route('pkl.lowongan.index')
            ->with('success', "Lowongan \"{$lowongan->posisi}\" berhasil diperbarui.");
    }

    /**
     * Soft toggle aktif/nonaktif lowongan.
     */
    public function toggleLowongan(Lowongan $lowongan): RedirectResponse
    {
        $lowongan->update(['is_active' => ! $lowongan->is_active]);

        return back()->with('success', sprintf(
            'Lowongan "%s" %s.',
            $lowongan->posisi,
            $lowongan->is_active ? 'diaktifkan kembali' : 'dinonaktifkan'
        ));
    }

    public function destroyLowongan(Lowongan $lowongan): RedirectResponse
    {
        $posisi = $lowongan->posisi;
        $lowongan->delete();

        return redirect()
            ->route('pkl.lowongan.index')
            ->with('success', "Lowongan \"{$posisi}\" berhasil dihapus.");
    }

    /**
     * Daftar siswa PKL + filter status, untuk monitoring.
     */
    public function siswa(Request $request): View
    {
        $filterStatus = $request->string('status')->trim()->toString();
        $search = $request->string('q')->trim()->toString();

        $query = Siswa::with(['penempatanPkls' => fn ($q) => $q->with(['dudi', 'guru'])->latest()])
            ->withCount('penempatanFix');

        if ($filterStatus === 'fix') {
            $query->whereHas('penempatanFix');
        } elseif ($filterStatus === 'belum') {
            $query->whereDoesntHave('penempatanFix');
        } elseif ($filterStatus === 'menunggu') {
            $query->whereHas('penempatanPkls', fn ($q) => $q->where('status_penempatan', PenempatanPkl::STATUS_PENGAJUAN));
        } elseif ($filterStatus === 'ditolak') {
            $query->whereHas('penempatanPkls', fn ($q) => $q->where('status_penempatan', PenempatanPkl::STATUS_DITOLAK));
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")->orWhere('nis', 'like', "%{$search}%");
            });
        }

        return view('Admin.bkk.siswa', [
            'siswas' => $query->orderBy('nama')->get(),
            'filterStatus' => $filterStatus,
            'search' => $search,
            'pageTitle' => 'Data Siswa PKL',
        ]);
    }
}
