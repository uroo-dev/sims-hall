<?php

namespace Tests\Feature;

use App\Models\TataTertib;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KesiswaanTataTertibTest extends TestCase
{
    use RefreshDatabase;

    private User $adminKesiswaan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminKesiswaan = User::factory()->create([
            'role' => 'admin_kesiswaan',
        ]);
    }

    public function test_can_store_tata_tertib_without_column_errors(): void
    {
        $response = $this->actingAs($this->adminKesiswaan)
            ->post(route('admin.kesiswaan.tata-tertib.store'), [
                'judul' => 'Pakai sepatu hitam',
                'deskripsi' => 'Wajib pantofel',
            ]);

        $response->assertRedirect(route('admin.kesiswaan.tata-tertib.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tata_tertib', [
            'judul' => 'Pakai sepatu hitam',
            'deskripsi' => 'Wajib pantofel',
        ]);
    }

    public function test_can_update_tata_tertib(): void
    {
        $tataTertib = TataTertib::create([
            'judul' => 'Aturan Awal',
            'deskripsi' => 'Deskripsi Awal',
        ]);

        $response = $this->actingAs($this->adminKesiswaan)
            ->put(route('admin.kesiswaan.tata-tertib.update', $tataTertib->tata_tertibID), [
                'judul' => 'Aturan Diperbarui',
                'deskripsi' => 'Deskripsi Baru',
            ]);

        $response->assertRedirect(route('admin.kesiswaan.tata-tertib.index'));
        $this->assertDatabaseHas('tata_tertib', [
            'tata_tertibID' => $tataTertib->tata_tertibID,
            'judul' => 'Aturan Diperbarui',
            'deskripsi' => 'Deskripsi Baru',
        ]);
    }

    public function test_can_delete_tata_tertib(): void
    {
        $tataTertib = TataTertib::create([
            'judul' => 'Aturan Dihapus',
            'deskripsi' => 'Deskripsi',
        ]);

        $response = $this->actingAs($this->adminKesiswaan)
            ->delete(route('admin.kesiswaan.tata-tertib.destroy', $tataTertib->tata_tertibID));

        $response->assertRedirect(route('admin.kesiswaan.tata-tertib.index'));
        $this->assertDatabaseMissing('tata_tertib', [
            'tata_tertibID' => $tataTertib->tata_tertibID,
        ]);
    }

    public function test_can_store_ekstrakurikuler(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->adminKesiswaan)
            ->post(route('admin.kesiswaan.ekstrakurikuler.store'), [
                'nama' => 'Pramuka Garuda',
                'sekolah' => 'SMKN 2 Karanganyar',
                'deskripsi' => 'Kegiatan kepramukaan',
                'logo' => UploadedFile::fake()->image('pramuka.png'),
            ]);

        $response->assertRedirect(route('admin.kesiswaan.ekstrakurikuler.index'));
        $this->assertDatabaseHas('ekstrakurikuler', [
            'nama' => 'Pramuka Garuda',
        ]);
    }

    public function test_public_kesiswaan_page_renders_successfully(): void
    {
        TataTertib::create([
            'judul' => 'Aturan Seragam',
            'deskripsi' => 'Wajib rapi',
        ]);

        $response = $this->get(route('kesiswaan'));
        $response->assertOk();
        $response->assertSee('Aturan Seragam');
    }

    public function test_public_kesiswaan_only_shows_top_3_rules_in_accordions(): void
    {
        TataTertib::create(['judul' => 'Aturan 1', 'deskripsi' => 'Deskripsi 1']);
        TataTertib::create(['judul' => 'Aturan 2', 'deskripsi' => 'Deskripsi 2']);
        TataTertib::create(['judul' => 'Aturan 3', 'deskripsi' => 'Deskripsi 3']);
        TataTertib::create(['judul' => 'Aturan 4', 'deskripsi' => 'Deskripsi 4']);
        TataTertib::create(['judul' => 'Aturan 5', 'deskripsi' => 'Deskripsi 5']);

        $response = $this->get(route('kesiswaan'));
        $response->assertOk();
        $response->assertSee('Aturan 1');
        $response->assertSee('Aturan 2');
        $response->assertSee('Aturan 3');
        $response->assertDontSee('Aturan 4');
        $response->assertDontSee('Aturan 5');
    }
}
