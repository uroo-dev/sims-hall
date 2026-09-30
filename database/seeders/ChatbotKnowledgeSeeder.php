<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\BludProduct;
use App\Models\Faq;
use App\Models\IndustryPartner;
use App\Models\JobVacancy;
use App\Models\Major;
use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;

/**
 * Seeder knowledge base untuk chatbot "Nanya AI".
 *
 * PENTING: Seluruh data di bawah ini adalah DATA CONTOH yang realistis
 * namun fiktif (bukan data pribadi asli). Semua data dapat diubah/diganti
 * oleh admin melalui database atau admin panel. Seeder ini idempoten
 * (updateCreate by kunci unik), aman dijalankan berulang.
 */
class ChatbotKnowledgeSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSchoolSettings();
        $this->seedFaqs();
        $this->seedMajors();
        $this->seedAchievements();
        $this->seedIndustryPartners();
        $this->seedJobVacancies();
        $this->seedBludProducts();
    }

    /**
     * Profil & kontak sekolah (selalu dikirim sebagai konteks dasar chatbot).
     */
    private function seedSchoolSettings(): void
    {
        // SESUAIKAN DI SINI: ganti dengan data resmi sekolah Anda.
        $settings = [
            'nama_sekolah' => 'SMK Negeri 2 Karanganyar',
            'alamat' => 'Jl. Yos Sudarso, Jengglong, Bejen, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57716',
            'telepon' => '(0271) 495036',
            'email' => 'admin@smkn2kra.sch.id',
            'website' => '/',
            'jam_operasional' => 'Senin s.d. Jumat pukul 07.00 - 15.30 WIB',
            'link_ppdb' => '/ppdb',
            'link_profil' => '/profil',
            'link_produk_unggulan' => '/produk-unggulan',
            'link_pkl_bkk' => '/pkl-bkk',
            'link_kesiswaan' => '/kesiswaan',
            'periode_ppdb' => 'PPDB Tahun Ajaran 2026/2027 (data contoh - dapat diubah melalui database/admin panel)',
        ];

        foreach ($settings as $kunci => $nilai) {
            SchoolSetting::updateOrCreate(['kunci' => $kunci], ['nilai' => $nilai]);
        }
    }

    private function seedFaqs(): void
    {
        $faqs = [
            [
                'kategori' => 'PPDB',
                'pertanyaan' => 'Bagaimana cara mendaftar PPDB?',
                'jawaban' => 'Pendaftaran PPDB dapat dilakukan melalui halaman PPDB resmi sekolah pada menu PPDB di website ini. Pastikan menyiapkan dokumen persyaratan sesuai ketentuan yang tertera pada halaman PPDB.',
            ],
            [
                'kategori' => 'PPDB',
                'pertanyaan' => 'Kapan pendaftaran PPDB dibuka?',
                'jawaban' => 'Jadwal pembukaan PPDB mengikuti ketentuan resmi dari Dinas Pendidikan Provinsi Jawa Tengah. Informasi terbaru dapat dilihat pada halaman PPDB di website sekolah.',
            ],
            [
                'kategori' => 'Jurusan',
                'pertanyaan' => 'Jurusan apa saja yang tersedia di sekolah?',
                'jawaban' => 'SMK Negeri 2 Karanganyar memiliki 4 kompetensi keahlian: Rekayasa Perangkat Lunak (RPL), Teknik Pembuatan Kain, Teknik Ototronik, dan Teknik Permesinan.',
            ],
            [
                'kategori' => 'PKL',
                'pertanyaan' => 'Apa itu PKL dan kapan pelaksanaannya?',
                'jawaban' => 'PKL (Praktik Kerja Lapangan) adalah program pembelajaran langsung di dunia usaha/dunia industri bagi siswa. Penempatan PKL dikelola melalui modul PKL & BKK sekolah dan dilaksanakan sesuai kalender akademik.',
            ],
            [
                'kategori' => 'BKK',
                'pertanyaan' => 'Apa itu BKK dan bagaimana cara melamar lowongan kerja untuk alumni?',
                'jawaban' => 'BKK (Bursa Kerja Khusus) / Career Center adalah unit sekolah yang menyalurkan lulusan ke dunia kerja. Alumni dapat melihat daftar lowongan kerja melalui halaman PKL & BKK di website sekolah dan melamar melalui link pendaftaran yang tertera pada setiap lowongan.',
            ],
            [
                'kategori' => 'Produk Unggulan',
                'pertanyaan' => 'Apa saja produk unggulan sekolah (BLUD)?',
                'jawaban' => 'Sekolah mengembangkan produk unggulan berbasis BLUD, antara lain produk tekstil (kain batik, tenun, dan ecoprint) hasil karya siswa. Detail produk dan harga dapat dilihat pada halaman Produk Unggulan.',
            ],
            [
                'kategori' => 'Kontak',
                'pertanyaan' => 'Bagaimana cara menghubungi sekolah?',
                'jawaban' => 'Sekolah dapat dihubungi melalui telepon (0271) 495036, email admin@smkn2kra.sch.id, atau datang langsung ke Jl. Yos Sudarso, Jengglong, Bejen, Kec. Karanganyar pada jam operasional Senin s.d. Jumat pukul 07.00 - 15.30 WIB.',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['pertanyaan' => $faq['pertanyaan']], $faq);
        }
    }

    private function seedMajors(): void
    {
        // SESUAIKAN DI SINI: daftar kompetensi keahlian sesuai sekolah Anda.
        $majors = [
            [
                'nama' => 'Rekayasa Perangkat Lunak (RPL)',
                'deskripsi' => 'Jurusan yang mempelajari pengembangan aplikasi dan sistem perangkat lunak, meliputi pemrograman web, mobile, dan basis data.',
                'prospek_karier' => 'Web Developer, Mobile Developer, QA Engineer, Software Engineer.',
            ],
            [
                'nama' => 'Teknik Pembuatan Kain',
                'deskripsi' => 'Jurusan yang mempelajari proses produksi tekstil, mulai dari penenunan, pencelupan, batik, hingga kain ecoprint.',
                'prospek_karier' => 'Operator industri tekstil, pengrajin batik, wirausaha produk tekstil, quality control produksi kain.',
            ],
            [
                'nama' => 'Teknik Ototronik',
                'deskripsi' => 'Jurusan yang mempelajari sistem kelistrikan dan elektronika kendaraan modern, termasuk engine management system.',
                'prospek_karier' => 'Teknisi ototronik di bengkel resmi, mekanik kendaraan, quality control industri otomotif.',
            ],
            [
                'nama' => 'Teknik Permesinan',
                'deskripsi' => 'Jurusan yang mempelajari pengoperasian mesin produksi seperti bubut, frais, CNC, serta pengelasan dan gambar teknik.',
                'prospek_karier' => 'Operator mesin CNC, teknisi permesinan industri, drafter, wirausaha jasa permesinan.',
            ],
        ];

        foreach ($majors as $major) {
            Major::updateOrCreate(['nama' => $major['nama']], $major);
        }
    }

    private function seedAchievements(): void
    {
        // Data contoh - bukan data pribadi asli. Ganti melalui database/admin panel.
        $achievements = [
            [
                'judul' => 'Juara 1 Lomba Kompetensi Siswa (LKS) Web Technologies',
                'tingkat' => 'Kabupaten',
                'tahun' => 2026,
                'nama_siswa' => 'Siswa Contoh RPL',
                'deskripsi' => 'Data contoh prestasi - dapat diubah melalui database/admin panel.',
            ],
            [
                'judul' => 'Juara 2 Olimpiade Teknik Permesinan',
                'tingkat' => 'Provinsi',
                'tahun' => 2025,
                'nama_siswa' => 'Siswa Contoh Permesinan',
                'deskripsi' => 'Data contoh prestasi - dapat diubah melalui database/admin panel.',
            ],
            [
                'judul' => 'Juara 1 Festival Kriya Tekstil Pelajar',
                'tingkat' => 'Kabupaten',
                'tahun' => 2025,
                'nama_siswa' => 'Siswa Contoh Tekstil',
                'deskripsi' => 'Data contoh prestasi - dapat diubah melalui database/admin panel.',
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(['judul' => $achievement['judul']], $achievement);
        }
    }

    private function seedIndustryPartners(): void
    {
        // Data contoh - ganti dengan mitra industri resmi sekolah.
        $partners = [
            [
                'nama_perusahaan' => 'PT Contoh Teknologi Nusantara',
                'bidang' => 'Teknologi Informasi',
                'jenis_kerja_sama' => 'Tempat PKL siswa RPL, guru tamu, dan penyaluran lulusan.',
                'lokasi' => 'Surakarta',
            ],
            [
                'nama_perusahaan' => 'PT Contoh Tekstil Mandiri',
                'bidang' => 'Tekstil',
                'jenis_kerja_sama' => 'Tempat PKL siswa Teknik Pembuatan Kain dan serapan lulusan.',
                'lokasi' => 'Karanganyar',
            ],
            [
                'nama_perusahaan' => 'PT Contoh Otomotif Jaya',
                'bidang' => 'Otomotif',
                'jenis_kerja_sama' => 'Praktik kerja industri, sinkronisasi kurikulum, dan rekrutmen alumni.',
                'lokasi' => 'Semarang',
            ],
        ];

        foreach ($partners as $partner) {
            IndustryPartner::updateOrCreate(['nama_perusahaan' => $partner['nama_perusahaan']], $partner);
        }
    }

    private function seedJobVacancies(): void
    {
        // Data contoh lowongan kerja alumni - ganti melalui database/admin panel.
        $vacancies = [
            [
                'perusahaan' => 'PT Contoh Teknologi Nusantara',
                'posisi' => 'Junior Web Developer',
                'lokasi' => 'Surakarta',
                'batas_lamaran' => '2026-12-31',
                'link_pendaftaran' => '/pkl-bkk',
                'deskripsi' => 'Terbuka untuk alumni RPL. Data contoh - dapat diubah melalui database/admin panel.',
            ],
            [
                'perusahaan' => 'PT Contoh Otomotif Jaya',
                'posisi' => 'Teknisi Ototronik',
                'lokasi' => 'Semarang',
                'batas_lamaran' => '2026-11-30',
                'link_pendaftaran' => '/pkl-bkk',
                'deskripsi' => 'Terbuka untuk alumni Teknik Ototronik. Data contoh - dapat diubah melalui database/admin panel.',
            ],
        ];

        foreach ($vacancies as $vacancy) {
            JobVacancy::updateOrCreate(
                ['perusahaan' => $vacancy['perusahaan'], 'posisi' => $vacancy['posisi']],
                $vacancy,
            );
        }
    }

    private function seedBludProducts(): void
    {
        // Data contoh produk unggulan BLUD - ganti melalui database/admin panel.
        $products = [
            [
                'nama_produk' => 'Kain Batik Tulis Karya Siswa',
                'deskripsi' => 'Kain batik tulis produksi siswa jurusan tekstil dengan motif khas Karanganyar. Data contoh - dapat diubah melalui database/admin panel.',
                'harga' => 250000,
                'unit' => 'lembar',
            ],
            [
                'nama_produk' => 'Kain Ecoprint',
                'deskripsi' => 'Kain ecoprint ramah lingkungan hasil praktik siswa. Data contoh - dapat diubah melalui database/admin panel.',
                'harga' => 175000,
                'unit' => 'lembar',
            ],
            [
                'nama_produk' => 'Jasa Servis dan Bubut Logam',
                'deskripsi' => 'Layanan jasa permesinan (bubut, frais) yang dikerjakan siswa bersama guru pembimbing. Data contoh - dapat diubah melalui database/admin panel.',
                'harga' => null,
                'unit' => null,
            ],
        ];

        foreach ($products as $product) {
            BludProduct::updateOrCreate(['nama_produk' => $product['nama_produk']], $product);
        }
    }
}
