<?php

namespace Database\Seeders;

use App\Models\Dudi;
use App\Models\Jurusan;
use App\Support\Kelas;
use Illuminate\Database\Seeder;

/**
 * Dua perusahaan mitra PKL. Hanya Dudi pertama yang tampil di landing page
 * sebagai mitra resmi; Dudi kedua sengaja belum resmi supaya alur persetujuan
 * mitra tetap bisa diuji.
 */
class DudiSeeder extends Seeder
{
    public function run(): void
    {
        $jurusanRpl = Jurusan::where('nama', Kelas::JURUSAN['R'])->value('id');
        $jurusanMesin = Jurusan::where('nama', Kelas::JURUSAN['M'])->value('id');

        $dudi = [
            [
                'nama_dudi' => 'PT Nasmoco Solution',
                'alamat' => 'Jl. Slamet Riyadi No. 108, Surakarta',
                'kota' => 'Surakarta',
                'bidang_usaha' => 'Perangkat Lunak',
                'kontak_person' => 'Rina Puspita',
                'no_hp' => '0271712345',
                'kuota_maksimal' => 4,
                'is_mitra_resmi' => true,
                'tampil_di_landing' => true,
                'logo' => 'Nasmoco.png',
                'program_1' => 'Pengembangan aplikasi web',
                'program_2' => 'Pembuatan basis data',
                'program_3' => 'Dukungan teknis TI',
                'deskripsi' => 'Mitra resmi untuk penempatan PKL bidang perangkat lunak.',
                'jurusan_id' => $jurusanRpl,
            ],
            [
                'nama_dudi' => 'CV Karya Mandiri Mesin',
                'alamat' => 'Jl. Adi Sucipto No. 45, Karanganyar',
                'kota' => 'Karanganyar',
                'bidang_usaha' => 'Manufaktur',
                'kontak_person' => 'Budi Santoso',
                'no_hp' => '0271834567',
                'kuota_maksimal' => 3,
                'is_mitra_resmi' => false,
                'tampil_di_landing' => false,
                'logo' => 'msm.png',
                'program_1' => 'Operator mesin bubut',
                'program_2' => 'Pemesinan CNC',
                'program_3' => 'Kontrol kualitas',
                'deskripsi' => 'Calon mitra PKL bidang manufaktur, menunggu persetujuan.',
                'jurusan_id' => $jurusanMesin,
            ],
        ];

        foreach ($dudi as $data) {
            Dudi::updateOrCreate(['nama_dudi' => $data['nama_dudi']], $data);
        }
    }
}
