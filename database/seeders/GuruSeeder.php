<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Support\Kelas;
use Illuminate\Database\Seeder;

/**
 * Dua guru contoh. Kolom `jurusan` memakai nama jurusan yang sama dengan tabel
 * `jurusan` agar filter guru dan siswa konsisten.
 */
class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $guru = [
            [
                'nip' => '198705122011011001',
                'nama' => 'Siti Aminah, S.Pd.',
                'jurusan' => Kelas::JURUSAN['R'],
                'no_hp' => '081211112222',
            ],
            [
                'nip' => '198204152008011004',
                'nama' => 'Joko Susilo, S.T.',
                'jurusan' => Kelas::JURUSAN['M'],
                'no_hp' => '081233334444',
            ],
        ];

        foreach ($guru as $data) {
            Guru::updateOrCreate(['nip' => $data['nip']], $data);
        }
    }
}
