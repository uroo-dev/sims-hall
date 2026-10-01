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
     * Daftar seluruh penempatan PKL dengan filter.
     */
    public function index(Request $request): View
    {
        $filterStatus = $request->string('status')->trim()->toString();
        $filterDudi = $request->string('dudi_id')->trim()->toString();
        $search = $request->string('q')->trim()->toString();

        $query = PenempatanPkl::query()->withRelasi();

        if ($filterStatus !== '') {
            $query->status($filterStatus);
        }

        if ($filterDudi !== '') {
            $query->where('dudi_id', $filterDudi);
        }

        // Dikelompokkan agar tidak meng-OR dengan filter status/dudi di atas.
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('siswa', fn ($s) => $s->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('dudi', fn ($d) => $d->where('nama_dudi', 'like', "%{$search}%"));
            });
        }

        return view('Admin.pkl.index', [
            'penempatans' => $query->orderByDesc('created_at')->get(),
            'dudis' => Dudi::orderBy('nama_dudi')->get(),
            'filterStatus' => $filterStatus,
            'filterDudi' => $filterDudi,
            'search' => $search,
            'pageTitle' => 'Data PKL',
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
     * Detail satu surat pengajuan + daftar siswa di dalamnya.
     */
    public function showSurat(SuratPengajuan $surat): View
    {
        $surat->load(['dudi', 'penempatanPkls.siswa', 'penempatanPkls.guru']);

        return view('Admin.pkl.surat', [
            'surat' => $surat,
            'pageTitle' => 'Surat '.$surat->nomor_surat,
        ]);
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
