<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\BludProduct;
use App\Models\Faq;
use App\Models\IndustryPartner;
use App\Models\Major;
use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;

class ChatbotKnowledgeSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['kategori' => 'PPDB', 'pertanyaan' => 'Kapan buka pendaftaran PPDB?', 'jawaban' => 'Buka setiap bulan Januari-Maret.'],
            ['kategori' => 'PKL', 'pertanyaan' => 'Bagaimana daftar PKL?', 'jawaban' => 'Hubungi BKK sekolah untuk info penempatan.'],
        ];

        foreach ($faqs as $f) {
            Faq::updateOrCreate(['pertanyaan' => $f['pertanyaan']], $f);
        }

        $settings = [
            ['kunci' => 'sekolah_nama', 'nilai' => 'SMK Negeri 2 Karanganyar'],
            ['kunci' => 'chatbot_welcome', 'nilai' => 'Halo! Ada yang bisa dibantu?'],
        ];

        foreach ($settings as $s) {
            SchoolSetting::updateOrCreate(['kunci' => $s['kunci']], $s);
        }

        $majors = [
            ['nama' => 'Rekayasa Perangkat Lunak', 'deskripsi' => 'Mempelajari pemrograman dan pengembangan aplikasi.', 'prospek_karier' => 'Programmer, Web Developer'],
            ['nama' => 'Teknik Pemesinan', 'deskripsi' => 'Mempelajari mesin bubut, frais, CNC.', 'prospek_karier' => 'Operator CNC, Teknisi Mesin'],
        ];

        foreach ($majors as $m) {
            // majora pakai table majors bukan Jurusan
            Major::updateOrCreate(['nama' => $m['nama']], $m);
        }

        $achievements = [
            ['judul' => 'Juara LKS Pemrograman', 'tingkat' => 'Nasional', 'tahun' => 2025, 'nama_siswa' => 'Aisyah', 'deskripsi' => 'Medali emas'],
            ['judul' => 'Juara Lomba Ototronik', 'tingkat' => 'Provinsi', 'tahun' => 2024, 'nama_siswa' => 'Bimo', 'deskripsi' => 'Juara 1'],
        ];

        foreach ($achievements as $a) {
            Achievement::updateOrCreate(['judul' => $a['judul']], $a);
        }

        $partners = [
            ['nama_perusahaan' => 'PT Nasmoco Solution', 'bidang' => 'IT', 'jenis_kerja_sama' => 'PKL', 'lokasi' => 'Surakarta'],
            ['nama_perusahaan' => 'CV Karya Mandiri', 'bidang' => 'Manufaktur', 'jenis_kerja_sama' => 'PKL', 'lokasi' => 'Karanganyar'],
        ];

        foreach ($partners as $p) {
            IndustryPartner::updateOrCreate(['nama_perusahaan' => $p['nama_perusahaan']], $p);
        }

        $products = [
            ['nama_produk' => 'Aplikasi Absensi', 'deskripsi' => 'Web app absensi', 'harga' => 5000000, 'unit' => 'unit'],
            ['nama_produk' => 'Mesin Otomatis', 'deskripsi' => 'Mesin penghitung', 'harga' => 25000000, 'unit' => 'unit'],
        ];

        foreach ($products as $p) {
            BludProduct::updateOrCreate(['nama_produk' => $p['nama_produk']], $p);
        }
    }
}
