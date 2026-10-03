<?php

namespace Database\Seeders;

use App\Models\Artikel;
use App\Models\KategoriArtikel;
use Illuminate\Database\Seeder;

/**
 * Kategori artikel dasar untuk halaman publik.
 *
 * Daftar ini sengaja hanya berisi kategori yang dipakai kode publik
 * (Artikel::scopePrestasi()). Kategori lain — berita, pengumuman, dan
 * sejenisnya — dibuat dari dashboard admin.
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
            'deskripsi' => 'Pencapaian dan penghargaan siswa SMK Negeri 2 Karanganyar.',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->kategori as $kategori) {
            // Dicocokkan lewat `nama` supaya aman terhadap variations kapitalisasi
            // dari dashboard admin, lalu slug-nya selalu ditulis ulang ke nilai
            // kanonik yang dipakai scope prestasi.
            KategoriArtikel::updateOrCreate(
                ['nama' => $kategori['nama']],
                ['slug' => $kategori['slug'], 'deskripsi' => $kategori['deskripsi']],
            );
        }
    }
}
