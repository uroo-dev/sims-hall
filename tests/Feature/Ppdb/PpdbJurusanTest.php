<?php

namespace Tests\Feature\Ppdb;

use App\Models\Jurusan;
use App\Models\Ppdb_jurusan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PpdbJurusanTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin_ppdb']);
    }

    public function test_admin_can_add_ppdb_jurusan_with_jurusan_id(): void
    {
        $this->actingAs($this->admin());

        $jurusan = Jurusan::create([
            'nama' => 'Rekayasa Perangkat Lunak',
            'deskripsi' => 'RPL',
        ]);

        $response = $this->post(route('post.jurusan.ppdb'), [
            'jurusan_id' => $jurusan->jurusanID,
            'daya_tampung' => 72,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ppdb_jurusan', [
            'jurusan_id' => $jurusan->jurusanID,
            'daya_tampung' => 72,
        ]);

        $ppdbJurusan = Ppdb_jurusan::first();
        $this->assertSame('Rekayasa Perangkat Lunak', $ppdbJurusan->nama_jurusan);
        $this->assertTrue($ppdbJurusan->jurusan->is($jurusan));
    }

    public function test_admin_can_update_ppdb_jurusan(): void
    {
        $this->actingAs($this->admin());

        $jurusan1 = Jurusan::create([
            'nama' => 'Rekayasa Perangkat Lunak',
            'deskripsi' => 'RPL',
        ]);
        $jurusan2 = Jurusan::create([
            'nama' => 'Teknik Ototronik',
            'deskripsi' => 'Oto',
        ]);

        $ppdbJurusan = Ppdb_jurusan::create([
            'jurusan_id' => $jurusan1->jurusanID,
            'daya_tampung' => 72,
        ]);

        $response = $this->put(route('update.jurusan.ppdb', $ppdbJurusan), [
            'jurusan_id' => $jurusan2->jurusanID,
            'daya_tampung' => 108,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ppdb_jurusan', [
            'id' => $ppdbJurusan->id,
            'jurusan_id' => $jurusan2->jurusanID,
            'daya_tampung' => 108,
        ]);
    }

    public function test_admin_can_delete_ppdb_jurusan(): void
    {
        $this->actingAs($this->admin());

        $jurusan = Jurusan::create([
            'nama' => 'Rekayasa Perangkat Lunak',
            'deskripsi' => 'RPL',
        ]);

        $ppdbJurusan = Ppdb_jurusan::create([
            'jurusan_id' => $jurusan->jurusanID,
            'daya_tampung' => 72,
        ]);

        $response = $this->delete(route('delete.jurusan.ppdb', $ppdbJurusan));

        $response->assertRedirect();
        $this->assertDatabaseMissing('ppdb_jurusan', [
            'id' => $ppdbJurusan->id,
        ]);
    }

    public function test_jurusan_id_must_exist_in_jurusan_table(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post(route('post.jurusan.ppdb'), [
            'jurusan_id' => 99999,
            'daya_tampung' => 72,
        ]);

        $response->assertSessionHasErrors('jurusan_id');
        $this->assertDatabaseCount('ppdb_jurusan', 0);
    }
}
