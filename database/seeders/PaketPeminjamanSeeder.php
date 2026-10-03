<?php

namespace Database\Seeders;

use App\Models\PaketPeminjaman;
use Illuminate\Database\Seeder;

class PaketPeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $paket = [
            [
                'nama_paket' => 'Paket Reguler',
                'kategori' => 'standar 1',
                'harga' => 50000,
                'harga_dp' => 10000,
                'deskripsi' => 'Paket peminjaman untuk kegiatan sekolah.',
                'durasi' => '4 Jam',
            ],
            [
                'nama_paket' => 'Paket Lengkap',
                'kategori' => 'standar 2',
                'harga' => 100000,
                'harga_dp' => 25000,
                'deskripsi' => 'Paket peminjaman dengan tambahan perlengkapan.',
                'durasi' => '8 Jam',
            ],
        ];

        foreach ($paket as $data) {
            PaketPeminjaman::updateOrCreate(['nama_paket' => $data['nama_paket']], $data);
        }
    }
}
