<?php

namespace Tests\Feature;

use App\Models\Aula;
use App\Models\Facility;
use App\Models\PaketPeminjaman;
use App\Models\PaymentConfiguration;
use App\Models\Peminjaman;
use App\Models\Sekolah;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicLayananPeminjamanTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_layanan_peminjaman_page_can_be_accessed(): void
    {
        $response = $this->get(route('layanan-peminjaman'));

        $response->assertStatus(200);
        $response->assertViewIs('Public.layanan-peminjaman');
    }

    public function test_public_layanan_peminjaman_renders_database_records(): void
    {
        Sekolah::create([
            'judul' => 'SMK Negeri 2 Karanganyar',
            'sejarah' => 'Sejarah singkat sekolah',
            'profil_judul' => 'Profil Singkat',
            'profil_deskripsi' => 'Deskripsi profil sekolah',
            'profil_dokumentasi' => 'default.jpg',
            'visi' => 'Visi sekolah',
            'misi' => 'Misi sekolah',
        ]);

        $aula = Aula::create([
            'nama' => 'Graha Krida Utama',
            'judul' => 'Sewa Aula Eksklusif Karanganyar',
            'deskripsi' => 'Gedung serbaguna dengan kapasitas besar untuk berbagai acara formal dan resepsi.',
            'dokumentasi' => 'storage/aula/aula1.jpg',
        ]);

        $facility = Facility::create([
            'judul' => 'Sound System 10000W',
            'deskripsi' => 'Audio sistem konser jernih',
        ]);

        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Platinum Mewah',
            'deskripsi' => 'Paket all in one resepsi pernikahan',
            'durasi' => '8 Jam',
            'kategori' => 'unggulan',
            'harga' => 15000000,
            'harga_dp' => 5000000,
        ]);
        $paket->facilities()->attach($facility->id);

        PaymentConfiguration::create([
            'bank_utama' => 'Bank Jateng',
            'norek_utama' => '1234567890',
            'atas_nama_utama' => 'SMK N 2 Karanganyar',
            'minimal_hari_booking' => 3,
            'is_active' => true,
        ]);

        $bookedStart = Carbon::tomorrow()->format('Y-m-d');
        $bookedEnd = Carbon::tomorrow()->addDay()->format('Y-m-d');

        Peminjaman::create([
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'Pernikahan Megah',
            'email_instansi' => 'pengantin@example.com',
            'tanggal_mulai' => $bookedStart.' 08:00:00',
            'tanggal_selesai' => $bookedEnd.' 17:00:00',
            'status' => 'approved_final',
        ]);

        $response = $this->get(route('layanan-peminjaman'));

        $response->assertStatus(200);
        $response->assertSee('Sewa Aula Eksklusif Karanganyar');
        $response->assertSee('Graha Krida Utama');
        $response->assertSee('Paket Platinum Mewah');
        $response->assertSee('Sound System 10000W');
        $response->assertSee($bookedStart);
    }

    public function test_landing_and_layanan_pages_fallback_to_logo_if_dokumentasi_is_empty(): void
    {
        Sekolah::create([
            'judul' => 'SMK Negeri 2 Karanganyar',
            'sejarah' => 'Sejarah singkat',
            'profil_judul' => 'Profil',
            'profil_deskripsi' => 'Deskripsi',
            'profil_dokumentasi' => 'default.jpg',
            'visi' => 'Visi',
            'misi' => 'Misi',
        ]);

        Aula::create([
            'nama' => 'Aula Sasana Krida',
            'judul' => 'Sewa Aula Bagus',
            'deskripsi' => 'Deskripsi',
            'dokumentasi' => null,
            'dokumentasi_2' => null,
        ]);

        $responseLanding = $this->get(route('landing'));
        $responseLanding->assertStatus(200);
        $responseLanding->assertSee('logosmkk.png');

        $responseLayanan = $this->get(route('layanan-peminjaman'));
        $responseLayanan->assertStatus(200);
        $responseLayanan->assertSee('logosmkk.png');
    }

    public function test_aula_model_resolves_image_urls_correctly(): void
    {
        $aula = new Aula([
            'nama' => 'Aula 1',
            'dokumentasi' => 'https://example.com/foto1.jpg',
            'dokumentasi_2' => null,
        ]);

        $this->assertSame('https://example.com/foto1.jpg', $aula->foto_dokumentasi_url);
        $this->assertStringContainsString('logosmkk.png', $aula->foto_dokumentasi_2_url);
        $this->assertTrue($aula->has_custom_dokumentasi);
        $this->assertFalse($aula->has_custom_dokumentasi_2);
    }
}
