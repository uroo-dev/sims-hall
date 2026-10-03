<?php

namespace Database\Seeders;

use App\Models\Aula;
use Database\Seeders\Concerns\MenyalinAset;
use Illuminate\Database\Seeder;

/**
 * Dua aula yang bisa dipinjam. Dokumentasi memakai aset lokal di
 * `storage/app/public/aula`.
 */
class AulaSeeder extends Seeder
{
    use MenyalinAset;

    public function run(): void
    {
        $aula = [
            [
                'nama' => 'Aula Sekolah',
                'judul' => 'Aula Sekolah',
                'deskripsi' => 'Aula utama untuk kegiatan sekolah.',
                'dokumentasi' => 'assets/dummy/dummy.jpg',
            ],
            [
                'nama' => 'Ruang Serbaguna',
                'judul' => 'Ruang Serbaguna',
                'deskripsi' => 'Ruang serbaguna untuk kegiatan akademik.',
                'dokumentasi' => 'assets/dummy/dummy.jpg',
            ],
        ];

        foreach ($aula as $data) {
            $data['dokumentasi'] = $this->salinKeStoragePublik($data['dokumentasi'], 'aula/'.$data['nama'].'.jpg');

            Aula::updateOrCreate(['nama' => $data['nama']], $data);
        }
    }
}
