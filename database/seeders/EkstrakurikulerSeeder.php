<?php

namespace Database\Seeders;

use App\Models\Ekstrakurikuler;
use Illuminate\Database\Seeder;

/**
 * Dua ekstrakurikuler contoh dengan logo dari `public/assets/organisasi`.
 */
class EkstrakurikulerSeeder extends Seeder
{
    public function run(): void
    {
        $ekskul = [
            [
                'nama' => 'OSIS',
                'sekolah' => 'SMK Negeri 2 Karanganyar',
                'deskripsi' => 'Organisasi Siswa Intra Sekolah yang memandu kegiatan kesiswaan.',
                'logo' => 'assets/organisasi/osis.png',
            ],
            [
                'nama' => 'PMR',
                'sekolah' => 'SMK Negeri 2 Karanganyar',
                'deskripsi' => 'Palang Merah Remaja yang melatih pertolongan pertama.',
                'logo' => 'assets/organisasi/pmr.png',
            ],
        ];

        foreach ($ekskul as $data) {
            Ekstrakurikuler::updateOrCreate(['nama' => $data['nama']], $data);
        }
    }
}
