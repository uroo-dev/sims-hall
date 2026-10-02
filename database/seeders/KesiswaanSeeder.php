<?php

namespace Database\Seeders;

use App\Models\Ekstrakurikuler;
use App\Models\Kesiswaan;
use App\Models\Prestasi;
use App\Models\TataTertib;
use Illuminate\Database\Seeder;

class KesiswaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Kesiswaan Singleton
        Kesiswaan::firstOrCreate(
            ['kesiswaanID' => 1],
            [
                'judul' => 'Kesiswaan SMKN 2 Karanganyar',
                'deskripsi' => 'Membangun karakter unggul melalui integrasi nilai moral dan penguasaan teknologi. Kami berdedikasi untuk membina potensi setiap siswa dalam lingkungan yang inklusif, inovatif, dan disiplin.',
            ]
        );

        // 2. Tata Tertib
        $tataTertibList = [
            [
                'judul' => 'Aturan Seragam',
                'deskripsi' => 'Siswa wajib mengenakan seragam lengkap sesuai jadwal yang ditentukan, termasuk atribut sekolah, sepatu hitam, dan rambut yang rapi. Pelanggaran terhadap aturan seragam akan dikenakan sanksi sesuai tata tertib sekolah.',
            ],
            [
                'judul' => 'Kehadiran & Jam Belajar',
                'deskripsi' => 'Siswa diharapkan hadir 15 menit sebelum bel masuk berbunyi. Keterlambatan dan absensi tanpa keterangan akan dicatat dan mempengaruhi penilaian sikap serta kedisiplinan.',
            ],
            [
                'judul' => 'Penggunaan Gadget',
                'deskripsi' => 'Penggunaan gadget diperbolehkan hanya untuk keperluan pembelajaran di dalam kelas dengan izin guru pengampu. Dilarang menggunakan gadget untuk bermain game atau media sosial selama jam pelajaran berlangsung.',
            ],
            [
                'judul' => 'Buku Saku Siswa',
                'deskripsi' => 'Panduan lengkap dan komprehensif norma perilaku, hak, kewajiban, dan tata tertib siswa SMKN 2 Karanganyar.',
            ],
        ];

        foreach ($tataTertibList as $tt) {
            TataTertib::firstOrCreate(['judul' => $tt['judul']], $tt);
        }

        // 3. Ekstrakurikuler
        $eskulList = [
            [
                'nama' => 'Organisasi OSIS',
                'sekolah' => 'SMKN 2 Karanganyar',
                'deskripsi' => 'Organisasi Siswa Intra Sekolah SMK N 2 KARANGANYAR adalah Organisasi satu - satunya yang ada disekolah yang berada dibawah Waka Kesiswaan.',
                'logo' => 'assets/organisasi/osis.png',
            ],
            [
                'nama' => 'Organisasi PMR',
                'sekolah' => 'SMKN 2 Karanganyar',
                'deskripsi' => 'PMR Wira SMK Negeri 2 Karanganyar merupakan salah satu organisasi di lingkungan sekolah.',
                'logo' => 'assets/organisasi/pmr.png',
            ],
            [
                'nama' => 'Organisasi AMBALAN',
                'sekolah' => 'SMKN 2 Karanganyar',
                'deskripsi' => 'Fasilitas sekolah dengan kapasitas luas untuk berbagai kebutuhan.',
                'logo' => 'assets/organisasi/ambalan.png',
            ],
            [
                'nama' => 'Organisasi PBB',
                'sekolah' => 'SMKN 2 Karanganyar',
                'deskripsi' => 'Fasilitas sekolah dengan kapasitas luas untuk berbagai kebutuhan acara institusi, perusahaan, dan masyarakat umum.',
                'logo' => 'assets/organisasi/pbb.png',
            ],
            [
                'nama' => 'Organisasi ROHIS',
                'sekolah' => 'SMKN 2 Karanganyar',
                'deskripsi' => 'Rohani Islam (disingkat Rohis) adalah sebuah organisasi memperdalam dan memperkuat ajaran Islam.',
                'logo' => 'assets/organisasi/rohis.png',
            ],
            [
                'nama' => 'Organisasi JURNALISTIK',
                'sekolah' => 'SMKN 2 Karanganyar',
                'deskripsi' => 'Jurnalistik secara bahasa adalah kewartawanan atau kepenulisan.',
                'logo' => 'assets/organisasi/jurnalistik.png',
            ],
        ];

        foreach ($eskulList as $e) {
            Ekstrakurikuler::firstOrCreate(['nama' => $e['nama']], $e);
        }

        // 4. Prestasi
        $prestasiList = [
            [
                'judul' => 'Juara 1 LKBB-PB Nasional 2026',
                'kategori' => 'non-akademik',
                'deskripsi' => 'SMK Negeri 2 Karanganyar sukses meraih Juara 1 Lomba Kreasi Baris Berbaris dan Pengibaran Bendera (LKBB-PB) 2026.',
                'dokumentasi' => 'assets/prestasi/prestasi_1.png',
            ],
            [
                'judul' => 'Lolos SNBT Tahun 2026',
                'kategori' => 'akademik',
                'deskripsi' => 'Selamat dan Sukses bagi peserta didik SMKN 2 KARANGANYAR yang telah Lolos SNBT (Seleksi Nasional Berdasarkan Tes) Tahun 2026.',
                'dokumentasi' => 'assets/prestasi/prestasi_2.png',
            ],
            [
                'judul' => 'Juara 2 Ganda Putra Badminton Nasional Pasanova 2026',
                'kategori' => 'non-akademik',
                'deskripsi' => 'Selamat dan Sukses untuk Haidar dan Sigit atas di raih-nya Juara 2 Ganda Putra Turnamen Badminton Tingkat Nasional Pasanova 2026.',
                'dokumentasi' => 'assets/prestasi/prestasi_3.png',
            ],
            [
                'judul' => 'Tim Patriakara Juara LKBB PPI Cup II 2026',
                'kategori' => 'non-akademik',
                'deskripsi' => 'Selamat dan Sukses kepada Tim Patriakara SMK N 2 Karanganyar yang telah berhasil memborong sederet piala dalam ajang LKBB PPI Cup II 2026 di GOR RMI...',
                'dokumentasi' => 'assets/prestasi/prestasi_4.png',
            ],
            [
                'judul' => 'Prestasi Gemilang Siswa SMKN 2 Karanganyar',
                'kategori' => 'non-akademik',
                'deskripsi' => 'Selamat & Sukses atas pencapaian gemilang siswa SMKN 2 Karanganyar pada ajang perlombaan tingkat regional dan nasional.',
                'dokumentasi' => 'assets/prestasi/prestasi_5.png',
            ],
            [
                'judul' => 'Juara 1 Lomba Kompetensi Siswa Provinsi 2026',
                'kategori' => 'akademik',
                'deskripsi' => 'Selamat dan Sukses atas prestasi Juara 1 dalam kompetisi keahlian siswa tingkat provinsi tahun 2026.',
                'dokumentasi' => 'assets/prestasi/prestasi_6.png',
            ],
            [
                'judul' => 'Juara 2 Kompetisi Kejuruan Siswa 2026',
                'kategori' => 'akademik',
                'deskripsi' => 'Selamat dan Sukses atas raihan Juara 2 dalam ajang perlombaan kompetensi kejuruan tahun 2026.',
                'dokumentasi' => 'assets/prestasi/prestasi_7.png',
            ],
            [
                'judul' => 'Juara 3 Kejuaraan Tingkat Provinsi 2026',
                'kategori' => 'akademik',
                'deskripsi' => 'Selamat dan Sukses atas perolehan Juara 3 dalam kejuaraan tingkat provinsi tahun 2026.',
                'dokumentasi' => 'assets/prestasi/prestasi_8.png',
            ],
        ];

        foreach ($prestasiList as $p) {
            Prestasi::firstOrCreate(
                ['deskripsi' => $p['deskripsi']],
                $p
            );
        }
    }
}
