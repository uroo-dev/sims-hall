<?php

namespace Database\Seeders;

use App\Models\Artikel;
use App\Models\KategoriArtikel;
use Illuminate\Database\Seeder;

/**
 * Kategori artikel dasar untuk halaman publik dan admin.
 */
class KategoriArtikelSeeder extends Seeder
{
    /**
     * @var list<array<string, string>>
     */
    protected array $kategori = [
        [
            'nama' => 'Prestasi',
            'slug' => Artikel::KATEGORI_PRESTASI,
            'deskripsi' => 'Pencapaian dan penghargaan membanggakan siswa SMK Negeri 2 Karanganyar.',
        ],
        [
            'nama' => 'Berita Sekolah',
            'slug' => 'berita-sekolah',
            'deskripsi' => 'Kabar berita terkini dan dinamika seputar kegiatan civitas akademika sekolah.',
        ],
        [
            'nama' => 'Pengumuman Resmi',
            'slug' => 'pengumuman',
            'deskripsi' => 'Pemberitahuan kedinasan, agenda akademik, serta informasi penting bagi siswa dan wali.',
        ],
        [
            'nama' => 'Inovasi & TEFA',
            'slug' => 'inovasi-tefa',
            'deskripsi' => 'Hasil karya riset terapan, produk Teaching Factory (TEFA), dan inovasi kejuruan.',
        ],
        [
            'nama' => 'Agenda & Kegiatan',
            'slug' => 'agenda-kegiatan',
            'deskripsi' => 'Jadwal workshop industri, seminar nasional, pameran karya, serta kunjungan kejuruan.',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->kategori as $kategori) {
            KategoriArtikel::updateOrCreate(
                ['slug' => $kategori['slug']],
                [
                    'nama' => $kategori['nama'],
                    'deskripsi' => $kategori['deskripsi'],
                ]
            );
        }
    }
}
