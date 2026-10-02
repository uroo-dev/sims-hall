<?php

namespace Tests\Feature;

use App\Models\Dudi;
use App\Models\Lowongan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PklBkkLogoTest extends TestCase
{
    use RefreshDatabase;

    private User $bkk;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->bkk = User::factory()->create([
            'username' => 'bkk_admin',
            'role' => 'bkk',
        ]);
    }

    public function test_admin_bkk_dapat_tambah_dudi_beserta_logo(): void
    {
        $logo = UploadedFile::fake()->image('dudi_logo.png', 200, 200);

        $response = $this->actingAs($this->bkk)
            ->post(route('pkl.dudi.store'), [
                'nama_dudi' => 'PT Karanganyar Cipta Kreasi',
                'alamat' => 'Jl. Lawu No. 88, Karanganyar',
                'kota' => 'Karanganyar',
                'bidang_usaha' => 'Teknologi Informasi',
                'kuota_maksimal' => 5,
                'is_mitra_resmi' => 1,
                'tampil_di_landing' => 1,
                'logo' => $logo,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $dudi = Dudi::where('nama_dudi', 'PT Karanganyar Cipta Kreasi')->firstOrFail();
        $this->assertNotNull($dudi->logo);
        Storage::disk('public')->assertExists($dudi->logo);
        $this->assertStringContainsString('storage/', $dudi->logo_url);
    }

    public function test_admin_bkk_dapat_update_dudi_dan_ganti_logo(): void
    {
        $dudi = Dudi::create([
            'nama_dudi' => 'PT Awal Sejahtera',
            'alamat' => 'Jl. Tentara Pelajar',
            'kota' => 'Karanganyar',
            'bidang_usaha' => 'Manufaktur',
            'kuota_maksimal' => 3,
        ]);

        $newLogo = UploadedFile::fake()->image('new_logo.jpg', 300, 300);

        $response = $this->actingAs($this->bkk)
            ->patch(route('pkl.dudi.update', $dudi), [
                'nama_dudi' => 'PT Awal Sejahtera Sukses',
                'alamat' => 'Jl. Tentara Pelajar No. 10',
                'kota' => 'Karanganyar',
                'bidang_usaha' => 'Manufaktur Presisi',
                'kuota_maksimal' => 8,
                'tampil_di_landing' => 1,
                'logo' => $newLogo,
            ]);

        $response->assertRedirect();
        $dudi->refresh();

        $this->assertSame('PT Awal Sejahtera Sukses', $dudi->nama_dudi);
        $this->assertSame(8, $dudi->kuota_maksimal);
        $this->assertTrue($dudi->tampil_di_landing);
        $this->assertNotNull($dudi->logo);
        Storage::disk('public')->assertExists($dudi->logo);
    }

    public function test_admin_bkk_dapat_tambah_lowongan_beserta_logo(): void
    {
        $dudi = Dudi::create([
            'nama_dudi' => 'PT Nasmoco Solo',
            'alamat' => 'Jl. Slamet Riyadi',
            'kota' => 'Surakarta',
            'bidang_usaha' => 'Otomotif',
        ]);

        $logo = UploadedFile::fake()->image('lowongan_poster.png', 400, 400);

        $response = $this->actingAs($this->bkk)
            ->post(route('pkl.lowongan.store'), [
                'dudi_id' => $dudi->id,
                'nama_perusahaan' => 'PT Nasmoco Solo',
                'posisi' => 'Teknisi Ototronik Senior',
                'tipe' => Lowongan::TIPE_PEKERJAAN,
                'jurusan_sesuai' => 'Teknik Ototronik',
                'deskripsi' => 'Dibutuhkan segera teknisi mobil yang menguasai kelistrikan dan sistem injeksi modern.',
                'link_daftar' => 'https://nasmoco.co.id/karir',
                'deadline' => now()->addDays(20)->toDateString(),
                'is_active' => 1,
                'logo' => $logo,
            ]);

        $response->assertRedirect(route('pkl.lowongan.index'));
        $response->assertSessionHas('success');

        $lowongan = Lowongan::where('posisi', 'Teknisi Ototronik Senior')->firstOrFail();
        $this->assertNotNull($lowongan->logo);
        Storage::disk('public')->assertExists($lowongan->logo);
        $this->assertStringContainsString('storage/', $lowongan->logo_url);
    }

    public function test_lowongan_fallback_ke_logo_dudi_jika_logo_lowongan_kosong(): void
    {
        $logoDudi = UploadedFile::fake()->image('dudi_logo.png', 200, 200);
        $path = $logoDudi->store('dudi-logo', 'public');

        $dudi = Dudi::create([
            'nama_dudi' => 'PT Indaco',
            'alamat' => 'Jl. Raya Palur',
            'kota' => 'Karanganyar',
            'bidang_usaha' => 'Cat & Kimia',
            'logo' => $path,
        ]);

        $lowongan = Lowongan::create([
            'dudi_id' => $dudi->id,
            'nama_perusahaan' => 'PT Indaco',
            'posisi' => 'Staff Gudang',
            'tipe' => Lowongan::TIPE_PEKERJAAN,
            'jurusan_sesuai' => 'Semua Jurusan',
            'deskripsi' => 'Deskripsi kualifikasi lowongan pekerjaan minimal 20 karakter.',
            'link_daftar' => 'https://indaco.example.com',
            'deadline' => now()->addDays(10)->toDateString(),
            'is_active' => true,
            'logo' => null,
        ]);

        $this->assertNull($lowongan->logo);
        $this->assertSame($dudi->logo_url, $lowongan->logo_url);
    }

    public function test_admin_bkk_dapat_hapus_logo_lowongan(): void
    {
        $logo = UploadedFile::fake()->image('lowongan_poster.png', 400, 400);
        $path = $logo->store('lowongan-logo', 'public');

        $lowongan = Lowongan::create([
            'nama_perusahaan' => 'PT Maju Digital',
            'posisi' => 'Web Developer',
            'tipe' => Lowongan::TIPE_PEKERJAAN,
            'jurusan_sesuai' => 'RPL',
            'deskripsi' => 'Deskripsi kualifikasi lowongan pekerjaan minimal 20 karakter.',
            'link_daftar' => 'https://maju.example.com',
            'deadline' => now()->addDays(15)->toDateString(),
            'is_active' => true,
            'logo' => $path,
        ]);

        $response = $this->actingAs($this->bkk)
            ->put(route('pkl.lowongan.update', $lowongan), [
                'nama_perusahaan' => 'PT Maju Digital',
                'posisi' => 'Web Developer',
                'tipe' => Lowongan::TIPE_PEKERJAAN,
                'jurusan_sesuai' => 'RPL',
                'deskripsi' => 'Deskripsi kualifikasi lowongan pekerjaan minimal 20 karakter.',
                'link_daftar' => 'https://maju.example.com',
                'deadline' => now()->addDays(15)->toDateString(),
                'is_active' => 1,
                'hapus_logo' => 1,
            ]);

        $response->assertRedirect(route('pkl.lowongan.index'));
        $lowongan->refresh();

        $this->assertNull($lowongan->logo);
        Storage::disk('public')->assertMissing($path);
    }
}
