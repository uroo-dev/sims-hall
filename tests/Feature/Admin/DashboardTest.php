<?php

namespace Tests\Feature\Admin;

use App\Models\Facility;
use App\Models\Fitur;
use App\Models\PaketPeminjaman;
use App\Models\PaymentConfiguration;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
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

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_pelanggan_is_redirected_to_customer_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'pelanggan']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('customer.dashboard'));
    }

    public function test_kepala_sekolah_is_redirected_to_kepala_sekolah_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'kepala_sekolah']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('kepala-sekolah.dashboard'));
    }

    public function test_admin_aula_sees_dynamic_metrics_and_recent_peminjaman(): void
    {
        $admin = $this->createAdminAula();

        // Seed facilities
        Facility::create(['judul' => 'Sound System 5000W', 'deskripsi' => 'Fasilitas audio']);
        Facility::create(['judul' => 'Videotron P2.5', 'deskripsi' => 'Layar LED']);

        // Seed paket
        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Platinum',
            'kategori' => 'unggulan',
            'harga' => 5000000,
            'harga_dp' => 2000000,
        ]);

        // Seed peminjaman
        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'Dinas Pendidikan',
            'email_instansi' => 'disdik@jateng.go.id',
            'tanggal_mulai' => now()->addDays(5),
            'tanggal_selesai' => now()->addDays(5)->addHours(4),
            'status' => 'approved_final',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertOk();
        $response->assertViewIs('Admin.dashboard');
        $response->assertViewHas('peminjamanTerverifikasiCount', 1);
        $response->assertViewHas('paketCount', 1);
        $response->assertViewHas('facilityCount', 2);
        $response->assertSee('Dinas Pendidikan');
        $response->assertSee('disdik@jateng.go.id');
        $response->assertSee('Paket Platinum');
    }

    public function test_super_admin_sees_payment_configuration_on_dashboard(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        PaymentConfiguration::current()->update([
            'bank_utama' => 'Bank Mandiri',
            'norek_utama' => '1380009988771',
        ]);

        $response = $this->actingAs($superAdmin)->get('/dashboard');

        $response->assertOk();
        $response->assertViewHas('isSuperAdmin', true);
        $response->assertSee('Bank Mandiri');
        $response->assertSee('1380009988771');
    }

    public function test_admin_aula_sees_availability_calendar_on_dashboard(): void
    {
        $admin = $this->createAdminAula();

        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Pernikahan Gold',
            'kategori' => 'unggulan',
            'harga' => 6000000,
        ]);

        $tanggalAcara = now()->startOfMonth()->addDays(12);

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'Keluarga Budi Santoso',
            'email_instansi' => 'budi@keluarga.id',
            'tanggal_mulai' => $tanggalAcara->copy()->setTime(8, 0),
            'tanggal_selesai' => $tanggalAcara->copy()->setTime(17, 0),
            'status' => 'approved_final',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard?month='.$tanggalAcara->month.'&year='.$tanggalAcara->year);

        $response->assertOk();
        $response->assertViewHas('calendarDate');
        $response->assertViewHas('bookedDays');
        $response->assertSee('CEK KETERSEDIAAN AULA');
        $response->assertSee('Terpakai');
        $response->assertSee('Tersedia');
        $response->assertSee('Keluarga Budi Santoso');
        $response->assertSee('Paket Pernikahan Gold');
    }
}
