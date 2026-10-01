<?php

namespace Tests\Feature\Ppdb;

use App\Models\Ppdb_informasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PpdbInformasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_informasi_is_created_when_table_is_empty(): void
    {
        $this->actingAs($this->admin());

        $this->post('/ppdb/informasi', [
            'judul' => 'Informasi PPDB 2027',
            'keterangan' => 'Gelombang pertama dibuka',
        ])->assertRedirect();

        $this->assertDatabaseCount('ppdb_informasi', 1);
        $this->assertDatabaseHas('ppdb_informasi', [
            'judul' => 'Informasi PPDB 2027',
            'keterangan' => 'Gelombang pertama dibuka',
        ]);
    }

    public function test_table_always_contains_only_one_row(): void
    {
        $this->actingAs($this->admin());

        $this->post('/ppdb/informasi', ['judul' => 'Satu', 'keterangan' => 'a']);
        $this->post('/ppdb/informasi', ['judul' => 'Dua', 'keterangan' => 'b']);
        $this->post('/ppdb/informasi', ['judul' => 'Tiga', 'keterangan' => 'c']);

        $this->assertDatabaseCount('ppdb_informasi', 1);
        $this->assertDatabaseHas('ppdb_informasi', ['judul' => 'Tiga', 'keterangan' => 'c']);
    }

    public function test_existing_informasi_is_updated(): void
    {
        $this->actingAs($this->admin());

        $this->post('/ppdb/informasi', ['judul' => 'Lama', 'keterangan' => 'keterangan lama']);
        $this->post('/ppdb/informasi', ['judul' => 'Baru', 'keterangan' => 'keterangan baru']);

        $this->assertDatabaseCount('ppdb_informasi', 1);
        $this->assertDatabaseHas('ppdb_informasi', [
            'judul' => 'Baru',
            'keterangan' => 'keterangan baru',
        ]);
    }

    public function test_keterangan_is_optional(): void
    {
        $this->actingAs($this->admin());

        $this->post('/ppdb/informasi', ['judul' => 'Tanpa Keterangan'])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('ppdb_informasi', [
            'judul' => 'Tanpa Keterangan',
            'keterangan' => null,
        ]);
    }

    public function test_judul_is_required(): void
    {
        $this->actingAs($this->admin());

        $this->from('/ppdb/informasi')
            ->post('/ppdb/informasi', ['judul' => ''])
            ->assertRedirect('/ppdb/informasi')
            ->assertSessionHasErrors('judul');

        $this->assertDatabaseCount('ppdb_informasi', 0);
    }

    public function test_judul_exceeding_max_length_is_rejected(): void
    {
        $this->actingAs($this->admin());

        $this->post('/ppdb/informasi', ['judul' => str_repeat('a', 101)])
            ->assertSessionHasErrors('judul');

        $this->assertDatabaseCount('ppdb_informasi', 0);
    }

    public function test_keterangan_exceeding_max_length_is_rejected(): void
    {
        $this->actingAs($this->admin());

        $this->post('/ppdb/informasi', [
            'judul' => 'Judul Valid',
            'keterangan' => str_repeat('b', 256),
        ])->assertSessionHasErrors('keterangan');

        $this->assertDatabaseCount('ppdb_informasi', 0);
    }

    public function test_page_shows_existing_informasi(): void
    {
        $this->actingAs($this->admin());

        Ppdb_informasi::create([
            'judul' => 'Informasi Tersimpan',
            'keterangan' => 'Keterangan yang tampil',
        ]);

        $this->get('/ppdb/informasi')
            ->assertOk()
            ->assertSee('Informasi Tersimpan')
            ->assertSee('Keterangan yang tampil');
    }

    public function test_guests_cannot_access_ppdb_informasi(): void
    {
        $this->get('/ppdb/informasi')->assertRedirect('/login');
    }

    public function test_guests_cannot_post_informasi(): void
    {
        $this->post('/ppdb/informasi', ['judul' => 'Gagal'])->assertRedirect('/login');

        $this->assertDatabaseCount('ppdb_informasi', 0);
    }

    public function test_existing_img_are_preserved(): void
    {
        $this->actingAs($this->admin());

        $informasi = Ppdb_informasi::create([
            'judul' => 'Awal',
            'keterangan' => 'Awal',
            'img' => ['ppdb/dokumen.pdf'],
        ]);

        $this->post('/ppdb/informasi', ['judul' => 'Akhir', 'keterangan' => 'Akhir']);

        $informasi->refresh();

        $this->assertSame(['ppdb/dokumen.pdf'], $informasi->img);
    }

    public function test_old_input_is_preserved_after_validation_error(): void
    {
        $this->actingAs($this->admin());

        $this->from('/ppdb/informasi')
            ->post('/ppdb/informasi', ['judul' => '', 'keterangan' => 'Keterangan_tempel']);

        $this->get('/ppdb/informasi')
            ->assertOk()
            ->assertSee('Keterangan_tempel');
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }
}
