<?php

namespace Tests\Feature\Ppdb;

use App\Models\Ppdb_tanggal_penting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PpdbTanggalPentingTest extends TestCase
{
    use RefreshDatabase;

    public function test_agenda_can_be_created(): void
    {
        $this->actingAs($this->admin());

        $this->post('/ppdb/tanggal-penting', [
            'nama_agenda' => 'Senam Jasmani',
            'tanggal_mulai' => '2026-06-20',
            'tanggal_selesai' => '2026-06-27',
            'keterangan' => 'Kegiatan rutin hari minggu',
        ])->assertRedirect();

        $this->assertDatabaseCount('ppdb_tanggal_penting', 1);

        $agenda = Ppdb_tanggal_penting::first();

        $this->assertSame('Senam Jasmani', $agenda->nama_agenda);
        $this->assertSame('2026-06-20', $agenda->tanggal_mulai->toDateString());
        $this->assertSame('2026-06-27', $agenda->tanggal_selesai->toDateString());
        $this->assertSame('Kegiatan rutin hari minggu', $agenda->keterangan);
    }

    public function test_agenda_requires_all_fields(): void
    {
        $this->actingAs($this->admin());

        $this->from('/ppdb/informasi')
            ->post('/ppdb/tanggal-penting', [])
            ->assertRedirect('/ppdb/informasi')
            ->assertSessionHasErrors(['nama_agenda', 'tanggal_mulai', 'tanggal_selesai', 'keterangan']);

        $this->assertDatabaseCount('ppdb_tanggal_penting', 0);
    }

    public function test_tanggal_selesai_must_be_after_tanggal_mulai(): void
    {
        $this->actingAs($this->admin());

        $this->post('/ppdb/tanggal-penting', [
            'nama_agenda' => 'Terbalik',
            'tanggal_mulai' => '2026-06-27',
            'tanggal_selesai' => '2026-06-20',
            'keterangan' => 'Tidak valid',
        ])->assertSessionHasErrors('tanggal_selesai');

        $this->assertDatabaseCount('ppdb_tanggal_penting', 0);
    }

    public function test_tanggal_selesai_equal_to_tanggal_mulai_is_allowed(): void
    {
        $this->actingAs($this->admin());

        $this->post('/ppdb/tanggal-penting', [
            'nama_agenda' => 'Satu Hari',
            'tanggal_mulai' => '2026-06-20',
            'tanggal_selesai' => '2026-06-20',
            'keterangan' => 'DEU 2026',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseCount('ppdb_tanggal_penting', 1);
    }

    public function test_nama_agenda_max_length_is_rejected(): void
    {
        $this->actingAs($this->admin());

        $this->post('/ppdb/tanggal-penting', [
            'nama_agenda' => str_repeat('a', 101),
            'tanggal_mulai' => '2026-06-20',
            'tanggal_selesai' => '2026-06-27',
            'keterangan' => 'Keterangan',
        ])->assertSessionHasErrors('nama_agenda');

        $this->assertDatabaseCount('ppdb_tanggal_penting', 0);
    }

    public function test_keterangan_max_length_is_rejected(): void
    {
        $this->actingAs($this->admin());

        $this->post('/ppdb/tanggal-penting', [
            'nama_agenda' => 'Agenda',
            'tanggal_mulai' => '2026-06-20',
            'tanggal_selesai' => '2026-06-27',
            'keterangan' => str_repeat('b', 151),
        ])->assertSessionHasErrors('keterangan');

        $this->assertDatabaseCount('ppdb_tanggal_penting', 0);
    }

    public function test_agenda_can_be_updated(): void
    {
        $this->actingAs($this->admin());

        $agenda = $this->buatAgenda();

        $this->put('/ppdb/tanggal-penting/'.$agenda->id, [
            'nama_agenda' => 'Senam Terbarui',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2026-07-05',
            'keterangan' => 'Keterangan baru',
        ])->assertRedirect();

        $agenda->refresh();

        $this->assertSame('Senam Terbarui', $agenda->nama_agenda);
        $this->assertSame('2026-07-01', $agenda->tanggal_mulai->toDateString());
        $this->assertSame('2026-07-05', $agenda->tanggal_selesai->toDateString());
        $this->assertSame('Keterangan baru', $agenda->keterangan);
        $this->assertDatabaseCount('ppdb_tanggal_penting', 1);
    }

    public function test_agenda_can_be_deleted(): void
    {
        $this->actingAs($this->admin());

        $agenda = $this->buatAgenda();

        $this->delete('/ppdb/tanggal-penting/'.$agenda->id)->assertRedirect();

        $this->assertDatabaseCount('ppdb_tanggal_penting', 0);
    }

    public function test_overlapping_agendas_are_both_kept(): void
    {
        $this->actingAs($this->admin());

        $this->buatAgenda('2026-06-20', '2026-06-27');
        $this->buatAgenda('2026-06-25', '2026-06-28', 'Agenda Kedua');

        $this->assertDatabaseCount('ppdb_tanggal_penting', 2);
    }

    public function test_page_lists_agendas(): void
    {
        $this->actingAs($this->admin());

        $this->buatAgenda();

        $this->get('/ppdb/informasi')
            ->assertOk()
            ->assertSee('Senam Jasmani')
            ->assertSee('Kegiatan rutin hari minggu');
    }

    public function test_guests_cannot_manage_agendas(): void
    {
        $agenda = $this->buatAgenda();

        $this->post('/ppdb/tanggal-penting', [
            'nama_agenda' => 'Gagal',
            'tanggal_mulai' => '2026-06-20',
            'tanggal_selesai' => '2026-06-27',
            'keterangan' => 'Gagal',
        ])->assertRedirect('/login');

        $this->put('/ppdb/tanggal-penting/'.$agenda->id, [
            'nama_agenda' => 'Gagal',
            'tanggal_mulai' => '2026-06-20',
            'tanggal_selesai' => '2026-06-27',
            'keterangan' => 'Gagal',
        ])->assertRedirect('/login');

        $this->delete('/ppdb/tanggal-penting/'.$agenda->id)->assertRedirect('/login');

        $this->assertDatabaseCount('ppdb_tanggal_penting', 1);
    }

    private function buatAgenda(
        string $mulai = '2026-06-20',
        string $selesai = '2026-06-27',
        string $nama = 'Senam Jasmani'
    ): Ppdb_tanggal_penting {
        return Ppdb_tanggal_penting::create([
            'nama_agenda' => $nama,
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'keterangan' => 'Kegiatan rutin hari minggu',
        ]);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }
}
