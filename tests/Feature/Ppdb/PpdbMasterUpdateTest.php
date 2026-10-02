<?php

namespace Tests\Feature\Ppdb;

use App\Models\Ppdb_master;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PpdbMasterUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_data_is_created_when_table_is_empty(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        $this->post('/admin/ppdb/master', [
            'judul' => 'PPDB 2027',
            'deskripsi' => 'Gelombang pertama',
        ])->assertRedirect();

        $this->assertDatabaseCount('ppdb_master', 1);
        $this->assertDatabaseHas('ppdb_master', [
            'judul' => 'PPDB 2027',
            'deskripsi' => 'Gelombang pertama',
            'banner_img' => null,
        ]);
    }

    public function test_table_always_contains_only_one_row(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        $this->post('/admin/ppdb/master', ['judul' => 'Satu', 'deskripsi' => 'a']);
        $this->post('/admin/ppdb/master', ['judul' => 'Dua', 'deskripsi' => 'b']);
        $this->post('/admin/ppdb/master', ['judul' => 'Tiga', 'deskripsi' => 'c']);

        $this->assertDatabaseCount('ppdb_master', 1);
        $this->assertDatabaseHas('ppdb_master', ['judul' => 'Tiga', 'deskripsi' => 'c']);
    }

    public function test_banner_image_is_stored_on_public_disk(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        $this->post('/admin/ppdb/master', [
            'judul' => 'PPDB 2027',
            'deskripsi' => 'Deskripsi',
            'banner_img' => UploadedFile::fake()->image('banner.jpg'),
        ]);

        $master = Ppdb_master::first();

        $this->assertNotNull($master->banner_img);
        $this->assertStringStartsWith('ppdb/', $master->banner_img);
        Storage::disk('public')->assertExists($master->banner_img);
    }

    public function test_existing_banner_is_kept_when_no_new_file_uploaded(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());
        Ppdb_master::create([
            'judul' => 'Lama',
            'deskripsi' => 'Lama',
            'banner_img' => 'ppdb/lama.jpg',
        ]);

        $this->post('/admin/ppdb/master', ['judul' => 'Baru', 'deskripsi' => 'Baru']);

        $this->assertDatabaseHas('ppdb_master', [
            'judul' => 'Baru',
            'deskripsi' => 'Baru',
            'banner_img' => 'ppdb/lama.jpg',
        ]);
    }

    public function test_old_banner_file_is_deleted_when_replaced(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());
        Storage::disk('public')->put('ppdb/lama.jpg', 'fake');
        Ppdb_master::create([
            'judul' => 'Lama',
            'deskripsi' => 'Lama',
            'banner_img' => 'ppdb/lama.jpg',
        ]);

        $this->post('/admin/ppdb/master', [
            'judul' => 'Baru',
            'deskripsi' => 'Baru',
            'banner_img' => UploadedFile::fake()->image('baru.jpg'),
        ]);

        $master = Ppdb_master::first();

        Storage::disk('public')->assertMissing('ppdb/lama.jpg');
        Storage::disk('public')->assertExists($master->banner_img);
        $this->assertNotSame('ppdb/lama.jpg', $master->banner_img);
    }

    public function test_banner_is_removed_when_delete_flag_is_sent(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());
        Storage::disk('public')->put('ppdb/lama.jpg', 'fake');
        Ppdb_master::create([
            'judul' => 'Lama',
            'deskripsi' => 'Lama',
            'banner_img' => 'ppdb/lama.jpg',
        ]);

        $this->post('/admin/ppdb/master', [
            'judul' => 'Tanpa Banner',
            'deskripsi' => 'Deskripsi',
            'hapus_gambar' => 1,
        ]);

        $this->assertDatabaseHas('ppdb_master', [
            'judul' => 'Tanpa Banner',
            'banner_img' => null,
        ]);
        Storage::disk('public')->assertMissing('ppdb/lama.jpg');
    }

    public function test_new_file_upload_wins_over_delete_flag(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());
        Storage::disk('public')->put('ppdb/lama.jpg', 'fake');
        Ppdb_master::create([
            'judul' => 'Lama',
            'deskripsi' => 'Lama',
            'banner_img' => 'ppdb/lama.jpg',
        ]);

        $this->post('/admin/ppdb/master', [
            'judul' => 'Baru',
            'deskripsi' => 'Baru',
            'hapus_gambar' => 1,
            'banner_img' => UploadedFile::fake()->image('baru.jpg'),
        ]);

        $master = Ppdb_master::first();

        $this->assertNotNull($master->banner_img);
        $this->assertNotSame('ppdb/lama.jpg', $master->banner_img);
        Storage::disk('public')->assertMissing('ppdb/lama.jpg');
        Storage::disk('public')->assertExists($master->banner_img);
    }

    public function test_form_shows_existing_data(): void
    {
        $this->actingAs($this->admin());
        Ppdb_master::create([
            'judul' => 'PPDB 2027',
            'deskripsi' => 'Deskripsi lama',
            'banner_img' => 'ppdb/ada.jpg',
        ]);

        $this->get('/admin/ppdb/dashboard')
            ->assertOk()
            ->assertSee('PPDB 2027')
            ->assertSee('Deskripsi lama')
            ->assertSee('ppdb/ada.jpg');
    }

    public function test_update_requires_judul_and_deskripsi(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        $this->from('/ppdb/dashboard')
            ->post('/admin/ppdb/master', ['judul' => '', 'deskripsi' => ''])
            ->assertRedirect('/ppdb/dashboard')
            ->assertSessionHasErrors(['judul', 'deskripsi']);

        $this->assertDatabaseCount('ppdb_master', 0);
    }

    public function test_banner_must_be_a_valid_image(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        $this->from('/ppdb/dashboard')
            ->post('/admin/ppdb/master', [
                'judul' => 'PPDB',
                'deskripsi' => 'Deskripsi',
                'banner_img' => UploadedFile::fake()->create('dokumen.pdf', 10, 'application/pdf'),
            ])
            ->assertSessionHasErrors('banner_img');

        $this->assertDatabaseCount('ppdb_master', 0);
    }

    public function test_guests_cannot_access_ppdb_dashboard(): void
    {
        $this->get('/admin/ppdb/dashboard')->assertRedirect('/login');
    }

    public function test_update_is_persisted_across_requests(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        $this->post('/admin/ppdb/master', [
            'judul' => 'Awal',
            'deskripsi' => 'Awal',
            'banner_img' => UploadedFile::fake()->image('awal.jpg'),
        ]);

        $pathAwal = Ppdb_master::first()->banner_img;

        $this->post('/admin/ppdb/master', [
            'judul' => 'Akhir',
            'deskripsi' => 'Akhir',
            'banner_img' => UploadedFile::fake()->image('akhir.jpg'),
        ]);

        $master = Ppdb_master::first();

        $this->assertSame('Akhir', $master->judul);
        $this->assertNotSame($pathAwal, $master->banner_img);
        Storage::disk('public')->assertMissing($pathAwal);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin_ppdb']);
    }
}
