<?php

namespace Database\Seeders;

use App\Models\Prestasi;
use Illuminate\Database\Seeder;

/**
 * Dua prestasi siswa contoh. Dokumentasi memakai aset lokal di
 * `public/assets/prestasi` supaya tidak bergantung pada file eksternal.
 */
class PrestasiSeeder extends Seeder
{
    public function run(): void
    {
        $prestasi = [
            [
                'judul' => 'Juara LKS Pemrograman Javascript tingkat Nasional',
                'kategori' => 'akademik',
                'deskripsi' => 'Siswa jurusan Rekayasa Perangkat Lunak meraih medali emas LKS.',
                'dokumentasi' => 'assets/prestasi/prestasi_1.png',
            ],
            [
                'judul' => 'Juara Lomba Profile Ototronik tingkat Provinsi',
                'kategori' => 'non-akademik',
                'deskripsi' => 'Siswa jurusan Teknik Ototronik juara pada lomba mekanik tingkat provinsi.',
                'dokumentasi' => 'assets/prestasi/prestasi_2.png',
            ],
        ];

        foreach ($prestasi as $data) {
            Prestasi::updateOrCreate(['judul' => $data['judul']], $data);
        }
    }
}
