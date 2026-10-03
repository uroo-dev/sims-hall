<?php

namespace Database\Seeders;

use App\Models\Artikel;
use App\Models\KategoriArtikel;
use Database\Seeders\Concerns\MenyalinAset;
use Illuminate\Database\Seeder;

class ArtikelSeeder extends Seeder
{
    use MenyalinAset;

    public function run(): void
    {
        $kategori = KategoriArtikel::where('slug', 'pengumuman')->value('id');
        $gambar = $this->salinKeStoragePublik('assets/dummy/dummy.jpg');

        $artikel = [
            [
                'kategori_artikel_id' => $kategori,
                'judul' => 'Pembukaan Tahun Ajaran Baru',
                'slug' => 'pembukaan-tahun-ajaran-baru',
                'ringkasan' => 'Sekolah membuka tahun ajaran baru bagi seluruh siswa.',
                'konten' => '<p>Sekolah resmi membuka tahun ajaran baru bagi seluruh siswa.</p>',
                'status' => 'published',
            ],
            [
                'kategori_artikel_id' => $kategori,
                'judul' => 'Jadwal Ujian Akhir Semester',
                'slug' => 'jadwal-ujian-akhir-semester',
                'ringkasan' => 'Informasi jadwal ujian akhir semester.',
                'konten' => '<p>Jadwal ujian akhir semester dapat dilihat pada papan pengumuman.</p>',
                'status' => 'published',
            ],
        ];

        foreach ($artikel as $data) {
            $data['gambar'] = $gambar;
            $data['published_at'] = now();
            $data['views'] = 0;

            Artikel::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
