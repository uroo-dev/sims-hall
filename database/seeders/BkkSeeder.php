<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Titik masuk untuk seluruh data modul Bursa Kerja Khusus: guru, siswa, DUDI,
 * lowongan, surat pengajuan, dan penempatan PKL.
 */
class BkkSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            GuruSeeder::class,
            SiswaSeeder::class,
            DudiSeeder::class,
            LowonganSeeder::class,
            SuratPengajuanSeeder::class,
            PenempatanPklSeeder::class,
        ]);
    }
}
