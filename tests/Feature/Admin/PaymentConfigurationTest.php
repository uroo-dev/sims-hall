<?php

namespace Tests\Feature\Admin;

use App\Models\PaymentConfiguration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentConfigurationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_payment_configuration(): void
    {
        $response = $this->get(route('admin.payment-configuration.index'));

        $response->assertRedirect('/login');
    }

    public function test_regular_admin_cannot_access_payment_configuration(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.payment-configuration.index'));

        $response->assertForbidden();
    }

    public function test_pelanggan_cannot_access_payment_configuration(): void
    {
        $pelanggan = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $response = $this->actingAs($pelanggan)->get(route('admin.payment-configuration.index'));

        $response->assertForbidden();
    }

    public function test_super_admin_can_view_payment_configuration(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        $response = $this->actingAs($superAdmin)->get(route('admin.payment-configuration.index'));

        $response->assertOk();
        $response->assertViewIs('Admin.peminjaman.paymentConfiguration.index');
        $response->assertSee('Konfigurasi Pembayaran & Rekening Sekolah');
        $response->assertSee('Bank Jateng');
        $response->assertSee('Batas Waktu Transfer DP (Jam)');
    }

    public function test_super_duper_admin_can_view_payment_configuration(): void
    {
        $superDuperAdmin = User::factory()->create([
            'role' => 'super_duper_admin',
        ]);

        $response = $this->actingAs($superDuperAdmin)->get(route('admin.payment-configuration.index'));

        $response->assertOk();
    }

    public function test_super_admin_can_update_payment_configuration(): void
    {
        Storage::fake('public');

        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        $fakeQris = UploadedFile::fake()->image('qris.png', 400, 400);

        $response = $this->actingAs($superAdmin)->put(route('admin.payment-configuration.update'), [
            'bank_utama' => 'Bank Mandiri',
            'norek_utama' => '1380012345678',
            'atas_nama_utama' => 'SMKN 2 KARANGANYAR',
            'bank_alternatif_1' => 'Bank BNI',
            'norek_alternatif_1' => '987654321',
            'atas_nama_alternatif_1' => 'SMKN 2 KRA',
            'bank_alternatif_2' => 'Bank BCA',
            'norek_alternatif_2' => '543216789',
            'atas_nama_alternatif_2' => 'SMK NEGERI 2 KRA',
            'qris_merchant' => 'AULA SMKN 2 KARANGANYAR',
            'qris_image' => $fakeQris,
            'jatuh_tempo_dp_jam' => 12,
            'jatuh_tempo_pelunasan_jam' => 72,
            'minimal_hari_booking' => 4,
            'instruksi_pembayaran' => 'Transfer tepat waktu dan simpan struk transfer.',
            'is_active' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payment_configurations', [
            'bank_utama' => 'Bank Mandiri',
            'norek_utama' => '1380012345678',
            'bank_alternatif_1' => 'Bank BNI',
            'bank_alternatif_2' => 'Bank BCA',
            'jatuh_tempo_dp_jam' => 12,
            'jatuh_tempo_pelunasan_jam' => 72,
            'minimal_hari_booking' => 4,
            'qris_merchant' => 'AULA SMKN 2 KARANGANYAR',
        ]);

        $config = PaymentConfiguration::current();
        $this->assertNotNull($config->qris_image);
        Storage::disk('public')->assertExists($config->qris_image);
    }

    public function test_super_admin_gagal_update_jika_minimal_hari_booking_kurang_dari_atau_sama_dengan_tenggat_pelunasan(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        // Pelunasan 48 jam (= 2 hari), tapi minimal_hari_booking hanya diisi 2 hari (2*24 = 48 <= 48)
        $response = $this->actingAs($superAdmin)->put(route('admin.payment-configuration.update'), [
            'bank_utama' => 'Bank Jateng',
            'norek_utama' => '123456789',
            'atas_nama_utama' => 'Bendahara',
            'jatuh_tempo_dp_jam' => 24,
            'jatuh_tempo_pelunasan_jam' => 48,
            'minimal_hari_booking' => 2,
        ]);

        $response->assertSessionHasErrors(['minimal_hari_booking']);

        // Pelunasan 72 jam (= 3 hari), tapi minimal_hari_booking hanya diisi 1 hari
        $response2 = $this->actingAs($superAdmin)->put(route('admin.payment-configuration.update'), [
            'bank_utama' => 'Bank Jateng',
            'norek_utama' => '123456789',
            'atas_nama_utama' => 'Bendahara',
            'jatuh_tempo_dp_jam' => 24,
            'jatuh_tempo_pelunasan_jam' => 72,
            'minimal_hari_booking' => 1,
        ]);

        $response2->assertSessionHasErrors(['minimal_hari_booking']);
    }

    public function test_super_admin_sees_payment_configuration_on_dashboard(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        $response = $this->actingAs($superAdmin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Konfigurasi Rekening & Pembayaran Sekolah');
        $response->assertSee('Kelola Konfigurasi');
        $response->assertSee('Bank Jateng');
    }

    public function test_regular_admin_does_not_see_payment_configuration_section_on_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertDontSee('SUPER ADMIN PAYMENT CONFIGURATION SECTION');
        $response->assertDontSee('Khusus Super Admin: Pengaturan rekening transfer tujuan');
    }
}
