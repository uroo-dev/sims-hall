<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FasilitasSeeder extends Seeder
{
    public function run(): void
    {
        $fasilitas = [
            [
                'judul' => 'Laboratorium Komputer',
                'deskripsi' => 'Unit komputer untuk praktikum pemrograman.',
            ],
            [
                'judul' => 'Bengkel Ototronik',
                'deskripsi' => 'Bengkel praktikum Teknik Ototronik.',
            ],
        ];

        foreach ($fasilitas as $data) {
            Facility::updateOrCreate(['judul' => $data['judul']], $data);
        }
    }
}
