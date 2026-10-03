<?php

namespace Database\Seeders;

use App\Models\Dudi;
use App\Models\Guru;
use App\Models\PenempatanPkl;
use App\Models\Siswa;
use App\Models\SuratPengajuan;
use Illuminate\Database\Seeder;

/**
 * Satu penempatan PKL berstatus FIX sebagai contoh riwayat yang sudah disetujui.
 */
class PenempatanPklSeeder extends Seeder
{
    public function run(): void
    {
        $surat = SuratPengajuan::orderBy('tanggal_surat')->first();
        $siswa = Siswa::orderBy('nis')->first();
        $guru = Guru::orderBy('nip')->first();
        $dudi = Dudi::where('nama_dudi', 'PT Nasmoco Solution')->first();

        if ($surat === null || $siswa === null || $guru === null || $dudi === null) {
            return;
        }

        PenempatanPkl::firstOrCreate(
            [
                'surat_pengajuan_id' => $surat->id,
                'siswa_id' => $siswa->id,
            ],
            [
                'dudi_id' => $dudi->id,
                'guru_id' => $guru->id,
                'status_penempatan' => PenempatanPkl::STATUS_FIX,
                'catatan' => 'Penempatan contoh dari seeder.',
            ]
        );
    }
}
