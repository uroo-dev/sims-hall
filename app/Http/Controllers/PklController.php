<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengajuanPklRequest;
use App\Http\Requests\UpdateStatusPenempatanRequest;
use App\Models\Dudi;
use App\Models\Guru;
use App\Models\PenempatanPkl;
use App\Models\Siswa;
use App\Models\SuratPengajuan;
use App\Services\SuratPengajuanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

/**
 * PKL = Praktik Kerja Lapangan.
 *
 * Tanggung jawab:
 * - Daftar pengajuan & monitoring status penempatan
 * - Buat pengajuan (multi siswa) + auto-generate surat PDF
 * - Update status balasan DUDI (FIX / ditolak)
 * - ACC DUDI untuk Landing Page
 * - Download / regenerate surat PDF
 */
class PklController extends Controller
{
    public function __construct(private readonly SuratPengajuanService $suratService) {}

    /**
     * Daftar seluruh penempatan PKL berhierarki (Jurusan -> Kelas -> Siswa)
     * lengkap dengan persentase progres, aksi download PDF, dan link perbaikan surat.
     */
    public function index(Request $request): View
    {
        $filterJurusan = $request->string('jurusan')->trim()->toString();
        $filterKelas = $request->string('kelas')->trim()->toString();
        $filterStatus = $request->string('status')->trim()->toString();
        $filterDudi = $request->string('dudi_id')->trim()->toString();
        $search = $request->string('q')->trim()->toString();

        // 1. Data summary seluruh siswa & relasi (1 query anti N+1)
        $siswasSummary = Siswa::query()
            ->withCount([
                'penempatanFix as fix_count',
                'penempatanPkls as pengajuan_count' => fn ($q) => $q->where('status_penempatan', PenempatanPkl::STATUS_PENGAJUAN),
                'penempatanPkls as ditolak_count' => fn ($q) => $q->where('status_penempatan', PenempatanPkl::STATUS_DITOLAK),
            ])
            ->get();

        // 2. Rekap per Jurusan (Level 1)
        $jurusanList = $siswasSummary
            ->groupBy('jurusan')
            ->map(function ($group, $jurusan) {
                $total = $group->count();
                $fix = (int) $group->sum('fix_count');
                $pengajuan = (int) $group->sum('pengajuan_count');
                $ditolak = (int) $group->sum('ditolak_count');
                $belum = $total - $fix;
                $kelasList = $group->pluck('kelas')->filter()->unique()->sort()->values()->all();

                return (object) [
                    'nama' => $jurusan ?: 'Umum / Lainnya',
                    'total' => $total,
                    'fix' => $fix,
                    'pengajuan' => $pengajuan,
                    'ditolak' => $ditolak,
                    'belum' => $belum,
                    'persentase_fix' => $total > 0 ? (int) round(($fix / $total) * 100) : 0,
                    'list_kelas' => $kelasList,
                ];
            })
            ->values()
            ->sortBy('nama');

        // 3. Rekap per Kelas untuk Jurusan terpilih (Level 2)
        $kelasList = collect();
        if ($filterJurusan !== '') {
            $kelasList = $siswasSummary
                ->where('jurusan', $filterJurusan)
                ->groupBy('kelas')
                ->map(function ($group, $kelas) use ($filterJurusan) {
                    $total = $group->count();
                    $fix = (int) $group->sum('fix_count');
                    $pengajuan = (int) $group->sum('pengajuan_count');
                    $ditolak = (int) $group->sum('ditolak_count');
                    $belum = $total - $fix;

                    return (object) [
                        'kelas' => $kelas ?: 'Tanpa Kelas',
                        'jurusan' => $filterJurusan,
                        'total' => $total,
                        'fix' => $fix,
                        'pengajuan' => $pengajuan,
                        'ditolak' => $ditolak,
                        'belum' => $belum,
                        'persentase_fix' => $total > 0 ? (int) round(($fix / $total) * 100) : 0,
                    ];
                })
                ->values()
                ->sortBy('kelas');
        }

        // 4. Data Siswa dalam Kelas terpilih (Level 3)
        $siswasKelas = collect();
        $kelasStats = null;
        if ($filterKelas !== '') {
            $siswaQuery = Siswa::query()
                ->where('kelas', $filterKelas)
                ->with([
                    'penempatanPkls' => fn ($q) => $q->with(['dudi', 'guru', 'suratPengajuan'])->latest(),
                ])
                ->withCount('penempatanFix');

            if ($filterJurusan !== '') {
                $siswaQuery->where('jurusan', $filterJurusan);
            }

            if ($search !== '') {
                $siswaQuery->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                });
            }

            $siswasKelas = $siswaQuery->orderBy('nama')->get();

            if ($filterStatus === 'fix') {
                $siswasKelas = $siswasKelas->filter(fn ($s) => $s->penempatan_fix_count > 0);
            } elseif ($filterStatus === 'pengajuan') {
                $siswasKelas = $siswasKelas->filter(fn ($s) => $s->penempatanPkls->first()?->status_penempatan === PenempatanPkl::STATUS_PENGAJUAN);
            } elseif ($filterStatus === 'ditolak') {
                $siswasKelas = $siswasKelas->filter(fn ($s) => $s->penempatanPkls->first()?->status_penempatan === PenempatanPkl::STATUS_DITOLAK);
            } elseif ($filterStatus === 'belum') {
                $siswasKelas = $siswasKelas->filter(fn ($s) => $s->penempatan_fix_count === 0);
            }

            $totalK = $siswasSummary->where('kelas', $filterKelas)->when($filterJurusan, fn ($c) => $c->where('jurusan', $filterJurusan))->count();
            $fixK = (int) $siswasSummary->where('kelas', $filterKelas)->when($filterJurusan, fn ($c) => $c->where('jurusan', $filterJurusan))->sum('fix_count');
            $pengajuanK = (int) $siswasSummary->where('kelas', $filterKelas)->when($filterJurusan, fn ($c) => $c->where('jurusan', $filterJurusan))->sum('pengajuan_count');
            $ditolakK = (int) $siswasSummary->where('kelas', $filterKelas)->when($filterJurusan, fn ($c) => $c->where('jurusan', $filterJurusan))->sum('ditolak_count');
            $belumK = $totalK - $fixK;

            $kelasStats = (object) [
                'kelas' => $filterKelas,
                'jurusan' => $filterJurusan ?: ($siswasKelas->first()?->jurusan ?? '-'),
                'total' => $totalK,
                'fix' => $fixK,
                'pengajuan' => $pengajuanK,
                'ditolak' => $ditolakK,
                'belum' => $belumK,
                'persentase_fix' => $totalK > 0 ? (int) round(($fixK / $totalK) * 100) : 0,
            ];
        }

        // 5. Query flat penempatan untuk pencarian langsung / filter khusus
        $penempatanQuery = PenempatanPkl::query()->withRelasi();
        if ($filterStatus !== '') {
            $penempatanQuery->status($filterStatus);
        }
        if ($filterDudi !== '') {
            $penempatanQuery->where('dudi_id', $filterDudi);
        }
        if ($search !== '') {
            $penempatanQuery->where(function ($q) use ($search) {
                $q->whereHas('siswa', fn ($s) => $s->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('dudi', fn ($d) => $d->where('nama_dudi', 'like', "%{$search}%"));
            });
        }

        // Tentukan level tampilan
        $viewLevel = 'jurusan';
        if ($filterKelas !== '') {
            $viewLevel = 'siswa';
        } elseif ($filterJurusan !== '') {
            $viewLevel = 'kelas';
        } elseif ($search !== '' || $filterStatus !== '' || $filterDudi !== '') {
            $viewLevel = 'search';
        }

        return view('Admin.pkl.index', [
            'viewLevel' => $viewLevel,
            'jurusanList' => $jurusanList,
            'kelasList' => $kelasList,
            'siswasKelas' => $siswasKelas,
            'kelasStats' => $kelasStats,
            'penempatans' => $penempatanQuery->orderByDesc('created_at')->get(),
            'dudis' => Dudi::orderBy('nama_dudi')->get(),
            'filterJurusan' => $filterJurusan,
            'filterKelas' => $filterKelas,
            'filterStatus' => $filterStatus,
            'filterDudi' => $filterDudi,
            'search' => $search,
            'pageTitle' => 'Data Penempatan PKL',
        ]);
    }

    /**
     * Form pengajuan PKL baru: pilih/ketik DUDI, multi-select siswa, 1 guru.
     */
    public function create(): View
    {
        return view('Admin.pkl.create', [
            'dudis' => Dudi::kapasitasTersisa()->orderBy('nama_dudi')->get(),
            'siswas' => Siswa::belumPkl()->orderBy('kelas')->orderBy('nama')->get(),
            'gurus' => Guru::orderBy('nama')->get(),
            'pageTitle' => 'Buat Pengajuan PKL',
        ]);
    }

    /**
     * Simpan pengajuan + generate surat PDF otomatis.
     */
    public function store(StorePengajuanPklRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            $dudi = $this->suratService->ensureDudi($data);
            $guru = Guru::findOrFail($data['guru_id']);

            [$surat, $pdfGenerated] = $this->suratService->buatSuratDenganPdf(
                $dudi,
                $data['siswa_ids'],
                $guru,
                [
                    'tanggal_surat' => $data['tanggal_surat'],
                    'tgl_mulai_pkl' => $data['tgl_mulai_pkl'],
                    'tgl_selesai_pkl' => $data['tgl_selesai_pkl'],
                ]
            );
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Pengajuan gagal disimpan: '.$e->getMessage());
        }

        $jumlahSiswa = count($data['siswa_ids']);

        $message = sprintf(
            'Pengajuan PKL %s untuk %d siswa di %s berhasil disimpan.',
            $surat->nomor_surat,
            $jumlahSiswa,
            $dudi->nama_dudi
        );

        if ($pdfGenerated) {
            $message .= ' Surat PDF berhasil digenerate.';
        } else {
            $message .= ' Catatan: generate PDF gagal, bisa diulang dari daftar pengajuan.';
        }

        return redirect()
            ->route('pkl.surat.show', $surat)
            ->with('success', $message);
    }

    /**
     * Detail satu surat pengajuan + opsi update data & regenerasi PDF.
     */
    public function showSurat(SuratPengajuan $surat): View
    {
        $surat->load(['dudi', 'penempatanPkls.siswa', 'penempatanPkls.guru']);

        $assignedSiswaIds = $surat->penempatanPkls->pluck('siswa_id')->all();
        $availableSiswas = Siswa::whereIn('id', $assignedSiswaIds)
            ->orWhereDoesntHave('penempatanFix')
            ->orderBy('kelas')
            ->orderBy('nama')
            ->get();

        return view('Admin.pkl.surat', [
            'surat' => $surat,
            'dudis' => Dudi::orderBy('nama_dudi')->get(),
            'gurus' => Guru::orderBy('nama')->get(),
            'availableSiswas' => $availableSiswas,
            'pageTitle' => 'Surat '.$surat->nomor_surat,
        ]);
    }

    /**
     * Update data surat pengajuan (DUDI, guru, tanggal, atau siswa terpilih) + render ulang PDF otomatis.
     */
    public function updateSurat(Request $request, SuratPengajuan $surat): RedirectResponse
    {
        $validated = $request->validate([
            'dudi_id' => ['required', 'integer', 'exists:dudis,id'],
            'guru_id' => ['required', 'integer', 'exists:gurus,id'],
            'tanggal_surat' => ['required', 'date'],
            'tgl_mulai_pkl' => ['required', 'date'],
            'tgl_selesai_pkl' => ['required', 'date', 'after_or_equal:tgl_mulai_pkl'],
            'siswa_ids' => ['required', 'array', 'min:1'],
            'siswa_ids.*' => ['required', 'integer', 'exists:siswas,id'],
        ]);

        DB::transaction(function () use ($surat, $validated) {
            $surat->update([
                'dudi_id' => $validated['dudi_id'],
                'tanggal_surat' => $validated['tanggal_surat'],
                'tgl_mulai_pkl' => $validated['tgl_mulai_pkl'],
                'tgl_selesai_pkl' => $validated['tgl_selesai_pkl'],
            ]);

            $existing = $surat->penempatanPkls()->pluck('status_penempatan', 'siswa_id')->all();
            $newSiswaIds = array_map('intval', $validated['siswa_ids']);

            // Hapus siswa yang dikeluarkan dari surat ini
            $surat->penempatanPkls()->whereNotIn('siswa_id', $newSiswaIds)->delete();

            // Simpan / update siswa yang terpilih
            foreach ($newSiswaIds as $siswaId) {
                $status = $existing[$siswaId] ?? PenempatanPkl::STATUS_PENGAJUAN;
                PenempatanPkl::updateOrCreate(
                    [
                        'surat_pengajuan_id' => $surat->id,
                        'siswa_id' => $siswaId,
                    ],
                    [
                        'dudi_id' => $validated['dudi_id'],
                        'guru_id' => $validated['guru_id'],
                        'status_penempatan' => $status,
                    ]
                );
            }
        });

        // Regenerate dokumen PDF dengan data terbaru
        try {
            $this->suratService->renderPdf($surat->fresh());
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'Data surat berhasil di-update, namun gagal generate PDF: '.$e->getMessage());
        }

        return redirect()
            ->route('pkl.surat.show', $surat)
            ->with('success', 'Surat '.$surat->nomor_surat.' dan dokumen PDF berhasil diperbarui.');
    }

    /**
     * Download file PDF surat.
     */
    public function downloadSurat(SuratPengajuan $surat): BinaryFileResponse|RedirectResponse
    {
        try {
            $path = $this->suratService->absolutePath($surat);
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        $filename = 'surat-pengajuan-pkl-'.str_replace('/', '-', $surat->nomor_surat).'.pdf';

        return response()->download($path, $filename);
    }

    /**
     * Generate ulang PDF (dipakai bila generate otomatis gagal).
     */
    public function regeneratePdf(SuratPengajuan $surat): RedirectResponse
    {
        try {
            $this->suratService->renderPdf($surat);
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'Generate PDF gagal: '.$e->getMessage());
        }

        return back()->with('success', 'PDF surat '.$surat->nomor_surat.' berhasil digenerate ulang.');
    }

    /**
     * Update status balasan DUDI.
     *
     * - FIX     : DUDI menerima, siswa punya tempat PKL
     * - ditolak : DUDI menolak, siswa kembali ke "Belum Ada Tempat PKL"
     *
     * Pakai transaction + lockForUpdate agar aman dari update bersamaan.
     */
    public function updateStatus(UpdateStatusPenempatanRequest $request, PenempatanPkl $penempatan): RedirectResponse
    {
        $data = $request->validated();
        $status = $data['status_penempatan'];

        DB::transaction(function () use ($penempatan, $status, $data) {
            $locked = PenempatanPkl::query()
                ->lockForUpdate()
                ->findOrFail($penempatan->id);

            $locked->update([
                'status_penempatan' => $status,
                'catatan' => $data['catatan'] ?? null,
            ]);
        });

        $siswa = $penempatan->siswa;
        $dudi = $penempatan->dudi;

        $message = $status === PenempatanPkl::STATUS_FIX
            ? sprintf('DUDI %s menerima pengajuan PKL %s. Status diubah ke FIX.', $dudi->nama_dudi, $siswa->nama)
            : sprintf('DUDI %s menolak pengajuan PKL %s. Siswa kembali ke status Belum Ada Tempat PKL.', $dudi->nama_dudi, $siswa->nama);

        return back()->with('success', $message);
    }

    /**
     * Update status beberapa penempatan sekaligus (bulk action dari tabel).
     *
     * @param  array<int, int>  $ids
     */
    public function bulkUpdateStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:penempatan_pkls,id'],
            'status_penempatan' => ['required', 'in:'.PenempatanPkl::STATUS_FIX.','.PenempatanPkl::STATUS_DITOLAK],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ]);

        $ids = $validated['ids'];
        $status = $validated['status_penempatan'];

        $updated = PenempatanPkl::whereIn('id', $ids)
            ->update([
                'status_penempatan' => $status,
                'catatan' => $validated['catatan'] ?? null,
                'updated_at' => now(),
            ]);

        return back()->with('success', sprintf(
            '%d penempatan PKL berhasil di-update menjadi %s.',
            $updated,
            PenempatanPkl::STATUS_LABEL[$status] ?? $status
        ));
    }

    /**
     * ACC DUDI untuk tampil di Landing Page.
     * Ini yang dipakai modul Landing Page (Modul 5) & Chatbot AI.
     */
    public function accLanding(Dudi $dudi): RedirectResponse
    {
        $dudi->update(['tampil_di_landing' => ! $dudi->tampil_di_landing]);

        return back()->with('success', sprintf(
            'DUDI "%s" %s untuk Landing Page.',
            $dudi->nama_dudi,
            $dudi->tampil_di_landing ? 'di-ACC' : 'dinonaktifkan dari'
        ));
    }
}
