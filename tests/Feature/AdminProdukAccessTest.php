<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProdukAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_produk_cannot_access_panel_admin_aula(): void
    {
        $adminProduk = User::factory()->create([
            'role' => 'admin_produk',
            'username' => 'produk_tester',
        ]);

        // Mengakses /dashboard diarahkan langsung ke produk-unggulan.index, bukan panel aula
        $response = $this->actingAs($adminProduk)->get('/dashboard');
        $response->assertRedirect(route('produk-unggulan.index'));

        // Tidak boleh bisa mengakses rute admin aula
        $this->actingAs($adminProduk)->get(route('admin.aula.index'))->assertForbidden();
        $this->actingAs($adminProduk)->get(route('admin.peminjaman.index'))->assertForbidden();
        $this->actingAs($adminProduk)->get(route('admin.paket.index'))->assertForbidden();
        $this->actingAs($adminProduk)->get(route('admin.fasilitas.index'))->assertForbidden();
    }

    public function test_admin_produk_sidebar_shows_only_produk_menus(): void
    {
        $adminProduk = User::factory()->create([
            'role' => 'admin_produk',
            'username' => 'produk_tester_2',
        ]);

        $response = $this->actingAs($adminProduk)->get(route('produk-unggulan.index'));
        $response->assertOk();

        // Harus melihat menu produk unggulan
        $response->assertSee('Dashboard Produk');
        $response->assertSee('Data Produk');

        // Tidak boleh melihat link dashboard panel aula
        $response->assertDontSee(route('dashboard'));
    }
}
