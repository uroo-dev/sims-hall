<?php

namespace Tests\Feature;

use App\Models\Jurusan;
use App\Models\Produk;
use App\Models\ProdukUnggulan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProdukUnggulanTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('produk-unggulan.index'))->assertRedirect('/login');
        $this->get(route('produk.index'))->assertRedirect('/login');
    }

    public function test_non_admin_roles_cannot_access_produk_unggulan(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $this->actingAs($guru)->get(route('produk.index'))->assertForbidden();
    }

    public function test_dashboard_can_be_rendered(): void
    {
        ProdukUnggulan::create([
            'judul' => 'Produk Unggulan SMK N 2',
            'deskripsi' => 'Karya siswa',
            'dokumentasi' => '',
        ]);

        Produk::create([
            'kode_produk' => 'PU-001',
            'nama' => 'Robot Line Follower',
            'deskripsi' => 'Robot pengikut garis',
        ]);

        $this->actingAs($this->admin)
            ->get(route('produk-unggulan.index'))
            ->assertOk()
            ->assertSee('Produk Unggulan SMKN 2 Karanganyar')
            ->assertSee('Karya siswa');

        $this->actingAs($this->admin)
            ->get(route('produk.index'))
            ->assertOk()
            ->assertSee('Robot Line Follower')
            ->assertSee('PU-001');
    }

    public function test_dashboard_renders_existing_dokumentasi(): void
    {
        $this->actingAs($this->admin)->put(route('produk-unggulan.update'), [
            'judul' => 'Judul',
            'deskripsi' => 'Deskripsi',
            'dokumentasi' => [
                UploadedFile::fake()->image('satu.jpg'),
                UploadedFile::fake()->image('dua.jpg'),
            ],
        ]);

        [$satu] = ProdukUnggulan::current()->dokumentasi_list;

        $this->actingAs($this->admin)
            ->get(route('produk-unggulan.index'))
            ->assertOk()
            ->assertSee($satu)
            ->assertSee('hapus_dokumentasi[]', escape: false);
    }

    public function test_admin_can_update_landing_page_settings(): void
    {
        $response = $this->actingAs($this->admin)->put(route('produk-unggulan.update'), [
            'judul' => 'Produk Unggulan Terbaru',
            'deskripsi' => 'Deskripsi baru',
            'dokumentasi' => [
                UploadedFile::fake()->image('satu.jpg'),
                UploadedFile::fake()->image('dua.jpg'),
            ],
        ]);

        $response->assertRedirect(route('produk-unggulan.index'));

        $produkUnggulan = ProdukUnggulan::current();

        $this->assertSame('Produk Unggulan Terbaru', $produkUnggulan->judul);
        $this->assertSame('Deskripsi baru', $produkUnggulan->deskripsi);
        $this->assertCount(2, $produkUnggulan->dokumentasi_list);
        $this->assertCount(2, $produkUnggulan->dokumentasi_urls);
    }

    public function test_landing_page_settings_require_judul_and_deskripsi(): void
    {
        $this->actingAs($this->admin)
            ->from(route('produk-unggulan.index'))
            ->put(route('produk-unggulan.update'), ['judul' => '', 'deskripsi' => ''])
            ->assertRedirect(route('produk-unggulan.index'))
            ->assertSessionHasErrors(['judul', 'deskripsi']);
    }

    public function test_existing_dokumentasi_is_kept_when_saving_again(): void
    {
        $this->actingAs($this->admin)->put(route('produk-unggulan.update'), [
            'judul' => 'Judul',
            'deskripsi' => 'Deskripsi',
            'dokumentasi' => [UploadedFile::fake()->image('satu.jpg')],
        ]);

        $lama = ProdukUnggulan::current()->dokumentasi_list[0];

        $this->actingAs($this->admin)->put(route('produk-unggulan.update'), [
            'judul' => 'Judul Baru',
            'deskripsi' => 'Deskripsi Baru',
        ])->assertRedirect(route('produk-unggulan.index'));

        $produkUnggulan = ProdukUnggulan::current();

        $this->assertSame('Judul Baru', $produkUnggulan->judul);
        $this->assertSame([$lama], $produkUnggulan->dokumentasi_list);
        Storage::disk('public')->assertExists($lama);
    }

    public function test_dokumentasi_can_be_removed_one_by_one(): void
    {
        $this->actingAs($this->admin)->put(route('produk-unggulan.update'), [
            'judul' => 'Judul',
            'deskripsi' => 'Deskripsi',
            'dokumentasi' => [
                UploadedFile::fake()->image('satu.jpg'),
                UploadedFile::fake()->image('dua.jpg'),
            ],
        ]);

        [$satu, $dua] = ProdukUnggulan::current()->dokumentasi_list;

        $this->actingAs($this->admin)->put(route('produk-unggulan.update'), [
            'judul' => 'Judul',
            'deskripsi' => 'Deskripsi',
            'hapus_dokumentasi' => [$satu],
        ])->assertRedirect(route('produk-unggulan.index'));

        $produkUnggulan = ProdukUnggulan::current();

        $this->assertSame([$dua], $produkUnggulan->dokumentasi_list);
        Storage::disk('public')->assertMissing($satu);
        Storage::disk('public')->assertExists($dua);
    }

    public function test_dokumentasi_limit_is_enforced(): void
    {
        $this->actingAs($this->admin)
            ->from(route('produk-unggulan.index'))
            ->put(route('produk-unggulan.update'), [
                'judul' => 'Judul',
                'deskripsi' => 'Deskripsi',
                'dokumentasi' => [
                    UploadedFile::fake()->image('satu.jpg'),
                    UploadedFile::fake()->image('dua.jpg'),
                    UploadedFile::fake()->image('tiga.jpg'),
                    UploadedFile::fake()->image('empat.jpg'),
                    UploadedFile::fake()->image('lima.jpg'),
                ],
            ])
            ->assertRedirect(route('produk-unggulan.index'))
            ->assertSessionHasErrors('dokumentasi');

        $this->assertSame(0, ProdukUnggulan::count());
    }

    public function test_admin_can_create_produk_with_generated_kode(): void
    {
        $jurusan = Jurusan::create(['nama' => 'Permesinan', 'deskripsi' => 'Mesin', 'dokumentasi' => '']);

        $response = $this->actingAs($this->admin)->post(route('produk.store'), [
            'nama' => 'Alat Bubut Mini CNC',
            'deskripsi' => 'Karya siswa jurusan permesinan',
            'jurusanID' => $jurusan->jurusanID,
            'dokumentasi' => UploadedFile::fake()->image('bubut.png', 400, 300),
        ]);

        $response->assertRedirect(route('produk.index'));
        $response->assertSessionHas('success');

        $produk = Produk::sole();

        $this->assertMatchesRegularExpression('/^PU-\d{3}$/', $produk->kode_produk);
        $this->assertSame(1, Produk::where('kode_produk', $produk->kode_produk)->count());
        $this->assertTrue($produk->jurusan->is($jurusan));
        Storage::disk('public')->assertExists($produk->dokumentasi);
    }

    public function test_kode_produk_is_generated_sequentially(): void
    {
        Produk::create(['kode_produk' => 'PU-001', 'nama' => 'A', 'deskripsi' => 'A']);
        Produk::create(['kode_produk' => 'PU-002', 'nama' => 'B', 'deskripsi' => 'B']);

        $this->actingAs($this->admin)->post(route('produk.store'), [
            'nama' => 'C',
            'deskripsi' => 'C',
        ])->assertRedirect(route('produk.index'));

        $baru = Produk::where('nama', 'C')->sole();

        $this->assertGreaterThan(2, (int) substr($baru->kode_produk, 3));
        $this->assertSame(1, Produk::where('kode_produk', $baru->kode_produk)->count());
    }

    public function test_creating_produk_validates_input(): void
    {
        $this->actingAs($this->admin)
            ->from(route('produk.index'))
            ->post(route('produk.store'), ['nama' => '', 'deskripsi' => '', 'jurusanID' => 999])
            ->assertRedirect(route('produk.index'))
            ->assertSessionHasErrors(['nama', 'deskripsi', 'jurusanID']);

        $this->assertSame(0, Produk::count());
    }

    public function test_produk_can_be_searched_and_paginated(): void
    {
        Produk::create(['kode_produk' => 'PU-001', 'nama' => 'Robot Line Follower', 'deskripsi' => 'Karya RPL']);
        Produk::create(['kode_produk' => 'PU-002', 'nama' => 'Kain Tenun', 'deskripsi' => 'Karya tekstil']);

        $this->actingAs($this->admin)
            ->get(route('produk.index', ['search' => 'Tenun']))
            ->assertOk()
            ->assertSee('Kain Tenun')
            ->assertDontSee('Robot Line Follower');
    }

    public function test_admin_can_update_produk(): void
    {
        $produk = Produk::create([
            'kode_produk' => 'PU-001',
            'nama' => 'Robot Line Follower',
            'deskripsi' => 'Deskripsi lama',
        ]);

        $this->actingAs($this->admin)
            ->put(route('produk.update', $produk), [
                'nama' => 'Robot Line Follower V2',
                'deskripsi' => 'Deskripsi baru',
            ])
            ->assertRedirect(route('produk.index'));

        $this->assertSame('Robot Line Follower V2', $produk->fresh()->nama);
    }

    public function test_updating_produk_removes_old_dokumentasi_when_replaced(): void
    {
        $produk = Produk::create([
            'kode_produk' => 'PU-001',
            'nama' => 'Robot',
            'deskripsi' => 'Deskripsi',
            'dokumentasi' => UploadedFile::fake()->image('lama.jpg')->store('produk', 'public'),
        ]);

        Storage::disk('public')->assertExists($produk->dokumentasi);

        $pathLama = $produk->dokumentasi;

        $this->actingAs($this->admin)->put(route('produk.update', $produk), [
            'nama' => 'Robot',
            'deskripsi' => 'Deskripsi',
            'dokumentasi' => UploadedFile::fake()->image('baru.jpg'),
        ])->assertRedirect(route('produk.index'));

        $produk->refresh();

        Storage::disk('public')->assertMissing($pathLama);
        Storage::disk('public')->assertExists($produk->dokumentasi);
    }

    public function test_updating_produk_can_delete_dokumentasi(): void
    {
        $produk = Produk::create([
            'kode_produk' => 'PU-001',
            'nama' => 'Robot',
            'deskripsi' => 'Deskripsi',
            'dokumentasi' => UploadedFile::fake()->image('lama.jpg')->store('produk', 'public'),
        ]);

        $path = $produk->dokumentasi;

        $this->actingAs($this->admin)->put(route('produk.update', $produk), [
            'nama' => 'Robot',
            'deskripsi' => 'Deskripsi',
            'hapus_dokumentasi' => '1',
        ])->assertRedirect(route('produk.index'));

        $this->assertNull($produk->fresh()->dokumentasi);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_admin_can_delete_produk(): void
    {
        $produk = Produk::create([
            'kode_produk' => 'PU-001',
            'nama' => 'Robot',
            'deskripsi' => 'Deskripsi',
            'dokumentasi' => UploadedFile::fake()->image('lama.jpg')->store('produk', 'public'),
        ]);

        $this->actingAs($this->admin)
            ->delete(route('produk.destroy', $produk))
            ->assertRedirect(route('produk.index'));

        $this->assertDatabaseMissing('produk', ['produkID' => $produk->produkID]);
        Storage::disk('public')->assertMissing($produk->dokumentasi);
    }

    public function test_edit_page_is_rendered_with_produk_data(): void
    {
        $produk = Produk::create(['kode_produk' => 'PU-001', 'nama' => 'Robot', 'deskripsi' => 'Deskripsi']);

        $this->actingAs($this->admin)
            ->get(route('produk.edit', $produk))
            ->assertOk()
            ->assertSee('Robot')
            ->assertSee('PU-001');
    }

    public function test_landing_page_settings_can_be_deleted(): void
    {
        ProdukUnggulan::create([
            'judul' => 'Judul',
            'deskripsi' => 'Deskripsi',
            'dokumentasi' => UploadedFile::fake()->image('satu.jpg')->store('produk-unggulan', 'public'),
        ]);

        $this->actingAs($this->admin)
            ->delete(route('produk-unggulan.destroy'))
            ->assertRedirect(route('produk-unggulan.index'));

        $this->assertDatabaseCount('produk_unggulan', 0);
        $this->assertEmpty(Storage::disk('public')->files('produk-unggulan'));
    }
}
