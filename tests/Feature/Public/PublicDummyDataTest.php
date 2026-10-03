<?php

namespace Tests\Feature\Public;

use App\Models\Artikel;
use App\Models\KategoriArtikel;
use App\Models\Lowongan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Halaman publik tidak boleh menampilkan data demo.
 *
 * Halaman publik sebelumnya mengisi sendiri bagian yang datanya belum ada:
 * poster lowongan PT Indaco/PT SCA, banner prestasi, program mitra contoh,
 * alamat "Jl. Raya Industri", dan angka "mitra resmi" yang sebenarnya cuma
 * jumlah mitra yang tampil di landing. Test ini mengunci aturan tersebut supaya
 * data dummy tidak masuk lagi diam-diam.
 */
class PublicDummyDataTest extends TestCase
{
    use RefreshDatabase;

    private function buatKategoriPrestasi(): KategoriArtikel
    {
        return KategoriArtikel::create([
            'nama' => 'Prestasi',
            'slug' => Artikel::KATEGORI_PRESTASI,
            'deskripsi' => 'Kategori prestasi.',
        ]);
    }

    private function buatArtikel(array $extra = []): Artikel
    {
        return Artikel::create(array_merge([
            'kategori_artikel_id' => $this->buatKategoriPrestasi()->id,
            'judul' => 'Juara 1 Lomba Regional',
            'slug' => 'juara-1-lomba-regional',
            'ringkasan' => 'Tim sekolah meraih juara 1 lomba regional.',
            'konten' => '<p>Ringkasan achievement.</p>',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ], $extra));
    }

    public function test_beranda_tidak_menampilkan_kartu_produk_dan_prestasi_hardcode(): void
    {
        $this->get(route('landing'))
            ->assertOk()
            ->assertDontSee('DIPROSES MENGGUNAKAN MESIN MODERN')
            ->assertDontSee('DARI KAIN BATIK, TENUN')
            ->assertDontSee('AKADEMIK');
    }

    public function test_beranda_menampilkan_prestasi_dari_artikel_kategori_prestasi(): void
    {
        $this->buatArtikel([
            'judul' => 'Juara 1 Lomba Fifo',
            'slug' => 'juara-1-lomba-fifo',
            'ringkasan' => 'Siswa RPL juara 1 lomba Fifo tingkat kabupaten.',
        ]);

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('Juara 1 Lomba Fifo');
    }

    public function test_beranda_mengabaikan_artikel_prestasi_yang_belum_published(): void
    {
        $this->buatArtikel([
            'judul' => 'Draft Prestasi Belum Terbit',
            'slug' => 'draft-prestasi-belum-terbit',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $this->get(route('landing'))
            ->assertOk()
            ->assertDontSee('Draft Prestasi Belum Terbit');
    }

    public function test_beranda_mengabaikan_artikel_prestasi_kategori_lain(): void
    {
        $berita = KategoriArtikel::create([
            'nama' => 'Berita',
            'slug' => 'berita',
            'deskripsi' => 'Kategori berita.',
        ]);

        Artikel::create([
            'kategori_artikel_id' => $berita->id,
            'judul' => 'Artikel Berita biasa',
            'slug' => 'artikel-berita-biasa',
            'ringkasan' => 'Bukan prestasi.',
            'konten' => '<p>Bukan prestasi.</p>',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('landing'))
            ->assertOk()
            ->assertDontSee('Artikel Berita biasa');
    }

    public function test_kesiswaan_tidak_menampilkan_banner_prestasi_hardcode(): void
    {
        $this->get(route('kesiswaan'))
            ->assertOk()
            ->assertDontSee('banner_terbaru_1.png')
            ->assertDontSee('banner_terbaru_2.png')
            ->assertDontSee('SMKN 2 KARANGANYAR SIAP PERTAHANKAN GELAR JATENG DI LKBB-PB NASIONAL');
    }

    public function test_kesiswaan_menampilkan_prestasi_dari_artikel(): void
    {
        $this->buatArtikel([
            'judul' => 'Juara 2 Karate',
            'slug' => 'juara-2-karate',
            'ringkasan' => 'Atlet karate sekolah menjadi juara tingkat kabupaten.',
        ]);

        $this->get(route('kesiswaan'))
            ->assertOk()
            ->assertSee('Juara 2 Karate')
            ->assertSee(route('informasi.show', 'juara-2-karate'), false);
    }

    public function test_halaman_bkk_tidak_menampilkan_poster_perusahaan_palsu(): void
    {
        $this->get(route('bkk'))
            ->assertOk()
            ->assertDontSee('PT INDACO WARNA DUNIA')
            ->assertDontSee('PT. SCA (Agung Tex Group)')
            ->assertDontSee('WE ARE HIRING');
    }

    public function test_halaman_bkk_menampilkan_poster_dari_lowongan_aktif(): void
    {
        Lowongan::create([
            'nama_perusahaan' => 'PT Maju Bersama',
            'posisi' => 'Operator CNC',
            'tipe' => Lowongan::TIPE_MAGANG,
            'jurusan_sesuai' => Lowongan::SEMUA_JURUSAN,
            'deskripsi' => 'Lowongan uji.',
            'link_daftar' => 'https://example.com/daftar',
            'deadline' => now()->addDays(10)->toDateString(),
            'is_active' => true,
        ]);

        $this->get(route('bkk'))
            ->assertOk()
            ->assertSee('PT Maju Bersama')
            ->assertSee('Operator CNC');
    }

    public function test_halaman_bkk_tidak_menampilkan_lowongan_kedaluwarsa(): void
    {
        Lowongan::create([
            'nama_perusahaan' => 'PT Lama',
            'posisi' => 'Posisi Sudah Lewat',
            'tipe' => Lowongan::TIPE_MAGANG,
            'jurusan_sesuai' => Lowongan::SEMUA_JURUSAN,
            'deskripsi' => 'Lowongan lama.',
            'link_daftar' => 'https://example.com/daftar',
            'deadline' => now()->subDay()->toDateString(),
            'is_active' => true,
        ]);

        $this->get(route('bkk'))
            ->assertOk()
            ->assertDontSee('Posisi Sudah Lewat')
            ->assertSee('Belum ada lowongan aktif');
    }
}
