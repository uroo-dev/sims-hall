<?php

namespace Database\Seeders;

use App\Models\Dudi;
use App\Models\SuratPengajuan;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

/**
 * Satu surat pengajuan PKL contoh. Surat dicari berdasarkan DUDI dan tanggal
 * surat sebelum dibuat, sehingga nomor surat tidak berubah saat seeder
 * dijalankan berulang kali.
 */
class SuratPengajuanSeeder extends Seeder
{
    public function run(): void
    {
        $dudi = Dudi::where('nama_dudi', 'PT Nasmoco Solution')->first();

        if ($dudi === null) {
            return;
        }

        // Tanggal tetap agar idempotent di test
        $tanggalSurat = '2026-01-01';

        SuratPengajuan::firstOrCreate(
            [
                'dudi_id' => $dudi->id,
                'tanggal_surat' => $tanggalSurat,
            ],
            [
                'nomor_surat' => SuratPengajuan::generateNomorSurat(2026),
                'tgl_mulai_pkl' => '2026-02-01',
                'tgl_selesai_pkl' => '2026-05-01',
            ]
        );
    }
}
