<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Support\Kelas;
use Illuminate\Database\Seeder;

/**
 * Dua siswa contoh. Nama kelas diambil dari {@see Kelas} sehingga selalu valid,
 * dan kolom `jurusan` memakai nama yang sama dengan tabel `jurusan`.
 */
class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = [
            [
                'nis' => '2026001',
                'nama' => 'Aisyah Nur Rahma',
                'kelas' => Kelas::namaKelas('R', 'A'),
                'no_hp' => '081234567890',
            ],
            [
                'nis' => '2026002',
                'nama' => 'Bimo Prasetyo',
                'kelas' => Kelas::namaKelas('M', 'B'),
                'no_hp' => '081298765432',
            ],
        ];

        foreach ($siswa as $data) {
            $data['jurusan'] = Kelas::jurusanDariKelas($data['kelas']);

            Siswa::updateOrCreate(['nis' => $data['nis']], $data);
        }
    }
}
