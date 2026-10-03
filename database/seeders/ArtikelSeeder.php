<?php

namespace Database\Seeders;

use App\Models\Artikel;
use App\Models\KategoriArtikel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ArtikelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan KategoriArtikelSeeder sudah dijalankan
        $this->call(KategoriArtikelSeeder::class);

        $penulis = User::where('role', 'admin_sekolah')->first()
            ?? User::where('role', 'super_admin')->first()
            ?? User::first();

        $kategoriPrestasi = KategoriArtikel::where('slug', Artikel::KATEGORI_PRESTASI)->first();
        $kategoriBerita = KategoriArtikel::where('slug', 'berita-sekolah')->first() ?? $kategoriPrestasi;
        $kategoriPengumuman = KategoriArtikel::where('slug', 'pengumuman')->first() ?? $kategoriPrestasi;
        $kategoriTefa = KategoriArtikel::where('slug', 'inovasi-tefa')->first() ?? $kategoriPrestasi;
        $kategoriAgenda = KategoriArtikel::where('slug', 'agenda-kegiatan')->first() ?? $kategoriPrestasi;

        $imagePath = 'dummy/dummy.jpg';

        $artikels = [
            [
                'kategori_artikel_id' => $kategoriBerita->id,
                'judul' => 'SMKN 2 Karanganyar Resmikan Fasilitas Teaching Factory Modern Berstandar Industri',
                'slug' => 'smkn-2-karanganyar-resmikan-fasilitas-tefa-modern-berstandar-industri',
                'ringkasan' => 'Fasilitas manufaktur presisi dan software engineering berstandar industri siap mencetak lulusan berdaya saing global.',
                'konten' => '<p>SMK Negeri 2 Karanganyar meresmikan gedung Teaching Factory (TEFA) terpadu. Peresmian ini dihadiri oleh jajaran Dinas Pendidikan serta pimpinan mitra industri terkemuka di Jawa Tengah.</p><p>Kepala Sekolah menegaskan bahwa sarana baru ini akan mendekatkan proses belajar siswa dengan ritme kerja industri sesungguhnya, mulai dari perancangan produk hingga kendali mutu yang presisi.</p>',
                'gambar' => $imagePath,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(2),
                'views' => 450,
            ],
            [
                'kategori_artikel_id' => $kategoriPrestasi->id,
                'judul' => 'Siswa Rekayasa Perangkat Lunak Raih Medali Emas LKS Nasional 2026',
                'slug' => 'siswa-rekayasa-perangkat-lunak-raih-medali-emas-lks-nasional-2026',
                'ringkasan' => 'Tim Web Technologies SMKN 2 Karanganyar sukses menorehkan prestasi membanggakan pada ajang Lomba Kompetensi Siswa Nasional.',
                'konten' => '<p>Prestasi prestisius kembali dipersembahkan oleh siswa jurusan Rekayasa Perangkat Lunak (RPL) SMKN 2 Karanganyar. Melalui proyek aplikasi berbasis cloud dan pengujian otomatis, perwakilan sekolah meraih predikat terbaik nasional.</p><p>Prestasi ini membuktikan mutu pendidikan vokasi teknologi informasi yang unggul dan adaptif terhadap kebutuhan dunia kerja digital.</p>',
                'gambar' => $imagePath,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(4),
                'views' => 620,
            ],
            [
                'kategori_artikel_id' => $kategoriPengumuman->id,
                'judul' => 'Informasi Resmi Alur Seleksi Penerimaan Peserta Didik Baru (PPDB) Tahun 2026',
                'slug' => 'informasi-resmi-alur-seleksi-ppdb-tahun-2026',
                'ringkasan' => 'Pendaftaran siswa baru dibuka melalui jalur zonasi, prestasi, afirmasi, dan domisili secara transparan dan akuntabel.',
                'konten' => '<p>Panitia PPDB SMKN 2 Karanganyar mengumumkan tahapan pendaftaran peserta didik baru. Seluruh proses verifikasi berkas dan pemilihan jurusan dilakukan secara daring melalui portal resmi sekolah.</p><p>Calon siswa dan wali murid diimbau memperhatikan linimasa dan melengkapi persyaratan dokumen sesuai petunjuk teknis.</p>',
                'gambar' => $imagePath,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(6),
                'views' => 890,
            ],
            [
                'kategori_artikel_id' => $kategoriTefa->id,
                'judul' => 'Karya Inovasi Siswa: Mesin CNC Router 3-Axis Siap Dipasarkan untuk UMKM',
                'slug' => 'karya-inovasi-siswa-mesin-cnc-router-3-axis-siap-dipasarkan',
                'ringkasan' => 'Kolaborasi kejuruan teknik mesin dan elektronika menghasilkan mesin perkakas presisi hemat energi dan ramah anggaran.',
                'konten' => '<p>Teaching Factory jurusan Teknik Pemesinan berhasil memproduksi unit mesin CNC Router 3-Axis yang dirancang khusus untuk membantu perajin kayu dan akrilik lokal.</p><p>Mesin ini telah melalui serangkaian uji akurasi dimensi dan ketahanan operasional, membuktikan kapabilitas produksi siswa setara standar manufaktur.</p>',
                'gambar' => $imagePath,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(8),
                'views' => 310,
            ],
            [
                'kategori_artikel_id' => $kategoriAgenda->id,
                'judul' => 'Kunjungan Industri & Penyelarasan Kurikulum Bersama Mitra DUDI Otomotif',
                'slug' => 'kunjungan-industri-dan-penyelarasan-kurikulum-mitra-dudi',
                'ringkasan' => 'Sebanyak 150 siswa jurusan Ototronik memperdalam teknologi kendaraan hibrida dan kelistrikan modern langsung di pabrik perakitan.',
                'konten' => '<p>Program pembelajaran luar kelas kembali digelar dengan menyambangi fasilitas perakitan mitra industri otomotif. Siswa diajak mengamati implementasi otomasi industri serta sistem sensor kendaraan masa kini.</p><p>Kegiatan ditutup dengan penandatanganan pembaruan nota kesepahaman (MoU) rekrutmen lulusan dan penyediaan kuota magang industri.</p>',
                'gambar' => $imagePath,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(10),
                'views' => 280,
            ],
        ];

        foreach ($artikels as $art) {
            Artikel::updateOrCreate(
                ['slug' => $art['slug']],
                array_merge($art, [
                    'user_id' => $penulis?->id,
                ])
            );
        }
    }
}
