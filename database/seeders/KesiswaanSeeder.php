<?php

namespace Database\Seeders;

use App\Models\Kesiswaan;
use Database\Seeders\Concerns\MenyalinAset;
use Illuminate\Database\Seeder;

class KesiswaanSeeder extends Seeder
{
    use MenyalinAset;

    public function run(): void
    {
        $dummy = $this->salinKeStoragePublik('assets/dummy/dummy.jpg');

        $data = [
            [
                'judul' => 'Profil Kesiswaan',
                'deskripsi' => 'Deskripsi program kesiswaan SMKN 2 Karanganyar.',
                'dokumentasi' => $dummy,
            ],
            [
                'judul' => 'Ekstrakurikuler',
                'deskripsi' => 'Daftar ekstrakurikuler yang tersedia bagi siswa.',
                'dokumentasi' => $dummy,
            ],
        ];

        foreach ($data as $d) {
            Kesiswaan::updateOrCreate(['judul' => $d['judul']], $d);
        }
    }
}
