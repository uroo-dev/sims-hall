<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\PaketPeminjaman;
use Illuminate\Database\Seeder;

class PaketPeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan fasilitas sudah ada terlebih dahulu
        $this->call(FasilitasSeeder::class);

        $daftarPaket = [
            [
                'kategori' => 'unggulan',
                'nama_paket' => 'Unggulan',
                'harga' => 6000000,
                'harga_dp' => 2000000,
                'deskripsi' => 'Paket terlengkap aula untuk resepsi, wisuda, atau gathering instansi skala besar hingga 12 jam.',
                'fasilitas' => [
                    'Sound System Medium',
                    'Mic 4',
                    '500 Kursi + Cover',
                    'Proyektor 2',
                    'Wifi 1080mbps',
                    'Podium',
                    'Parkir Luas (Gratis)',
                    'Staff Keamanan',
                    'AC Central Aula',
                    'Panggung Utama & Lighting',
                ],
            ],
            [
                'kategori' => 'terjangkau',
                'nama_paket' => 'Terjangkau',
                'harga' => 1500000,
                'harga_dp' => 500000,
                'deskripsi' => 'Paket hemat untuk seminar singkat, rapat pleno, atau workshop berdurasi 4 jam.',
                'fasilitas' => [
                    'Sound System Standar',
                    'Mic 2',
                    '100 Kursi',
                    'Proyektor 1',
                ],
            ],
            [
                'kategori' => 'standar 1',
                'nama_paket' => 'Standar 1',
                'harga' => 3500000,
                'harga_dp' => 1000000,
                'deskripsi' => 'Pilihan ideal untuk kegiatan seminar umum, pentas seni sekolah, dan pelatihan hingga 12 jam.',
                'fasilitas' => [
                    'Sound System Medium',
                    'Mic 4',
                    '500 Kursi + Cover',
                    'Proyektor 2',
                ],
            ],
            [
                'kategori' => 'standar 2',
                'nama_paket' => 'Standar 2',
                'harga' => 4400000,
                'harga_dp' => 1500000,
                'deskripsi' => 'Fasilitas medium dengan kapasitas kursi penuh dan dukungan audiovisual prima hingga 12 jam.',
                'fasilitas' => [
                    'Sound System Medium',
                    'Mic 4',
                    '500 Kursi + Cover',
                    'Proyektor 2',
                    'Wifi 1080mbps',
                    'Podium',
                ],
            ],
            [
                'kategori' => 'standar 3',
                'nama_paket' => 'Standar 3',
                'harga' => 5500000,
                'harga_dp' => 2000000,
                'deskripsi' => 'Fasilitas premium dengan kenyamanan maksimal untuk berbagai perhelatan formal hingga 12 jam.',
                'fasilitas' => [
                    'Sound System Medium',
                    'Mic 4',
                    '500 Kursi + Cover',
                    'Proyektor 2',
                    'Wifi 1080mbps',
                    'Podium',
                    'Parkir Luas (Gratis)',
                    'Staff Keamanan',
                ],
            ],
        ];

        foreach ($daftarPaket as $item) {
            $paket = PaketPeminjaman::firstOrCreate(
                ['kategori' => $item['kategori']],
                [
                    'nama_paket' => $item['nama_paket'],
                    'harga' => $item['harga'],
                    'harga_dp' => $item['harga_dp'],
                    'deskripsi' => $item['deskripsi'],
                ]
            );

            // Hubungkan dengan relasi fasilitas
            $facilityIds = Facility::whereIn('judul', $item['fasilitas'])->pluck('id')->toArray();
            $paket->facilities()->sync($facilityIds);
        }
    }
}
