<?php

namespace Tests\Feature\Admin;

use App\Models\Facility;
use App\Models\Fitur;
use App\Models\PaketPeminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaketPeminjamanTest extends TestCase
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

    public function test_guest_is_redirected_from_paket_index(): void
    {
        $response = $this->get('/admin/paket');

        $response->assertRedirect('/login');
    }

    public function test_user_without_aula_fitur_cannot_access_paket(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        Fitur::create([
            'user_id' => $user->id,
            'nama_fitur' => 'kesiswaan',
        ]);

        $response = $this->actingAs($user)->get('/admin/paket');

        $response->assertForbidden();
    }

    public function test_admin_aula_can_view_paket_index(): void
    {
        $admin = $this->createAdminAula();
        $facility1 = Facility::create(['judul' => 'Sound System']);
        $facility2 = Facility::create(['judul' => 'Proyektor']);

        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Seminar',
            'kategori' => 'unggulan',
            'harga' => 5000000,
            'deskripsi' => 'Paket komplit untuk seminar',
        ]);
        $paket->facilities()->sync([$facility1->id, $facility2->id]);

        $response = $this->actingAs($admin)->get('/admin/paket');

        $response->assertOk();
        $response->assertSee('Paket Seminar');
        $response->assertSee('Sound System');
        $response->assertSee('Proyektor');
    }

    public function test_admin_aula_can_create_paket_with_multiple_facilities(): void
    {
        $admin = $this->createAdminAula();
        $facility1 = Facility::create(['judul' => 'Kursi Lipat 300']);
        $facility2 = Facility::create(['judul' => 'Panggung Aula']);
        $facility3 = Facility::create(['judul' => 'AC Portable']);

        $response = $this->actingAs($admin)->post('/admin/paket', [
            'nama_paket' => 'Paket Wisuda',
            'kategori' => 'unggulan',
            'harga' => 7500000,
            'deskripsi' => 'Termasuk sound dan AC',
            'facility_ids' => [$facility1->id, $facility2->id, $facility3->id],
        ]);

        $response->assertRedirect('/admin/paket');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('paket_peminjamans', [
            'nama_paket' => 'Paket Wisuda',
            'kategori' => 'unggulan',
            'harga' => 7500000,
        ]);

        $paket = PaketPeminjaman::where('nama_paket', 'Paket Wisuda')->first();
        $this->assertNotNull($paket);
        $this->assertCount(3, $paket->facilities);
        $this->assertDatabaseHas('detail_paket_peminjamans', [
            'paket_peminjaman_id' => $paket->id,
            'facility_id' => $facility1->id,
        ]);
        $this->assertDatabaseHas('detail_paket_peminjamans', [
            'paket_peminjaman_id' => $paket->id,
            'facility_id' => $facility2->id,
        ]);
        $this->assertDatabaseHas('detail_paket_peminjamans', [
            'paket_peminjaman_id' => $paket->id,
            'facility_id' => $facility3->id,
        ]);
    }

    public function test_admin_aula_can_update_paket_and_facilities(): void
    {
        $admin = $this->createAdminAula();
        $facility1 = Facility::create(['judul' => 'Fasilitas 1']);
        $facility2 = Facility::create(['judul' => 'Fasilitas 2']);
        $facility3 = Facility::create(['judul' => 'Fasilitas 3']);

        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Awal',
            'kategori' => 'terjangkau',
            'harga' => 2000000,
        ]);
        $paket->facilities()->sync([$facility1->id]);

        $response = $this->actingAs($admin)->put("/admin/paket/{$paket->id}", [
            'nama_paket' => 'Paket Update',
            'kategori' => 'standar 1',
            'harga' => 3500000,
            'deskripsi' => 'Updated deskripsi',
            'facility_ids' => [$facility2->id, $facility3->id],
        ]);

        $response->assertRedirect('/admin/paket');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('paket_peminjamans', [
            'id' => $paket->id,
            'nama_paket' => 'Paket Update',
            'kategori' => 'standar 1',
        ]);

        $paket->refresh();
        $this->assertCount(2, $paket->facilities);
        $this->assertTrue($paket->facilities->contains($facility2));
        $this->assertTrue($paket->facilities->contains($facility3));
        $this->assertFalse($paket->facilities->contains($facility1));
    }

    public function test_admin_aula_can_delete_paket(): void
    {
        $admin = $this->createAdminAula();
        $facility = Facility::create(['judul' => 'Fasilitas']);
        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Hapus',
            'kategori' => 'standar 2',
            'harga' => 1000000,
        ]);
        $paket->facilities()->sync([$facility->id]);

        $response = $this->actingAs($admin)->delete("/admin/paket/{$paket->id}");

        $response->assertRedirect('/admin/paket');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('paket_peminjamans', [
            'id' => $paket->id,
        ]);
        $this->assertDatabaseMissing('detail_paket_peminjamans', [
            'paket_peminjaman_id' => $paket->id,
        ]);
    }
}
