<?php

namespace Tests\Feature\Admin;

use App\Models\DetailPembayaran;
use App\Models\Fitur;
use App\Models\PaketPeminjaman;
use App\Models\PaymentConfiguration;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPeminjamanTest extends TestCase
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

    private function createCustomerUser(string $email = 'pemohon@instansi.com'): User
    {
        return User::factory()->create([
            'name' => 'Instansi Pemohon',
            'email' => $email,
            'role' => 'pelanggan',
        ]);
    }

    private function createPeminjamanDanTagihan(string $statusPeminjaman = 'pending', string $statusPembayaran = 'pending', float $terbayar = 0): array
    {
        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Aula Pernikahan & Seminar',
            'kategori' => 'standar 1',
            'harga' => 5000000,
            'harga_dp' => 1500000,
            'deskripsi' => 'Paket aula dengan sound system dan AC',
        ]);

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'Instansi Pemohon',
            'email_instansi' => 'pemohon@instansi.com',
            'tanggal_mulai' => now()->addDays(7)->setTime(8, 0),
            'tanggal_selesai' => now()->addDays(7)->setTime(16, 0),
            'catatan' => 'Acara seminar teknologi sekolah',
            'status' => $statusPeminjaman,
        ]);

        $config = PaymentConfiguration::current();
        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'INV-'.date('Ym').'-'.str_pad((string) $peminjaman->id, 4, '0', STR_PAD_LEFT),
            'total_tagihan' => 5000000,
            'total_terbayar' => $terbayar,
            'total_refund' => 0,
            'sisa_tagihan' => max(0, 5000000 - $terbayar),
            'status_pembayaran' => $statusPembayaran,
            'jatuh_tempo_dp' => now()->addHours($config->jatuh_tempo_dp_jam),
            'jatuh_tempo_pelunasan' => now()->addHours($config->jatuh_tempo_pelunasan_jam),
        ]);

        return [$peminjaman, $pembayaran, $paket];
    }

    public function test_guest_is_redirected_from_admin_peminjaman_index(): void
    {
        $response = $this->get('/admin/peminjaman');
        $response->assertRedirect('/login');
    }

    public function test_user_without_aula_fitur_cannot_access_peminjaman(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        Fitur::create(['user_id' => $user->id, 'nama_fitur' => 'kesiswaan']);

        $response = $this->actingAs($user)->get('/admin/peminjaman');
        $response->assertForbidden();
    }

    public function test_admin_aula_can_view_peminjaman_index_and_filter(): void
    {
        $admin = $this->createAdminAula();
        [$peminjaman, $pembayaran] = $this->createPeminjamanDanTagihan();

        $response = $this->actingAs($admin)->get('/admin/peminjaman');

        $response->assertOk();
        $response->assertViewIs('Admin.peminjaman.index');
        $response->assertSee('Instansi Pemohon');
        $response->assertSee($pembayaran->kode_pembayaran);

        // Filter status
        $filterResponse = $this->actingAs($admin)->get('/admin/peminjaman?status=pending');
        $filterResponse->assertOk();
        $filterResponse->assertSee('Instansi Pemohon');
    }

    public function test_admin_aula_can_view_peminjaman_detail(): void
    {
        $admin = $this->createAdminAula();
        [$peminjaman, $pembayaran] = $this->createPeminjamanDanTagihan();

        $response = $this->actingAs($admin)->get('/admin/peminjaman/'.$peminjaman->id);

        $response->assertOk();
        $response->assertViewIs('Admin.peminjaman.show');
        $response->assertSee($peminjaman->nama);
        $response->assertSee($peminjaman->email_instansi);
        $response->assertSee($pembayaran->kode_pembayaran);
    }

    public function test_admin_can_approve_peminjaman_and_verifies_pending_payment(): void
    {
        $admin = $this->createAdminAula();
        [$peminjaman, $pembayaran] = $this->createPeminjamanDanTagihan('pending', 'pending', 0);

        // Simulasikan ada transaksi DP berstatus pending
        $detail = DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'kode_transaksi' => 'TRX-DP-001',
            'tipe_pembayaran' => 'dp',
            'jumlah_bayar' => 1500000,
            'metode' => 'transfer',
            'bank_tujuan' => 'Bank Jateng',
            'bank_pengirim' => 'BCA',
            'norek_pengirim' => '12345678',
            'atas_nama_pengirim' => 'Pemohon',
            'status' => 'pending',
            'tanggal_bayar' => now(),
        ]);

        $response = $this->actingAs($admin)->post('/admin/peminjaman/'.$peminjaman->id.'/approve', [
            'catatan_approval' => 'Pengajuan aula disetujui, jadwal aman.',
        ]);

        $response->assertRedirect('/admin/peminjaman/'.$peminjaman->id);
        $response->assertSessionHas('success');

        $peminjaman->refresh();
        $pembayaran->refresh();
        $detail->refresh();

        $this->assertEquals('approved_1', $peminjaman->status);
        $this->assertEquals('verified', $detail->status);
        $this->assertEquals($admin->id, $detail->diverifikasi_oleh);
        $this->assertEquals(1500000, $pembayaran->total_terbayar);
        $this->assertEquals('partial', $pembayaran->status_pembayaran);
        $this->assertDatabaseHas('persetujuans', [
            'peminjaman_id' => $peminjaman->id,
            'approver_id' => $admin->id,
            'status' => 'approved',
        ]);
    }

    public function test_admin_cannot_approve_peminjaman_if_schedule_conflicts_with_already_approved_peminjaman(): void
    {
        $admin = $this->createAdminAula();
        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Aula',
            'kategori' => 'standar 1',
            'harga' => 5000000,
        ]);

        // Peminjaman 1: Sudah disetujui (approved_1)
        $peminjamanApproved = Peminjaman::create([
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'Instansi Pertama',
            'email_instansi' => 'pertama@instansi.com',
            'tanggal_mulai' => now()->addDays(7)->setTime(8, 0),
            'tanggal_selesai' => now()->addDays(7)->setTime(16, 0),
            'status' => 'approved_1',
        ]);

        // Peminjaman 2: Masih pending, tetapi rentang waktu bentrok dengan Peminjaman 1
        $peminjamanPending = Peminjaman::create([
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'Instansi Kedua',
            'email_instansi' => 'kedua@instansi.com',
            'tanggal_mulai' => now()->addDays(7)->setTime(10, 0),
            'tanggal_selesai' => now()->addDays(7)->setTime(14, 0),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post('/admin/peminjaman/'.$peminjamanPending->id.'/approve', [
            'catatan_approval' => 'Setuju',
        ]);

        $response->assertSessionHas('error');
        $peminjamanPending->refresh();
        $this->assertEquals('pending', $peminjamanPending->status);
        $this->assertDatabaseMissing('persetujuans', [
            'peminjaman_id' => $peminjamanPending->id,
        ]);
    }

    public function test_admin_reject_peminjaman_requires_alasan(): void
    {
        $admin = $this->createAdminAula();
        [$peminjaman] = $this->createPeminjamanDanTagihan();

        $response = $this->actingAs($admin)->post('/admin/peminjaman/'.$peminjaman->id.'/reject', [
            'alasan_penolakan' => '',
        ]);

        $response->assertSessionHasErrors('alasan_penolakan');
    }

    public function test_admin_reject_with_successful_payment_triggers_refund_pending(): void
    {
        $admin = $this->createAdminAula();
        // Buat peminjaman dengan pembayaran berhasil (DP 1.500.000 terbayar)
        [$peminjaman, $pembayaran] = $this->createPeminjamanDanTagihan('pending', 'partial', 1500000);

        DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'kode_transaksi' => 'TRX-DP-001',
            'tipe_pembayaran' => 'dp',
            'jumlah_bayar' => 1500000,
            'status' => 'verified',
            'tanggal_bayar' => now(),
        ]);

        $response = $this->actingAs($admin)->post('/admin/peminjaman/'.$peminjaman->id.'/reject', [
            'alasan_penolakan' => 'Aula digunakan untuk gladi bersih wisuda sekolah pada tanggal tersebut.',
        ]);

        $response->assertRedirect('/admin/peminjaman/'.$peminjaman->id);
        $response->assertSessionHas('success');

        $peminjaman->refresh();
        $pembayaran->refresh();

        $this->assertEquals('rejected', $peminjaman->status);
        $this->assertEquals('refund_pending', $pembayaran->status_pembayaran);
        $this->assertEquals(1500000, $pembayaran->total_refund);

        // Draft detail refund otomatis terbuat
        $this->assertDatabaseHas('detail_pembayarans', [
            'pembayaran_id' => $pembayaran->id,
            'tipe_pembayaran' => 'refund',
            'jumlah_bayar' => 1500000,
            'status' => 'pending',
        ]);
    }

    public function test_full_refund_flow_user_rekening_admin_upload_and_user_confirm(): void
    {
        Storage::fake('public');

        $admin = $this->createAdminAula();
        $customer = $this->createCustomerUser('pemohon@instansi.com');

        [$peminjaman, $pembayaran] = $this->createPeminjamanDanTagihan('rejected', 'refund_pending', 1500000);
        $pembayaran->update(['total_refund' => 1500000]);

        // 1. Pemohon mengisi nomor rekening pengembalian dana
        $responseRekening = $this->actingAs($customer)->post('/customer/pembayaran/'.$pembayaran->id.'/rekening-refund', [
            'bank_tujuan' => 'Bank Mandiri',
            'norek_tujuan' => '1380001234567',
            'atas_nama_pengirim' => 'Instansi Pemohon Jaya',
        ]);

        $responseRekening->assertRedirect('/customer/pembayaran/'.$pembayaran->id);
        $responseRekening->assertSessionHas('success');

        $this->assertDatabaseHas('detail_pembayarans', [
            'pembayaran_id' => $pembayaran->id,
            'tipe_pembayaran' => 'refund',
            'bank_tujuan' => 'Bank Mandiri',
            'norek_tujuan' => '1380001234567',
            'atas_nama_pengirim' => 'Instansi Pemohon Jaya',
        ]);

        // 2. Admin mengunggah bukti transfer refund
        $fileBukti = UploadedFile::fake()->image('bukti_refund.jpg');
        $responseUploadRefund = $this->actingAs($admin)->post('/admin/peminjaman/'.$peminjaman->id.'/upload-refund', [
            'bukti_refund' => $fileBukti,
            'bank_pengirim' => 'Bank Jateng',
            'catatan' => 'Dana telah ditransfer ke rekening Mandiri pemohon.',
        ]);

        $responseUploadRefund->assertRedirect('/admin/peminjaman/'.$peminjaman->id);
        $responseUploadRefund->assertSessionHas('success');

        $refundDetail = $pembayaran->details()->where('tipe_pembayaran', 'refund')->first();
        $this->assertNotNull($refundDetail->bukti_pembayaran);
        Storage::disk('public')->assertExists($refundDetail->bukti_pembayaran);

        // 3. Pemohon mengonfirmasi penerimaan dana refund
        $responseConfirm = $this->actingAs($customer)->post('/customer/pembayaran/'.$pembayaran->id.'/konfirmasi-refund');

        $responseConfirm->assertRedirect('/customer/pembayaran/'.$pembayaran->id);
        $responseConfirm->assertSessionHas('success');

        $pembayaran->refresh();
        $refundDetail->refresh();

        $this->assertEquals('refunded', $pembayaran->status_pembayaran);
        $this->assertEquals('verified', $refundDetail->status);
    }

    public function test_admin_can_reject_deposit_payment_and_system_auto_rejects_on_expired_deadline(): void
    {
        $admin = $this->createAdminAula();
        [$peminjaman, $pembayaran] = $this->createPeminjamanDanTagihan('pending', 'pending', 0);

        $detailDp = DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'kode_transaksi' => 'TRX-DP-001',
            'tipe_pembayaran' => 'dp',
            'jumlah_bayar' => 1500000,
            'status' => 'pending',
            'tanggal_bayar' => now(),
        ]);

        // Admin tolak pembayaran deposit karena bukti buram / tidak masuk
        $responseRejectPayment = $this->actingAs($admin)->post('/admin/peminjaman/'.$peminjaman->id.'/reject-pembayaran', [
            'alasan_penolakan' => 'Bukti transfer tidak jelas dan nominal tidak masuk mutasi rekening sekolah.',
        ]);

        $responseRejectPayment->assertRedirect('/admin/peminjaman/'.$peminjaman->id);
        $responseRejectPayment->assertSessionHas('success');

        $pembayaran->refresh();
        $detailDp->refresh();

        $this->assertEquals('rejected', $pembayaran->status_pembayaran);
        $this->assertEquals('rejected', $detailDp->status);
        $this->assertTrue(now()->diffInHours($pembayaran->jatuh_tempo_dp) >= 23);

        // Simulasikan waktu melewati batas jatuh tempo transfer ulang
        $pembayaran->update([
            'jatuh_tempo_dp' => now()->subMinutes(10),
        ]);

        // Panggil auto-sync deadline
        Peminjaman::syncExpiredDeadlines();

        $peminjaman->refresh();
        $pembayaran->refresh();

        $this->assertEquals('rejected', $peminjaman->status);
        $this->assertEquals('hangus', $pembayaran->status_pembayaran);
    }

    public function test_approve_peminjaman_mengupdate_jatuh_tempo_pelunasan_sesuai_konfigurasi_dan_hari_h(): void
    {
        $admin = $this->createAdminAula();

        PaymentConfiguration::truncate();
        $config = PaymentConfiguration::create([
            'nama_sekolah' => 'SMK Negeri 2 Karanganyar',
            'bank_utama' => 'Bank Jateng',
            'norek_utama' => '1234567890',
            'atas_nama_utama' => 'Bendahara Aula SMK 2',
            'jatuh_tempo_dp_jam' => 24,
            'jatuh_tempo_pelunasan_jam' => 24,
        ]);

        $tanggalAcara = now()->addDays(5)->startOfHour();

        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Aula Uji Deadline',
            'kategori' => 'standar 1',
            'harga' => 3000000,
            'harga_dp' => 1000000,
        ]);

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'Peminjam Uji Deadline',
            'email_instansi' => 'pemohon_deadline@test.com',
            'tanggal_mulai' => $tanggalAcara,
            'tanggal_selesai' => $tanggalAcara->copy()->addHours(6),
            'status' => 'pending',
        ]);

        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'PAY-DDL-001',
            'total_tagihan' => 3000000,
            'total_terbayar' => 0,
            'sisa_tagihan' => 3000000,
            'status_pembayaran' => 'pending',
            'jatuh_tempo_dp' => now()->addHours(24),
            'jatuh_tempo_pelunasan' => now()->addHours(48),
        ]);

        // Simulasikan ada transaksi DP
        DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'kode_transaksi' => 'TRX-DP-DDL-1',
            'tipe_pembayaran' => 'dp',
            'jumlah_bayar' => 1000000,
            'metode' => 'transfer',
            'status' => 'pending',
            'tanggal_bayar' => now(),
        ]);

        // Admin approve
        $response = $this->actingAs($admin)->post('/admin/peminjaman/'.$peminjaman->id.'/approve', [
            'catatan_approval' => 'Approved dan jadwal terverifikasi.',
        ]);

        $response->assertRedirect('/admin/peminjaman/'.$peminjaman->id);

        $pembayaran->refresh();
        $this->assertEquals('partial', $pembayaran->status_pembayaran);

        // Tenggat waktu pelunasan harus 24 jam sebelum hari H (tanggal_mulai)
        $expectedDeadline = $tanggalAcara->copy()->subHours(24);
        $this->assertEquals(
            $expectedDeadline->format('Y-m-d H:i'),
            $pembayaran->jatuh_tempo_pelunasan->format('Y-m-d H:i')
        );

        // Kunjungi halaman detail di dashboard admin
        $viewResponse = $this->actingAs($admin)->get('/admin/peminjaman/'.$peminjaman->id);
        $viewResponse->assertOk();
        $viewResponse->assertSee('Batas Waktu Pelunasan (Final):');
        $viewResponse->assertSee('24 jam sebelum Hari H');
        $viewResponse->assertSee($expectedDeadline->translatedFormat('d M Y, H:i'));
    }
}
