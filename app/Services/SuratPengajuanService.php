<?php

namespace App\Services;

use App\Models\Dudi;
use App\Models\Guru;
use App\Models\PenempatanPkl;
use App\Models\SuratPengajuan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

/**
 * Pembuat Surat Pengajuan PKL otomatis.
 *
 * Alur:
 * 1. ensureDudi()  - resolve DUDI existing atau buat baru (tampil_di_landing = false)
 * 2. buatSurat()   - generate nomor surat + record penempatan dalam 1 transaksi
 * 3. renderPdf()   - render PDF dan simpan ke storage
 */
class SuratPengajuanService
{
    /**
     * Format tanggal Indonesia pada surat resmi.
     */
    private const BULAN = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];

    /**
     * Resolve DUDI: pakai yang dipilih, atau buat baru dari input formulir.
     * DUDI baru SELALU tampil_di_landing = false sampai di-ACC BKK.
     *
     * Data DUDI baru dibaca dari array `dudi_baru` (lihat
     * StorePengajuanPklRequest). Input `dudi_baru` sudah tervalidasi, jadi
     * aman diasumsikan berupa array saat `dudi_id` kosong.
     *
     * @param  array<string, mixed>  $data
     */
    public function ensureDudi(array $data): Dudi
    {
        if (! empty($data['dudi_id'])) {
            return Dudi::findOrFail($data['dudi_id']);
        }

        $baru = $data['dudi_baru'] ?? [];

        return Dudi::create([
            'nama_dudi' => $baru['nama'],
            'alamat' => $baru['alamat'],
            'kota' => $baru['kota'],
            'bidang_usaha' => $baru['bidang_usaha'],
            'kontak_person' => $baru['kontak_person'] ?? null,
            'no_hp' => $baru['no_hp'] ?? null,
            'kuota_maksimal' => (int) ($baru['kuota_maksimal'] ?? 0),
            'is_mitra_resmi' => false,
            'tampil_di_landing' => false,
        ]);
    }

    /**
     * Buat surat pengajuan + record penempatan untuk semua siswa terpilih.
     *
     * Semua dijalankan dalam satu transaksi agar nomor surat tidak dipakai
     * ganda bila ada siswa yang gagal disimpan.
     *
     * @param  array<int, int>  $siswaIds
     */
    public function buatSurat(Dudi $dudi, array $siswaIds, Guru $guru, array $tanggal): SuratPengajuan
    {
        return DB::transaction(function () use ($dudi, $siswaIds, $guru, $tanggal) {
            $tanggalSurat = Carbon::parse($tanggal['tanggal_surat']);

            $surat = SuratPengajuan::create([
                'nomor_surat' => SuratPengajuan::generateNomorSurat($tanggalSurat->year),
                'dudi_id' => $dudi->id,
                'tanggal_surat' => $tanggalSurat->toDateString(),
                'tgl_mulai_pkl' => Carbon::parse($tanggal['tgl_mulai_pkl'])->toDateString(),
                'tgl_selesai_pkl' => Carbon::parse($tanggal['tgl_selesai_pkl'])->toDateString(),
            ]);

            foreach ($siswaIds as $siswaId) {
                PenempatanPkl::create([
                    'surat_pengajuan_id' => $surat->id,
                    'siswa_id' => $siswaId,
                    'dudi_id' => $dudi->id,
                    'guru_id' => $guru->id,
                    'status_penempatan' => PenempatanPkl::STATUS_PENGAJUAN,
                ]);
            }

            return $surat->load(['dudi', 'penempatanPkls.siswa', 'penempatanPkls.guru']);
        });
    }

    /**
     * Render PDF surat dan simpan ke disk publik.
     * Mengembalikan path relatif file.
     */
    public function renderPdf(SuratPengajuan $surat): string
    {
        $surat->loadMissing(['dudi', 'penempatanPkls.siswa', 'penempatanPkls.guru']);

        $pdf = Pdf::loadView('pkl.surat-pengajuan', [
            'surat' => $surat,
            'siswas' => $surat->penempatanPkls->pluck('siswa')->filter()->values(),
            'guru' => $surat->penempatanPkls->pluck('guru')->filter()->unique('id')->first(),
            'tanggalIndonesia' => fn ($t) => $this->tanggalIndonesia($t),
            'penandaTangan' => $this->penandaTangan(),
            'jabatan' => 'Manager BKK dan PKL',
            'namaSekolah' => 'SMK Negeri 2 Karanganyar Dietrich Scholtze',
            'namaKota' => 'Karanganyar',
        ])->setPaper('a4', 'portrait');

        $filename = $this->namaFile($surat);
        $directory = 'surat-pkl';

        Storage::disk('public')->makeDirectory($directory);
        $pdf->save(Storage::disk('public')->path($directory.'/'.$filename));

        $path = $directory.'/'.$filename;
        $surat->forceFill(['file_pdf_path' => $path])->save();

        return $path;
    }

    /**
     * Identitas penanda tangan surat: user yang sedang login, beserta NIP
     * bila akunnya tertaut ke data guru.
     *
     * @return array{name: string, nip: string|null}
     */
    private function penandaTangan(): array
    {
        $user = auth()->user();

        if (! $user) {
            return ['name' => '-', 'nip' => null];
        }

        return [
            'name' => $user->name,
            'nip' => Guru::where('user_id', $user->id)->value('nip'),
        ];
    }

    /**
     * Buat surat + langsung generate PDF. Bila PDF gagal, record tetap
     * disimpan dan path dikosongkan agar bisa di-retry dari UI.
     */
    public function buatSuratDenganPdf(Dudi $dudi, array $siswaIds, Guru $guru, array $tanggal): array
    {
        $surat = $this->buatSurat($dudi, $siswaIds, $guru, $tanggal);

        try {
            $this->renderPdf($surat);
        } catch (Throwable $e) {
            Log::error('Gagal generate PDF surat pengajuan PKL', [
                'surat_id' => $surat->id,
                'error' => $e->getMessage(),
            ]);
        }

        return [$surat->fresh(), $surat->file_pdf_path !== null];
    }

    /**
     * Ambil path absolut file PDF di disk.
     */
    public function absolutePath(SuratPengajuan $surat): string
    {
        if (blank($surat->file_pdf_path)) {
            throw new RuntimeException('Surat ini belum memiliki file PDF. Silakan generate ulang.');
        }

        $absolute = Storage::disk('public')->path($surat->file_pdf_path);

        if (! is_file($absolute)) {
            throw new RuntimeException('File PDF surat tidak ditemukan di storage.');
        }

        return $absolute;
    }

    /**
     * Nama file PDF: surat-pkl-{tahun}-{index}.pdf
     *
     * Nomor surat berformat 421/BKK/{tahun}/{index}, jadi index diambil dari
     * segment terakhir.
     */
    private function namaFile(SuratPengajuan $surat): string
    {
        $segments = explode('/', $surat->nomor_surat);
        $index = end($segments) ?: (string) $surat->id;

        return sprintf('surat-pkl-%d-%s.pdf', $surat->tanggal_surat->year, $index);
    }

    /**
     * Format tanggal Indonesia: "12 September 2026".
     */
    public function tanggalIndonesia(Carbon|string $tanggal): string
    {
        $date = $tanggal instanceof Carbon ? $tanggal : Carbon::parse($tanggal);

        return sprintf('%d %s %d', $date->day, self::BULAN[$date->month], $date->year);
    }
}
