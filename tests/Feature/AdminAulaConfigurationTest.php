<?php

namespace Tests\Feature;

use App\Models\Aula;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAulaConfigurationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.aula.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_unauthorized_role_cannot_access_aula_configuration(): void
    {
        $pelanggan = User::factory()->create(['role' => 'pelanggan']);
        $bkk = User::factory()->create(['role' => 'bkk']);

        $this->actingAs($pelanggan)->get(route('admin.aula.index'))->assertStatus(403);
        $this->actingAs($bkk)->get(route('admin.aula.index'))->assertStatus(403);
    }

    public function test_admin_aula_can_view_aula_configuration_page(): void
    {
        $adminAula = User::factory()->create(['role' => 'admin_aula']);

        Aula::create([
            'nama' => 'Graha Krida Utama SMKN 2',
            'judul' => 'Sewa Aula Eksklusif Karanganyar',
            'deskripsi' => 'Deskripsi aula sekolah',
        ]);

        $response = $this->actingAs($adminAula)->get(route('admin.aula.index'));

        $response->assertStatus(200);
        $response->assertViewIs('Admin.peminjaman.aula.index');
        $response->assertSee('Graha Krida Utama SMKN 2');
        $response->assertSee('Sewa Aula Eksklusif Karanganyar');
    }

    public function test_super_admin_can_view_aula_configuration_page(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($superAdmin)->get(route('admin.aula.index'));

        $response->assertStatus(200);
        $response->assertViewIs('Admin.peminjaman.aula.index');
    }

    public function test_admin_aula_can_update_aula_configuration_with_images(): void
    {
        Storage::fake('public');

        $adminAula = User::factory()->create(['role' => 'admin_aula']);

        $fotoUtama = UploadedFile::fake()->image('aula_utama.jpg', 800, 600);
        $fotoPendukung = UploadedFile::fake()->image('aula_interior.jpg', 800, 600);

        $response = $this->actingAs($adminAula)->put(route('admin.aula.update'), [
            'nama' => 'Aula Sasana Krida SMKN 2 Kra',
            'judul' => 'Gedung Pertemuan Modern & Nyaman',
            'deskripsi' => 'Kapasitas 1000 orang dengan AC dan sound system profesional.',
            'dokumentasi' => $fotoUtama,
            'dokumentasi_2' => $fotoPendukung,
        ]);

        $response->assertRedirect(route('admin.aula.index'));
        $response->assertSessionHas('success');

        $aula = Aula::first();
        $this->assertNotNull($aula);
        $this->assertSame('Aula Sasana Krida SMKN 2 Kra', $aula->nama);
        $this->assertSame('Gedung Pertemuan Modern & Nyaman', $aula->judul);
        $this->assertSame('Kapasitas 1000 orang dengan AC dan sound system profesional.', $aula->deskripsi);

        Storage::disk('public')->assertExists($aula->dokumentasi);
        Storage::disk('public')->assertExists($aula->dokumentasi_2);
    }

    public function test_validation_fails_if_nama_aula_is_missing(): void
    {
        $adminAula = User::factory()->create(['role' => 'admin_aula']);

        $response = $this->actingAs($adminAula)->put(route('admin.aula.update'), [
            'nama' => '',
            'judul' => 'Judul Baru',
        ]);

        $response->assertSessionHasErrors('nama');
    }
}
