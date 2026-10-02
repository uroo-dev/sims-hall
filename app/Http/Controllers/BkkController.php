<?php

namespace App\Http\Controllers;

use App\Http\Requests\LowonganRequest;
use App\Http\Requests\StoreDudiRequest;
use App\Http\Requests\UpdateDudiRequest;
use App\Models\Dudi;
use App\Models\Lowongan;
use App\Models\PenempatanPkl;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
     * Tambah mitra DUDI baru beserta logo (opsional).
     */
    public function storeDudi(StoreDudiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('dudi-logo', 'public');
        }

        $dudi = Dudi::create([
            'nama_dudi' => $data['nama_dudi'],
            'alamat' => $data['alamat'],
            'kota' => $data['kota'],
            'bidang_usaha' => $data['bidang_usaha'],
            'kontak_person' => $data['kontak_person'] ?? null,
            'no_hp' => $data['no_hp'] ?? null,
            'kuota_maksimal' => (int) ($data['kuota_maksimal'] ?? 0),
            'deskripsi' => $data['deskripsi'] ?? null,
            'is_mitra_resmi' => $request->boolean('is_mitra_resmi', false),
            'tampil_di_landing' => $request->boolean('tampil_di_landing', false),
            'logo' => $data['logo'] ?? null,
        ]);

        return back()->with('success', "Mitra DUDI \"{$dudi->nama_dudi}\" berhasil ditambahkan.");
    }

    /**
     * Update data DUDI (profil, kuota, logo, atau status ACC Landing Page).
     */
    public function updateDudi(UpdateDudiRequest $request, Dudi $dudi): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($dudi->logo && Storage::disk('public')->exists($dudi->logo)) {
                Storage::disk('public')->delete($dudi->logo);
            }
            $data['logo'] = $request->file('logo')->store('dudi-logo', 'public');
        } elseif ($request->boolean('hapus_logo')) {
            if ($dudi->logo && Storage::disk('public')->exists($dudi->logo)) {
                Storage::disk('public')->delete($dudi->logo);
            }
            $data['logo'] = null;
        }

        $fields = [];
        foreach (['nama_dudi', 'alamat', 'kota', 'bidang_usaha', 'kontak_person', 'no_hp', 'kuota_maksimal', 'deskripsi', 'logo'] as $col) {
            if (array_key_exists($col, $data)) {
                $fields[$col] = $data[$col];
            }
        }

        if (array_key_exists('is_mitra_resmi', $data)) {
            $fields['is_mitra_resmi'] = (bool) $data['is_mitra_resmi'];
        }

        if (array_key_exists('tampil_di_landing', $data)) {
            $fields['tampil_di_landing'] = (bool) $data['tampil_di_landing'];
        }

        if (! empty($fields)) {
            $dudi->fill($fields)->save();
        }

        return back()->with('success', sprintf(
            'DUDI "%s" berhasil diperbarui.',
            $dudi->nama_dudi
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
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('lowongan-logo', 'public');
        }

        $lowongan = Lowongan::create($data);

        return redirect()
            ->route('pkl.lowongan.index')
            ->with('success', "Lowongan \"{$lowongan->posisi}\" di {$lowongan->nama_perusahaan} berhasil ditambahkan.");
    }

    public function updateLowongan(LowonganRequest $request, Lowongan $lowongan): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($lowongan->logo && Storage::disk('public')->exists($lowongan->logo)) {
                Storage::disk('public')->delete($lowongan->logo);
            }
            $data['logo'] = $request->file('logo')->store('lowongan-logo', 'public');
        } elseif ($request->boolean('hapus_logo')) {
            if ($lowongan->logo && Storage::disk('public')->exists($lowongan->logo)) {
                Storage::disk('public')->delete($lowongan->logo);
            }
            $data['logo'] = null;
        }

        $lowongan->update($data);

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
        if ($lowongan->logo && Storage::disk('public')->exists($lowongan->logo)) {
            Storage::disk('public')->delete($lowongan->logo);
        }
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
