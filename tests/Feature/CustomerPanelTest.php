<?php

namespace Tests\Feature;

use App\Models\PaketPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_dashboard_dapat_diakses_oleh_pelanggan(): void
    {
        $pelanggan = User::factory()->create([
            'name' => 'Ilham',
            'email' => 'PBB@smk2nkra.sch.id',
            'role' => 'pelanggan',
        ]);

        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Standar 2',
            'kategori' => 'standar 2',
            'harga' => 4400000,
        ]);

        Peminjaman::create([
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'Ilham',
            'email_instansi' => 'PBB@smk2nkra.sch.id',
            'tanggal_mulai' => now()->startOfMonth()->addDays(24),
            'tanggal_selesai' => now()->startOfMonth()->addDays(24)->addHours(12),
            'status' => 'approved_final',
        ]);

        $response = $this->actingAs($pelanggan)->get(route('customer.dashboard'));

        $response->assertOk();
        $response->assertViewIs('Admin.customerPanel.dashboard');
        $response->assertSee('PEMINJAMAN TERVERIFIKASI');
        $response->assertSee('CEK KETERSEDIAAN');
        $response->assertSee('Ilham');
    }

    public function test_customer_paket_peminjaman_dapat_diakses_oleh_pelanggan(): void
    {
        $pelanggan = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Unggulan',
            'kategori' => 'unggulan',
            'harga' => 6000000,
        ]);

        $response = $this->actingAs($pelanggan)->get(route('customer.paket'));

        $response->assertOk();
        $response->assertViewIs('Admin.customerPanel.paket');
        $response->assertSee('Unggulan');
        $response->assertSee('Pilih Paket');
    }

    public function test_customer_cek_peminjaman_riwayat_dapat_diakses_oleh_pelanggan(): void
    {
        $pelanggan = User::factory()->create([
            'name' => 'Ilham',
            'email' => 'PBB@smk2nkra.sch.id',
            'role' => 'pelanggan',
        ]);

        $response = $this->actingAs($pelanggan)->get(route('customer.cek-peminjaman'));

        $response->assertOk();
        $response->assertViewIs('Admin.customerPanel.riwayat');
        $response->assertSee('Daftar Peminjaman');
        $response->assertSee('Pembayaran');
    }

    public function test_customer_profil_dapat_diakses_oleh_pelanggan(): void
    {
        $pelanggan = User::factory()->create([
            'name' => 'Ilham',
            'email' => 'PBB@smk2nkra.sch.id',
            'role' => 'pelanggan',
        ]);

        $response = $this->actingAs($pelanggan)->get(route('customer.profil'));

        $response->assertOk();
        $response->assertViewIs('Admin.customerPanel.profil');
        $response->assertSee('Informasi Akun');
    }

    public function test_pelanggan_mengakses_dashboard_dialihkan_ke_customer_dashboard(): void
    {
        $pelanggan = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $response = $this->actingAs($pelanggan)->get(route('dashboard'));

        $response->assertRedirect(route('customer.dashboard'));
    }

    public function test_pelanggan_yang_sudah_login_mengakses_login_dialihkan_ke_customer_dashboard(): void
    {
        $pelanggan = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $response = $this->actingAs($pelanggan)->get(route('login'));

        $response->assertRedirect(route('customer.dashboard'));
    }

    public function test_guest_diarahkan_ke_login_saat_mengakses_customer_panel(): void
    {
        $response = $this->get(route('customer.dashboard'));

        $response->assertRedirect('/login');
    }

    public function test_customer_riwayat_menampilkan_empty_case_saat_data_kosong(): void
    {
        $pelanggan = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $response = $this->actingAs($pelanggan)->get(route('customer.riwayat'));

        $response->assertOk();
        $response->assertSee('Belum Ada Riwayat Peminjaman');
        $response->assertDontSee('ORD-002');
    }

    public function test_customer_paket_menampilkan_empty_case_saat_data_kosong(): void
    {
        $pelanggan = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $response = $this->actingAs($pelanggan)->get(route('customer.paket'));

        $response->assertOk();
        $response->assertSee('Belum Ada Paket Peminjaman');
        $response->assertDontSee('6.000.000');
    }
}
