<?php

namespace Tests\Feature\Admin;

use App\Models\Facility;
use App\Models\Fitur;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FasilitasTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminAula(): User
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        Fitur::create([
            'user_id' => $user->id,
            'nama_fitur' => 'aula',
        ]);

        return $user;
    }

    public function test_guest_is_redirected_from_fasilitas_index(): void
    {
        $response = $this->get('/admin/fasilitas');

        $response->assertRedirect('/login');
    }

    public function test_user_without_aula_fitur_cannot_access_fasilitas(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        Fitur::create([
            'user_id' => $user->id,
            'nama_fitur' => 'kesiswaan',
        ]);

        $response = $this->actingAs($user)->get('/admin/fasilitas');

        $response->assertForbidden();
    }

    public function test_admin_aula_can_view_fasilitas_index(): void
    {
        $admin = $this->createAdminAula();
        $facility = Facility::create([
            'judul' => 'Sound System 5000W',
            'deskripsi' => 'Set audio profesional',
        ]);

        $response = $this->actingAs($admin)->get('/admin/fasilitas');

        $response->assertOk();
        $response->assertSee('Sound System 5000W');
        $response->assertSee('Manajemen Fasilitas Aula');
    }

    public function test_admin_aula_can_create_facility(): void
    {
        $admin = $this->createAdminAula();

        $response = $this->actingAs($admin)->post('/admin/fasilitas', [
            'judul' => 'Proyektor Laser HD',
            'deskripsi' => 'Proyektor resolusi tinggi 4K',
        ]);

        $response->assertRedirect('/admin/fasilitas');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('facilities', [
            'judul' => 'Proyektor Laser HD',
            'deskripsi' => 'Proyektor resolusi tinggi 4K',
        ]);
    }

    public function test_admin_aula_can_update_facility(): void
    {
        $admin = $this->createAdminAula();
        $facility = Facility::create([
            'judul' => 'Panggung Aula',
            'deskripsi' => 'Ukuran 8x6 meter',
        ]);

        $response = $this->actingAs($admin)->put("/admin/fasilitas/{$facility->id}", [
            'judul' => 'Panggung Aula Utama',
            'deskripsi' => 'Ukuran 10x8 meter karpet merah',
        ]);

        $response->assertRedirect('/admin/fasilitas');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('facilities', [
            'id' => $facility->id,
            'judul' => 'Panggung Aula Utama',
            'deskripsi' => 'Ukuran 10x8 meter karpet merah',
        ]);
    }

    public function test_admin_aula_can_delete_facility(): void
    {
        $admin = $this->createAdminAula();
        $facility = Facility::create([
            'judul' => 'AC Portable 2 PK',
            'deskripsi' => 'Unit pendingin tambahan',
        ]);

        $response = $this->actingAs($admin)->delete("/admin/fasilitas/{$facility->id}");

        $response->assertRedirect('/admin/fasilitas');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('facilities', [
            'id' => $facility->id,
        ]);
    }

    public function test_super_admin_can_access_fasilitas(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        $response = $this->actingAs($superAdmin)->get('/admin/fasilitas');

        $response->assertOk();
    }
}
