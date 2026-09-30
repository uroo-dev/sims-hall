<?php

namespace Tests\Feature;

use App\Models\Sekolah;
use App\Models\User;
use Database\Seeders\DataMasterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Smoke test sementara untuk verifikasi hasil merge branch `dapin`.
 * Berisi assertion tentang titik integrasi yang rawan rusak saat merge:
 * accessor Dudi::nama, video hero, sidebar role-aware, dan route Data Master.
 */
class MergeSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DataMasterSeeder::class);
    }

    public function test_landing_memakai_video_hero_ketika_tanpa_data_sekolah(): void
    {
        // Kolom profil_dokumentasi NOT NULL, jadi fallback video tidak bisa
        // dipancing dengan mengosongkan kolomnya. Menghapus baris sekolah
        // membuat controller mengembalikan model kosong, dan `isset()`
        // pada atribut yang tidak ada bernilai false sehingga blok @else
        // (video) yang dirender.
        Sekolah::query()->delete();

        $this->get('/')->assertOk()
            ->assertSee('assets/hero.mp4', false)
            ->assertSee('autoplay', false)
            ->assertSee('loop', false)
            ->assertSee('muted', false)
            ->assertSee('playsinline', false);
    }

    public function test_landing_memakai_foto_sekolah_bila_ada(): void
    {
        Sekolah::query()->update(['profil_dokumentasi' => 'sekolah-profile.png']);

        $html = $this->get('/')->assertOk()->getContent();

        // Foto profil sekolah lebih diprioritaskan daripada video fallback.
        $this->assertStringContainsString('sekolah-profile.png', $html);
        $this->assertStringNotContainsString('assets/hero.mp4', $html);
    }

    public function test_landing_menampilkan_nama_mitra_lewat_accessor(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('Nasmoco')
            ->assertSee('PT Yichad Textile Indonesia');
    }

    public function test_halaman_publik_lain_tetap_200(): void
    {
        foreach (['/profil', '/ppdb', '/kesiswaan'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_widget_nanya_ai_tetap_ada_di_halaman_publik(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('Nanya AI', $html);

        // URL endpoint di-escape slash-nya (`chatbot\/send`) karena halaman
        // di-render sebagai JSON di dalam literal JS.
        $this->assertStringContainsString('chatbot\/send', $html);

        // Layout sudah menyediakan global modal; partial chatbot tidak
        // boleh menyalinnya lagi karena id-nya akan bentrok.
        $this->assertSame(1, substr_count($html, 'id="global-modal"'));
        $this->assertSame(1, substr_count($html, 'id="modal-content"'));
    }

    public function test_route_data_master_milik_dapin_terdaftar(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('datamaster.index'))->assertOk();
        $this->actingAs($admin)->get(route('datamaster.users'))->assertOk();
        $this->actingAs($admin)->get(route('datamaster.sekolah.edit'))->assertOk();
    }

    public function test_sidebar_bkk_tidak_menampilkan_modul_lain(): void
    {
        $bkk = User::factory()->create(['role' => 'bkk']);

        $html = $this->actingAs($bkk)->get(route('pkl.dashboard'))->assertOk()->getContent();

        $this->assertStringNotContainsString('Data Master Sekolah', $html);
        $this->assertStringNotContainsString('Kepala Sekolah', $html);
        $this->assertStringContainsString('PKL &amp; BKK', $html);
        $this->assertStringContainsString(route('pkl.dudi.index'), $html);
    }

    public function test_admin_melihat_data_master_dan_pkl_bkk(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $html = $this->actingAs($admin)->get(route('pkl.dashboard'))->assertOk()->getContent();

        $this->assertStringContainsString('Data Master Sekolah', $html);
        $this->assertStringContainsString(route('datamaster.index'), $html);
        $this->assertStringContainsString(route('pkl.lowongan.index'), $html);
    }

    public function test_tidak_ada_sidebar_ganda_di_semua_halaman_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        foreach ([route('dashboard'), route('pkl.dashboard'), route('datamaster.index')] as $url) {
            $html = $this->actingAs($admin)->get($url)->assertOk()->getContent();

            $this->assertSame(
                1,
                substr_count($html, '<aside'),
                "Halaman {$url} harus punya tepat satu sidebar."
            );
        }
    }

public function test_layout_admin_tidak_leak_nama_modul_lain(): void
    {
        $bkk = User::factory()->create(['role' => 'bkk']);

        // Komentar HTML di layout ikut terkirim, jadi nama modul lain
        // tidak boleh muncul di halaman milik role bkk.
        $html = $this->actingAs($bkk)->get(route('pkl.dashboard'))->assertOk()->getContent();

        $this->assertStringNotContainsString('Peminjaman Aula', $html);
        $this->assertStringNotContainsString('Produk Unggulan', $html);
    }
}
