<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Ppdb_informasi;
use App\Models\Ppdb_jalur;
use App\Models\Ppdb_jurusan;
use App\Models\Ppdb_master;
use App\Models\Ppdb_persyaratan;
use App\Models\Ppdb_tanggal_penting;
use App\Support\Kelas;
use Database\Seeders\Concerns\MenyalinAset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PpdbSeeder extends Seeder
{
    use MenyalinAset;

    public function run(): void
    {
        $dummy = $this->salinKeStoragePublik('assets/dummy/dummy.jpg');

        $this->seedMaster($dummy);
        $this->seedInformasi($dummy);
        $this->seedJurusan($dummy);
        $this->seedJalur();
        $this->seedPersyaratan();
        $this->seedTanggalPenting();
    }

    private function seedMaster(?string $dummy): void
    {
        Ppdb_master::updateOrCreate(
            ['id' => 1],
            [
                'judul' => "PPDB SMKN 2\nKARANGANYAR",
                'deskripsi' => 'Penerimaan peserta didik baru SMKN 2 Karanganyar.',
                'banner_img' => $dummy,
            ]
        );
    }

    private function seedInformasi(?string $dummy): void
    {
        $informasi = [
            [
                'judul' => 'Petunjuk Teknis PPDB',
                'keterangan' => 'Unduh berkas panduan pendaftaran PPDB.',
                'path_file' => $dummy,
                'path_file_hasil' => $dummy,
            ],
            [
                'judul' => 'Pengumuman Hasil Seleksi',
                'keterangan' => 'Pengumuman resmi hasil seleksi PPDB.',
                'path_file' => $dummy,
                'path_file_hasil' => $dummy,
            ],
        ];

        foreach ($informasi as $data) {
            Ppdb_informasi::updateOrCreate(['judul' => $data['judul']], $data);
        }
    }

    private function seedJurusan(?string $dummy): void
    {
        $dayaTampung = [
            Kelas::JURUSAN['R'] => 72,
            Kelas::JURUSAN['T'] => 36,
            Kelas::JURUSAN['O'] => 36,
            Kelas::JURUSAN['M'] => 36,
        ];

        foreach ($dayaTampung as $namaJurusan => $daya) {
            $jurusan = Jurusan::where('nama', $namaJurusan)->first();

            if ($jurusan === null) {
                continue;
            }

            Ppdb_jurusan::updateOrCreate(
                ['jurusan_id' => $jurusan->getKey()],
                [
                    'daya_tampung' => $daya,
                    'img' => $dummy,
                ]
            );
        }
    }

    private function seedJalur(): void
    {
        $jalur = [
            ['nama_jalur' => 'Jalur Zonasi Reguler', 'percentase' => 50.00],
            ['nama_jalur' => 'Jalur Prestasi Akademik', 'percentase' => 20.00],
        ];

        foreach ($jalur as $data) {
            Ppdb_jalur::updateOrCreate(['nama_jalur' => $data['nama_jalur']], $data);
        }
    }

    private function seedPersyaratan(): void
    {
        $persyaratan = [
            ['syarat' => 'Buku rapor SMP/MTs semester 1 sampai 5.'],
            ['syarat' => 'Akta kelahiran dan kartu keluarga.'],
        ];

        foreach ($persyaratan as $data) {
            Ppdb_persyaratan::updateOrCreate(['syarat' => $data['syarat']], $data);
        }
    }

    private function seedTanggalPenting(): void
    {
        $agenda = [
            [
                'nama_agenda' => 'Pendaftaran PPDB',
                'tanggal_mulai' => Carbon::now()->addDays(5)->toDateString(),
                'tanggal_selesai' => Carbon::now()->addDays(18)->toDateString(),
                'keterangan' => 'Pendaftaran peserta didik baru.',
            ],
            [
                'nama_agenda' => 'Pengumuman Hasil Seleksi',
                'tanggal_mulai' => Carbon::now()->addDays(22)->toDateString(),
                'tanggal_selesai' => Carbon::now()->addDays(22)->toDateString(),
                'keterangan' => 'Pengumuman online pukul 10.00 WIB.',
            ],
        ];

        foreach ($agenda as $data) {
            Ppdb_tanggal_penting::updateOrCreate(['nama_agenda' => $data['nama_agenda']], $data);
        }
    }
}
