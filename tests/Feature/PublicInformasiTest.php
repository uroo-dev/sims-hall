<?php

namespace Tests\Feature;

use App\Models\Artikel;
use App\Models\KategoriArtikel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicInformasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_header_contains_informasi_menu(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('informasi'));
        $response->assertSee('Informasi');
    }

    public function test_informasi_list_page_displays_published_articles(): void
    {
        $user = User::factory()->create();
        $kategori = KategoriArtikel::create(['nama' => 'Prestasi', 'slug' => 'prestasi']);

        $published = Artikel::create([
            'kategori_artikel_id' => $kategori->id,
            'user_id' => $user->id,
            'judul' => 'Siswa Juara 1 Lomba Web Design',
            'slug' => 'siswa-juara-1-lomba-web-design',
            'ringkasan' => 'Ringkasan prestasi siswa yang membanggakan.',
            'konten' => '<p>Konten lengkap mengenai prestasi siswa web design.</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $draft = Artikel::create([
            'kategori_artikel_id' => $kategori->id,
            'user_id' => $user->id,
            'judul' => 'Artikel Masih Konsep Rahasia',
            'slug' => 'artikel-masih-konsep-rahasia',
            'ringkasan' => 'Ringkasan draft rahasia.',
            'konten' => '<p>Draft belum boleh tayang.</p>',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $response = $this->get(route('informasi'));

        $response->assertOk();
        $response->assertSee('Artikel Terbaru');
        $response->assertSee('SKANDAKRA');
        $response->assertSee('Siswa Juara 1 Lomba Web Design');
        $response->assertDontSee('Artikel Masih Konsep Rahasia');
    }

    public function test_informasi_list_can_be_filtered_by_kategori(): void
    {
        $user = User::factory()->create();
        $kat1 = KategoriArtikel::create(['nama' => 'Akademik', 'slug' => 'akademik']);
        $kat2 = KategoriArtikel::create(['nama' => 'Teknologi', 'slug' => 'teknologi']);

        Artikel::create([
            'kategori_artikel_id' => $kat1->id,
            'user_id' => $user->id,
            'judul' => 'Jadwal Ujian Semester Genap',
            'slug' => 'jadwal-ujian-semester-genap',
            'ringkasan' => 'Info ujian semester genap.',
            'konten' => '<p>Jadwal lengkap ujian.</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Artikel::create([
            'kategori_artikel_id' => $kat2->id,
            'user_id' => $user->id,
            'judul' => 'Inovasi Teaching Factory RPL',
            'slug' => 'inovasi-teaching-factory-rpl',
            'ringkasan' => 'Info tefa rpl.',
            'konten' => '<p>Inovasi teknologi terkini.</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('informasi', ['kategori' => 'akademik']));

        $response->assertOk();
        $response->assertSee('Jadwal Ujian Semester Genap');
        $response->assertDontSee('Inovasi Teaching Factory RPL');
    }

    public function test_informasi_detail_page_renders_successfully_and_increments_views(): void
    {
        $user = User::factory()->create();
        $kategori = KategoriArtikel::create(['nama' => 'Kesiswaan', 'slug' => 'kesiswaan']);

        $artikel = Artikel::create([
            'kategori_artikel_id' => $kategori->id,
            'user_id' => $user->id,
            'judul' => 'SMKN 2 Siap Berlaga di LKBB Nasional',
            'slug' => 'smkn-2-siap-berlaga-di-lkbb-nasional',
            'ringkasan' => 'Persiapan tim Paskibra menuju nasional.',
            'konten' => '<p>Pasukan paskibra SKANDAKRA bersiap mengharumkan nama sekolah.</p>',
            'status' => 'published',
            'published_at' => now(),
            'views' => 10,
        ]);

        $response = $this->get(route('informasi.show', 'smkn-2-siap-berlaga-di-lkbb-nasional'));

        $response->assertOk();
        $response->assertSee('SMKN 2 Siap Berlaga di LKBB Nasional');
        $response->assertSee('Pasukan paskibra SKANDAKRA bersiap mengharumkan nama sekolah.');
        $response->assertSee('Bagikan Artikel Ini');

        $artikel->refresh();
        $this->assertEquals(11, $artikel->views);
    }

    public function test_informasi_detail_draft_returns_404(): void
    {
        $user = User::factory()->create();
        $kategori = KategoriArtikel::create(['nama' => 'Umum', 'slug' => 'umum']);

        Artikel::create([
            'kategori_artikel_id' => $kategori->id,
            'user_id' => $user->id,
            'judul' => 'Draft Yang Belum Boleh Dibuka',
            'slug' => 'draft-yang-belum-boleh-dibuka',
            'ringkasan' => 'Draft.',
            'konten' => '<p>Konten rahasia.</p>',
            'status' => 'draft',
        ]);

        $response = $this->get(route('informasi.show', 'draft-yang-belum-boleh-dibuka'));
        $response->assertNotFound();
    }
}
