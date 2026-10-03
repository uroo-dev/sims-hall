<?php

namespace Database\Seeders;

use App\Models\Dudi;
use App\Models\Lowongan;
use App\Support\Kelas;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

/**
 * Dua lowongan PKL aktif. Deadline dihitung relatif terhadap hari ini agar
 * lowongan tidak langsung kedaluwarsa setiap kali database di-seed ulang.
 */
class LowonganSeeder extends Seeder
{
    public function run(): void
    {
        $dudiNasmoco = Dudi::where('nama_dudi', 'PT Nasmoco Solution')->value('id');
        $dudiMandiri = Dudi::where('nama_dudi', 'CV Karya Mandiri Mesin')->value('id');

        $lowongan = [
            [
                'dudi_id' => $dudiNasmoco,
                'nama_perusahaan' => 'PT Nasmoco Solution',
                'posisi' => 'Internship Web Developer',
                'tipe' => 'Magang',
                'jurusan_sesuai' => Kelas::JURUSAN['R'],
                'deskripsi' => 'Membantu pengembangan aplikasi internal berbasis web.',
                'link_daftar' => 'https://contoh.go.id/pkl/nasmoco-web-dev',
                'deadline' => CarbonImmutable::now()->addDays(30)->toDateString(),
                'is_active' => true,
                'logo' => 'Nasmoco.png',
            ],
            [
                'dudi_id' => $dudiMandiri,
                'nama_perusahaan' => 'CV Karya Mandiri Mesin',
                'posisi' => 'Operator Mesin Bubut',
                'tipe' => 'Magang',
                'jurusan_sesuai' => Kelas::JURUSAN['M'],
                'deskripsi' => 'Operasional mesin bubut dan pemeriksaan dimensi workpiece.',
                'link_daftar' => 'https://contoh.go.id/pkl/karya-mandiri-bubut',
                'deadline' => CarbonImmutable::now()->addDays(21)->toDateString(),
                'is_active' => true,
                'logo' => 'msm.png',
            ],
        ];

        foreach ($lowongan as $data) {
            Lowongan::updateOrCreate(
                [
                    'dudi_id' => $data['dudi_id'],
                    'posisi' => $data['posisi'],
                ],
                $data
            );
        }
    }
}
