<?php

namespace Database\Seeders;

use App\Models\Ppdb_informasi;
use App\Models\Ppdb_jalur;
use App\Models\Ppdb_jurusan;
use App\Models\Ppdb_master;
use App\Models\Ppdb_persyaratan;
use App\Models\Ppdb_tanggal_penting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PpdbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dummyImage = 'dummy/dummy.jpg';

        // 1. PPDB Master Header & Banner
        Ppdb_master::updateOrCreate(
            ['id' => 1],
            [
                'judul' => "PPDB SMKN 2\nKARANGANYAR",
                'deskripsi' => 'Bersama SMKN 2 Karanganyar untuk mencetak generasi unggul yang kompeten dan berkarakter siap di dunia industri.',
                'banner_img' => $dummyImage,
            ]
        );

        // 2. PPDB Informasi Panduan & Berkas Hasil
        Ppdb_informasi::updateOrCreate(
            ['id' => 1],
            [
                'judul' => 'Petunjuk Teknis PPDB Resmi SMKN 2 Karanganyar',
                'keterangan' => 'Unduh berkas panduan lengkap, jadwal verifikasi berkas, dan pengumuman hasil seleksi.',
                'path_file' => $dummyImage,
                'path_file_hasil' => $dummyImage,
            ]
        );

        // 3. PPDB Jurusan (4 slot kompetensi keahlian unggulan)
        $jurusanList = [
            [
                'nama_jurusan' => 'Teknik Pemesinan',
                'daya_tampung' => 108,
                'img' => $dummyImage,
            ],
            [
                'nama_jurusan' => 'Teknik Pembuatan Kain',
                'daya_tampung' => 72,
                'img' => $dummyImage,
            ],
            [
                'nama_jurusan' => 'Teknik Ototronik',
                'daya_tampung' => 72,
                'img' => $dummyImage,
            ],
            [
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
                'daya_tampung' => 72,
                'img' => $dummyImage,
            ],
            [
                'nama_jurusan' => 'Desain Komunikasi Visual',
                'daya_tampung' => 36,
                'img' => $dummyImage,
            ],
        ];

        foreach ($jurusanList as $jur) {
            Ppdb_jurusan::updateOrCreate(
                ['nama_jurusan' => $jur['nama_jurusan']],
                $jur
            );
        }

        // 4. PPDB Jalur Seleksi
        $jalurList = [
            ['nama_jalur' => 'Jalur Zonasi Reguler', 'percentase' => 50.00],
            ['nama_jalur' => 'Jalur Prestasi Akademik & Kejuaraan', 'percentase' => 20.00],
            ['nama_jalur' => 'Jalur Afirmasi Keluarga Kurang Mampu', 'percentase' => 15.00],
            ['nama_jalur' => 'Jalur Domisili Terdekat Sekolah', 'percentase' => 10.00],
            ['nama_jalur' => 'Jalur Perpindahan Tugas Orang Tua / Wali', 'percentase' => 5.00],
        ];

        foreach ($jalurList as $jl) {
            Ppdb_jalur::updateOrCreate(
                ['nama_jalur' => $jl['nama_jalur']],
                $jl
            );
        }

        // 5. PPDB Persyaratan Pendaftaran
        $syaratList = [
            ['syarat' => 'Buku Rapor SMP/MTs sederajat semester 1 sampai dengan 5 (asli & fotokopi legalisir).'],
            ['syarat' => 'Ijazah SMP atau Surat Keterangan Lulus (SKL) resmi yang mencantumkan nilai asesmen akhir.'],
            ['syarat' => 'Akta Kelahiran dan Kartu Keluarga (KK) yang diterbitkan paling singkat 1 tahun sebelum pendaftaran.'],
            ['syarat' => 'Surat Pernyataan Sehat tidak buta warna dari fasilitas pelayanan kesehatan pemerintah.'],
            ['syarat' => 'Piagam sertifikat kejuaraan/prestasi tingkat kabupaten, provinsi, atau nasional (jika memiliki).'],
        ];

        foreach ($syaratList as $sy) {
            Ppdb_persyaratan::updateOrCreate(
                ['syarat' => $sy['syarat']],
                $sy
            );
        }

        // 6. PPDB Tanggal Penting / Jadwal Agenda
        $tanggalList = [
            [
                'nama_agenda' => 'Pembuatan Akun & Aktivasi Berkas Daring',
                'tanggal_mulai' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->addDays(12)->format('Y-m-d'),
                'keterangan' => 'Calon siswa mengunggah berkas scan rapor, KK, dan piagam di portal resmi.',
            ],
            [
                'nama_agenda' => 'Pendaftaran & Pemilihan Kompetensi Keahlian',
                'tanggal_mulai' => Carbon::now()->addDays(13)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->addDays(18)->format('Y-m-d'),
                'keterangan' => 'Memilih jurusan utama dan alternatif sesuai minat dan bakat kejuruan.',
            ],
            [
                'nama_agenda' => 'Validasi Berkas & Masa Tenang Panitia',
                'tanggal_mulai' => Carbon::now()->addDays(19)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->addDays(21)->format('Y-m-d'),
                'keterangan' => 'Panitia melakukan sinkronisasi data kuota jalur zonasi dan prestasi.',
            ],
            [
                'nama_agenda' => 'Pengumuman Resmi Hasil Seleksi PPDB',
                'tanggal_mulai' => Carbon::now()->addDays(22)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->addDays(22)->format('Y-m-d'),
                'keterangan' => 'Diumumkan secara online serentak pada pukul 10.00 WIB.',
            ],
            [
                'nama_agenda' => 'Daftar Ulang Peserta Didik Baru Diterima',
                'tanggal_mulai' => Carbon::now()->addDays(23)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->addDays(27)->format('Y-m-d'),
                'keterangan' => 'Penyerahan fisik berkas asli dan pengukuran seragam praktek kejuruan.',
            ],
        ];

        foreach ($tanggalList as $tg) {
            Ppdb_tanggal_penting::updateOrCreate(
                ['nama_agenda' => $tg['nama_agenda']],
                $tg
            );
        }
    }
}
