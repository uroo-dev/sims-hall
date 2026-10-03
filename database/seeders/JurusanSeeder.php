<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Support\Kelas;
use Illuminate\Database\Seeder;

/**
 * Empat jurusan aktif SMK Negeri 2 Karanganyar. Kode huruf yang dipakai pada
 * nama kelas (lihat {@see Kelas}) juga dipakai sebagai `kode` di
 * sini supaya keduanya selalu sejajar.
 */
class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        $jurusan = [
            [
                'nama' => 'Rekayasa Perangkat Lunak',
                'deskripsi' => 'Mempelajari pengembangan aplikasi, basis data, dan jaringan komputer.',
                'logo' => 'logo_rpl.png',
            ],
            [
                'nama' => 'Teknik Pembuatan Kain',
                'deskripsi' => 'Mempelajari proses produksi kain, rajut, dan desain tekstil.',
                'logo' => 'logo_tekstil.png',
            ],
            [
                'nama' => 'Teknik Ototronik',
                'deskripsi' => 'Mempelajari sistem elektronik, kendali mesin, dan troubleshooting kendaraan.',
                'logo' => 'logo_oto.png',
            ],
            [
                'nama' => 'Teknik Pemesinan',
                'deskripsi' => 'Mempelajari mesin bubut, frais, dan manufaktur presisi.',
                'logo' => 'logo_mesin.png',
            ],
        ];

        foreach ($jurusan as $data) {
            Jurusan::updateOrCreate(['nama' => $data['nama']], $data);
        }
    }
}
