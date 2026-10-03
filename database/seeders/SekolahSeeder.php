<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Seeder;

/**
 * Profil sekolah. Hanya ada satu baris, jadi seeder ini memakai `updateOrCreate`
 * tanpa kondisi agar aman dijalankan berulang kali.
 */
class SekolahSeeder extends Seeder
{
    public function run(): void
    {
        Sekolah::updateOrCreate([], [
            'judul' => 'SMK Negeri 2 Karanganyar',
            'deskripsi' => 'Sekolah menengah kejuruan yang mencetak tenaga kerja siap kerja di bidang teknologi, tekstil, dan manufaktur.',
            'dokumentasi' => 'assets/Sejarah.jpg',
            'sejarah' => 'SMK Negeri 2 Karanganyar berdiri sejak 2006 dengan jurusan Teknik Pemesinan, Teknik Ototronik, Teknik Pembuatan Kain, dan Rekayasa Perangkat Lunak. Sekolah membuka kerja sama dengan industri melalui program PKL.',
            'profil_judul' => 'Sekolah Menengah Kejuruan Berbasis Teknologi',
            'profil_deskripsi' => 'Menghasilkan lulusan yang siap bekerja, berdwipada, dan siap menghadapi perubahan.',
            'profil_dokumentasi' => 'full-jurusan-logo.png',
            'visi' => 'Menjadi SMK unggul yang menghasilkan lulusan siap kerja dan berdaya saing global.',
            'misi' => 'Menyelenggarakan pembelajaran berbasis proyek,eministuyan lingkungan kerja nyata, dan penilaian yang berorientasi pada kompetensi.',
            'sambutan_kepsek' => 'Selamat datang di website resmi SMK Negeri 2 Karanganyar. Kami membuka kerja sama dengan industri melalui program PKL.',
            'nama_kepsek' => 'Drs. Bambang Irawan, M.Pd.',
            'foto_kepsek' => 'foto kepsek.png',
            'yel_yel' => 'Teknik, Terampil, Berprestasi!',
        ]);
    }
}
