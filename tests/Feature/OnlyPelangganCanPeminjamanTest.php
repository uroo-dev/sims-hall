<?php

namespace Tests\Feature;

use App\Models\PaketPeminjaman;
use App\Models\PaymentConfiguration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnlyPelangganCanPeminjamanTest extends TestCase
{
    use RefreshDatabase;

    private PaketPeminjaman $paket;

    protected function setUp(): void
    {
        parent::setUp();

        PaymentConfiguration::create([
            'bank_utama' => 'Bank Jateng',
            'norek_utama' => '1234567890',
            'atas_nama_utama' => 'SMK N 2 Karanganyar',
            'minimal_hari_booking' => 3,
            'jatuh_tempo_dp_jam' => 24,
            'jatuh_tempo_pelunasan_jam' => 48,
            'is_active' => true,
        ]);

        $this->paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Pernikahan Standar',
            'durasi' => '8 Jam',
            'kategori' => 'standar 1',
            'harga' => 5000000,
            'harga_dp' => 2000000,
        ]);
    }

    public function test_guest_cannot_access_create_peminjaman_and_is_redirected_to_login(): void
    {
        $response = $this->get(route('customer.peminjaman.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_with_role_pelanggan_can_access_peminjaman_create_form(): void
    {
        $pelanggan = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $response = $this->actingAs($pelanggan)->get(route('customer.peminjaman.create'));

        $response->assertStatus(200);
        $response->assertViewIs('Admin.peminjaman.customerPanel.pengajuan');
    }

    public function test_user_with_role_pelanggan_can_submit_peminjaman(): void
    {
        $pelanggan = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'role' => 'pelanggan',
        ]);

        $mulai = Carbon::now()->addDays(5)->format('Y-m-d H:i');
        $selesai = Carbon::now()->addDays(5)->addHours(8)->format('Y-m-d H:i');

        $response = $this->actingAs($pelanggan)->post(route('customer.peminjaman.store'), [
            'paket_peminjaman_id' => $this->paket->id,
            'nama' => 'Budi Santoso',
            'email_instansi' => 'budi@example.com',
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'catatan' => 'Acara seminar keluarga',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('peminjamans', [
            'paket_peminjaman_id' => $this->paket->id,
            'nama' => 'Budi Santoso',
            'email_instansi' => 'budi@example.com',
        ]);
    }

    public function test_non_pelanggan_roles_are_forbidden_from_accessing_create_form(): void
    {
        $forbiddenRoles = ['admin_aula', 'super_admin', 'guru', 'bkk', 'admin_master', 'kepala_sekolah', 'user'];

        foreach ($forbiddenRoles as $role) {
            $user = User::factory()->create(['role' => $role]);

            $response = $this->actingAs($user)->get(route('customer.peminjaman.create'));

            $response->assertStatus(403);
        }
    }

    public function test_non_pelanggan_roles_are_forbidden_from_submitting_peminjaman(): void
    {
        $forbiddenRoles = ['admin_aula', 'super_admin', 'guru', 'bkk', 'admin_master', 'kepala_sekolah', 'user'];

        $mulai = Carbon::now()->addDays(5)->format('Y-m-d H:i');
        $selesai = Carbon::now()->addDays(5)->addHours(8)->format('Y-m-d H:i');

        foreach ($forbiddenRoles as $role) {
            $user = User::factory()->create(['role' => $role]);

            $response = $this->actingAs($user)->post(route('customer.peminjaman.store'), [
                'paket_peminjaman_id' => $this->paket->id,
                'nama' => 'User Non Pelanggan',
                'email_instansi' => 'nonpelanggan@example.com',
                'tanggal_mulai' => $mulai,
                'tanggal_selesai' => $selesai,
            ]);

            $response->assertStatus(403);
        }

        $this->assertDatabaseMissing('peminjamans', [
            'nama' => 'User Non Pelanggan',
        ]);
    }
}
