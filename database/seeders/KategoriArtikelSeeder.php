<?php

namespace Database\Seeders;

use App\Models\KategoriArtikel;
use Illuminate\Database\Seeder;

class KategoriArtikelSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            [
                'nama' => 'Prestasi',
                'slug' => 'prestasi',
                'deskripsi' => 'Kabar prestasi siswa SMKN 2 Karanganyar.',
            ],
            [
                'nama' => 'Pengumuman',
                'slug' => 'pengumuman',
                'deskripsi' => 'Pengumuman resmi sekolah.',
            ],
        ];

        foreach ($kategori as $data) {
            KategoriArtikel::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
