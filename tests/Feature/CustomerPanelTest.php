<?php

namespace Tests\Feature;

use App\Models\PaketPeminjaman;
use App\Models\PaymentConfiguration;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        $response->assertViewIs('Admin.peminjaman.customerPanel.dashboard');
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
            'harga_dp' => 2000000,
        ]);

        $response = $this->actingAs($pelanggan)->get(route('customer.paket'));

        $response->assertOk();
        $response->assertViewIs('Admin.peminjaman.customerPanel.paket');
        $response->assertSee('Unggulan');
        $response->assertSee('Pilih Paket');
        $response->assertSee('Deposit (DP):');
        $response->assertSee('2.000.000');
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
        $response->assertViewIs('Admin.peminjaman.customerPanel.riwayat');
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
        $response->assertViewIs('Admin.peminjaman.customerPanel.profil');
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

    public function test_pelanggan_dapat_mengakses_form_pengajuan_peminjaman(): void
    {
        $pelanggan = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Premium Aula',
            'kategori' => 'unggulan',
            'harga' => 5000000,
            'harga_dp' => 1500000,
        ]);

        $response = $this->actingAs($pelanggan)->get(route('customer.peminjaman.create', ['paket_id' => $paket->id]));

        $response->assertOk();
        $response->assertViewIs('Admin.peminjaman.customerPanel.pengajuan');
        $response->assertSee('Pengajuan Peminjaman Aula');
        $response->assertSee('Paket Premium Aula');
        $response->assertSee('5.000.000');
    }

    public function test_pelanggan_dapat_mengajukan_peminjaman_dan_diarahkan_ke_halaman_pembayaran(): void
    {
        Storage::fake('public');

        PaymentConfiguration::create([
            'nama_sekolah' => 'SMK Negeri 2 Karanganyar',
            'bank_utama' => 'Bank Jateng',
            'norek_utama' => '1234567890',
            'atas_nama_utama' => 'Bendahara Aula SMK 2',
            'jatuh_tempo_dp_jam' => 24,
            'jatuh_tempo_pelunasan_jam' => 72,
        ]);

        $pelanggan = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@instansi.com',
            'role' => 'pelanggan',
        ]);

        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Pernikahan',
            'kategori' => 'unggulan',
            'harga' => 8000000,
            'harga_dp' => 2500000,
        ]);

        $suratFile = UploadedFile::fake()->create('surat-pengajuan.pdf', 300, 'application/pdf');

        $tglMulai = now()->addDays(5)->format('Y-m-d\TH:i');
        $tglSelesai = now()->addDays(5)->addHours(8)->format('Y-m-d\TH:i');

        $response = $this->actingAs($pelanggan)->post(route('customer.peminjaman.store'), [
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'Budi Santoso',
            'email_instansi' => 'budi@instansi.com',
            'tanggal_mulai' => $tglMulai,
            'tanggal_selesai' => $tglSelesai,
            'surat_pengantar' => $suratFile,
            'catatan' => 'Mohon sediakan sound system tambahan.',
        ]);

        $peminjaman = Peminjaman::where('email_instansi', 'budi@instansi.com')->first();
        $this->assertNotNull($peminjaman);
        $this->assertEquals('pending', $peminjaman->status);

        $pembayaran = Pembayaran::where('peminjaman_id', $peminjaman->id)->first();
        $this->assertNotNull($pembayaran);
        $this->assertEquals(8000000, $pembayaran->total_tagihan);
        $this->assertNotNull($pembayaran->jatuh_tempo_dp);

        $response->assertRedirect(route('customer.pembayaran.show', $pembayaran));
    }

    public function test_pengajuan_peminjaman_gagal_jika_jadwal_bentrok(): void
    {
        Storage::fake('public');

        $pelanggan = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Seminar',
            'kategori' => 'standar 1',
            'harga' => 3000000,
        ]);

        // Buat peminjaman approved
        Peminjaman::create([
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'Peminjam Pertama',
            'email_instansi' => 'peminjam1@instansi.com',
            'tanggal_mulai' => now()->addDays(7)->setTime(8, 0),
            'tanggal_selesai' => now()->addDays(7)->setTime(16, 0),
            'status' => 'approved_final',
        ]);

        $suratFile = UploadedFile::fake()->create('surat.pdf', 200, 'application/pdf');

        $response = $this->actingAs($pelanggan)->post(route('customer.peminjaman.store'), [
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'Peminjam Kedua',
            'email_instansi' => 'peminjam2@instansi.com',
            'tanggal_mulai' => now()->addDays(7)->setTime(10, 0)->format('Y-m-d\TH:i'),
            'tanggal_selesai' => now()->addDays(7)->setTime(14, 0)->format('Y-m-d\TH:i'),
            'surat_pengantar' => $suratFile,
        ]);

        $response->assertSessionHasErrors(['tanggal_mulai']);
        $this->assertDatabaseMissing('peminjamans', [
            'email_instansi' => 'peminjam2@instansi.com',
        ]);
    }

    public function test_pelanggan_dapat_melihat_halaman_pembayaran_dengan_informasi_rekening_dan_countdown(): void
    {
        $config = PaymentConfiguration::create([
            'nama_sekolah' => 'SMK Negeri 2 Karanganyar',
            'bank_utama' => 'Bank Jateng',
            'norek_utama' => '1234567890',
            'atas_nama_utama' => 'Bendahara Aula SMK 2',
            'jatuh_tempo_dp_jam' => 24,
            'jatuh_tempo_pelunasan_jam' => 72,
        ]);

        $pelanggan = User::factory()->create([
            'email' => 'customer@test.com',
            'role' => 'pelanggan',
        ]);

        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Reguler',
            'kategori' => 'standar 1',
            'harga' => 3000000,
            'harga_dp' => 1000000,
        ]);

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'Customer Test',
            'email_instansi' => 'customer@test.com',
            'tanggal_mulai' => now()->addDays(3),
            'tanggal_selesai' => now()->addDays(3)->addHours(6),
            'status' => 'draft',
        ]);

        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'PAY-TEST-001',
            'total_tagihan' => 3000000,
            'sisa_tagihan' => 3000000,
            'status_pembayaran' => 'pending',
            'jatuh_tempo_dp' => now()->addHours(24),
        ]);

        $response = $this->actingAs($pelanggan)->get(route('customer.pembayaran.show', $pembayaran));

        $response->assertOk();
        $response->assertViewIs('Admin.peminjaman.customerPanel.pembayaran');
        $response->assertSee('Bank Jateng');
        $response->assertSee('1234567890');
        $response->assertSee('Bendahara Aula SMK 2');
        $response->assertSee('PAY-TEST-001');
        $response->assertSee('cd-hours');
        $response->assertSee('updateCountdown');
    }

    public function test_pelanggan_dapat_mengirim_bukti_pembayaran_dp(): void
    {
        Storage::fake('public');

        PaymentConfiguration::create([
            'nama_sekolah' => 'SMK Negeri 2 Karanganyar',
            'bank_utama' => 'Bank Jateng',
            'norek_utama' => '1234567890',
            'atas_nama_utama' => 'Bendahara Aula SMK 2',
        ]);

        $pelanggan = User::factory()->create([
            'email' => 'customer@test.com',
            'role' => 'pelanggan',
        ]);

        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Reguler',
            'kategori' => 'standar 1',
            'harga' => 3000000,
            'harga_dp' => 1000000,
        ]);

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'Customer Test',
            'email_instansi' => 'customer@test.com',
            'tanggal_mulai' => now()->addDays(3),
            'tanggal_selesai' => now()->addDays(3)->addHours(6),
            'status' => 'draft',
        ]);

        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'PAY-TEST-002',
            'total_tagihan' => 3000000,
            'sisa_tagihan' => 3000000,
            'status_pembayaran' => 'pending',
            'jatuh_tempo_dp' => now()->addHours(24),
        ]);

        $bukti = UploadedFile::fake()->image('bukti_transfer.jpg');

        $response = $this->actingAs($pelanggan)->post(route('customer.pembayaran.bayar', $pembayaran), [
            'tipe_pembayaran' => 'dp',
            'metode' => 'transfer_bank',
            'bank_tujuan' => 'Bank Jateng',
            'bank_pengirim' => 'BCA',
            'norek_pengirim' => '987654321',
            'atas_nama_pengirim' => 'Customer Pengirim',
            'jumlah_bayar' => 1000000,
            'tanggal_bayar' => now()->format('Y-m-d\TH:i'),
            'bukti_pembayaran' => $bukti,
        ]);

        $response->assertRedirect(route('customer.pembayaran.show', $pembayaran));

        $this->assertDatabaseHas('detail_pembayarans', [
            'pembayaran_id' => $pembayaran->id,
            'tipe_pembayaran' => 'dp',
            'jumlah_bayar' => 1000000,
            'bank_tujuan' => 'Bank Jateng',
            'norek_tujuan' => '1234567890',
            'atas_nama_pengirim' => 'Customer Pengirim',
            'status' => 'pending',
        ]);

        $peminjaman->refresh();
        $this->assertEquals('pending', $peminjaman->status);
    }

    public function test_pelanggan_dapat_melihat_opsi_qris_dan_membayar_via_qris(): void
    {
        Storage::fake('public');

        PaymentConfiguration::create([
            'nama_sekolah' => 'SMK Negeri 2 Karanganyar',
            'bank_utama' => 'Bank Jateng',
            'norek_utama' => '1234567890',
            'atas_nama_utama' => 'Bendahara Aula SMK 2',
            'qris_merchant' => 'SMKN 2 KRA AULA OFFICIAL',
        ]);

        $pelanggan = User::factory()->create([
            'email' => 'customer_qris@test.com',
            'role' => 'pelanggan',
        ]);

        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Aula QRIS',
            'kategori' => 'standar 1',
            'harga' => 2000000,
            'harga_dp' => 500000,
        ]);

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'Customer QRIS',
            'email_instansi' => 'customer_qris@test.com',
            'tanggal_mulai' => now()->addDays(2),
            'tanggal_selesai' => now()->addDays(2)->addHours(4),
            'status' => 'draft',
        ]);

        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'PAY-QRIS-001',
            'total_tagihan' => 2000000,
            'sisa_tagihan' => 2000000,
            'status_pembayaran' => 'pending',
            'jatuh_tempo_dp' => now()->addHours(24),
        ]);

        $resView = $this->actingAs($pelanggan)->get(route('customer.pembayaran.show', $pembayaran));
        $resView->assertOk();
        $resView->assertSee('QRIS Resmi Sekolah');
        $resView->assertSee('SMKN 2 KRA AULA OFFICIAL');
        $resView->assertSee('id="qrisInfoBox"', false);

        $bukti = UploadedFile::fake()->image('bukti_qris.jpg');

        $response = $this->actingAs($pelanggan)->post(route('customer.pembayaran.bayar', $pembayaran), [
            'tipe_pembayaran' => 'dp',
            'metode' => 'transfer_bank',
            'bank_tujuan' => 'QRIS',
            'bank_pengirim' => 'GoPay',
            'norek_pengirim' => '08123456789',
            'atas_nama_pengirim' => 'Customer QRIS User',
            'jumlah_bayar' => 500000,
            'tanggal_bayar' => now()->format('Y-m-d\TH:i'),
            'bukti_pembayaran' => $bukti,
        ]);

        $response->assertRedirect(route('customer.pembayaran.show', $pembayaran));

        $this->assertDatabaseHas('detail_pembayarans', [
            'pembayaran_id' => $pembayaran->id,
            'tipe_pembayaran' => 'dp',
            'jumlah_bayar' => 500000,
            'bank_tujuan' => 'QRIS',
            'norek_tujuan' => 'SMKN 2 KRA AULA OFFICIAL',
            'bank_pengirim' => 'GoPay',
            'status' => 'pending',
        ]);
    }
}
