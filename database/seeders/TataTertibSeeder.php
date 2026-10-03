<?php

namespace Database\Seeders;

use App\Models\TataTertib;
use Illuminate\Database\Seeder;

/**
 * Dua dokumen tata tertib. Kolom `file_pdf` sengaja dikosongkan karena berkas
 * PDF resmi belum tersedia di repository.
 */
class TataTertibSeeder extends Seeder
{
    public function run(): void
    {
        $tatatertib = [
            [
                'judul' => 'Tata Tertib Siswa SMKN 2 Karanganyar',
                'deskripsi' => 'Norma perilaku, hak, dan kewajiban siswa di sekolah.',
            ],
            [
                'judul' => 'Tata Tertib Prestasi Siswa',
                'deskripsi' => 'Pedoman kegiatan lomba dan penghargaan sekolah.',
            ],
        ];

        foreach ($tatatertib as $data) {
            TataTertib::updateOrCreate(['judul' => $data['judul']], $data);
        }
    }
}
