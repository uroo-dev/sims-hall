<?php

namespace Tests\Feature;

use App\Models\DetailPembayaran;
use App\Models\Fitur;
use App\Models\PaketPeminjaman;
use App\Models\PaymentConfiguration;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeminjamanCancellationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $pelanggan;

    private PaketPeminjaman $paket;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_aula',
        ]);

        Fitur::create([
            'user_id' => $this->admin->id,
            'nama_fitur' => 'aula',
        ]);

        $this->pelanggan = User::factory()->create([
            'name' => 'Budi Pemohon',
            'email' => 'budi@example.com',
            'role' => 'pelanggan',
        ]);

        $this->paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Pernikahan Silver',
            'kategori' => 'unggulan',
            'harga' => 5000000,
        ]);

        PaymentConfiguration::current()->update([
            'minimal_hari_booking' => 10,
            'offset_hari_pembatalan' => 9,
        ]);
    }

    public function test_pembatalan_berhasil_pada_h_minus_9_jika_selisih_booking_10_hari(): void
    {
        // Simulasi: dibuat pada 1 Oktober, acara pada 11 Oktober (selisih booking = 10 hari)
        // Maksimal pembatalan: H - 9 (yaitu 2 Oktober 23:59:59)
        Carbon::setTestNow(Carbon::create(2026, 10, 1, 10, 0, 0));

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $this->paket->id,
            'nama' => 'Budi Pemohon',
            'email_instansi' => 'budi@example.com',
            'tanggal_mulai' => Carbon::create(2026, 10, 11, 8, 0, 0),
            'tanggal_selesai' => Carbon::create(2026, 10, 11, 17, 0, 0),
            'status' => 'pending',
            'created_at' => Carbon::create(2026, 10, 1, 10, 0, 0),
        ]);

        $this->assertEquals(10, $peminjaman->selisih_booking_hari);
        $this->assertEquals(9, $peminjaman->hari_maksimal_cancel);
        // Batas pembatalan: 11 Okt - 9 hari = 2 Okt 23:59:59
        $this->assertEquals('2026-10-02 23:59:59', $peminjaman->batas_pembatalan->toDateTimeString());

        // Coba batalkan pada 2 Oktober jam 15:00 (masih dalam H-9)
        Carbon::setTestNow(Carbon::create(2026, 10, 2, 15, 0, 0));
        $this->assertTrue($peminjaman->canBeCancelled());

        $response = $this->actingAs($this->pelanggan)->post(route('customer.peminjaman.cancel', $peminjaman->id), [
            'alasan_pembatalan' => 'Ada perubahan rencana acara',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $peminjaman->refresh();
        $this->assertEquals('cancelled', $peminjaman->status);
        $this->assertStringContainsString('Ada perubahan rencana acara', $peminjaman->catatan);

        Carbon::setTestNow();
    }

    public function test_pembatalan_bisa_dilakukan_kapan_saja_misal_h_minus_8_namun_dana_tidak_dapat_direfund_hangus(): void
    {
        // Selisih booking = 10 hari (1 Okt ke 11 Okt), batas offset cancelation = H-9 (2 Okt 23:59:59)
        Carbon::setTestNow(Carbon::create(2026, 10, 1, 10, 0, 0));

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $this->paket->id,
            'nama' => 'Budi Pemohon',
            'email_instansi' => 'budi@example.com',
            'tanggal_mulai' => Carbon::create(2026, 10, 11, 8, 0, 0),
            'tanggal_selesai' => Carbon::create(2026, 10, 11, 17, 0, 0),
            'status' => 'approved_1',
            'created_at' => Carbon::create(2026, 10, 1, 10, 0, 0),
        ]);

        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'INV-TEST-004',
            'total_tagihan' => 5000000,
            'total_terbayar' => 2500000,
            'sisa_tagihan' => 2500000,
            'status_pembayaran' => 'partial',
        ]);

        DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'tipe_pembayaran' => 'dp',
            'jumlah_bayar' => 2500000,
            'status' => 'verified',
            'tanggal_bayar' => now(),
        ]);

        // Coba batalkan pada 3 Oktober jam 08:00 (H-8, melebihi batas offset cancelation H-9)
        Carbon::setTestNow(Carbon::create(2026, 10, 3, 8, 0, 0));

        // Pelanggan tetap BISA membatalkan kapan saja
        $this->assertTrue($peminjaman->canBeCancelled());
        // Namun TIDAK BERHAK mendapatkan pengembalian dana (refund)
        $this->assertFalse($peminjaman->isEligibleForRefund());

        $response = $this->actingAs($this->pelanggan)->post(route('customer.peminjaman.cancel', $peminjaman->id), [
            'alasan_pembatalan' => 'Membatalkan di H-8 karena kendala internal',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $peminjaman->refresh();
        $pembayaran->refresh();

        // Status peminjaman berhasil dibatalkan
        $this->assertEquals('cancelled', $peminjaman->status);
        // Namun DANA TIDAK DAPAT DIREUND: status pembayaran hangus dan total_refund bernilai 0
        $this->assertEquals('hangus', $pembayaran->status_pembayaran);
        $this->assertEquals(0, $pembayaran->total_refund);
        $this->assertEquals(0, $pembayaran->sisa_tagihan);
        $this->assertStringContainsString('dana hangus', strtolower($pembayaran->catatan));
        // Tidak ada rincian transaksi refund yang dibuat
        $this->assertNull($pembayaran->details()->where('tipe_pembayaran', 'refund')->first());

        Carbon::setTestNow();
    }

    public function test_pembatalan_bisa_dilakukan_pada_hari_h_namun_dana_tidak_dapat_direfund_hangus(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 1, 10, 0, 0));

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $this->paket->id,
            'nama' => 'Budi Pemohon',
            'email_instansi' => 'budi@example.com',
            'tanggal_mulai' => Carbon::create(2026, 10, 11, 8, 0, 0),
            'tanggal_selesai' => Carbon::create(2026, 10, 11, 17, 0, 0),
            'status' => 'pending',
            'created_at' => Carbon::create(2026, 10, 1, 10, 0, 0),
        ]);

        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'INV-TEST-005',
            'total_tagihan' => 5000000,
            'total_terbayar' => 5000000,
            'sisa_tagihan' => 0,
            'status_pembayaran' => 'lunas',
        ]);

        // Pada Hari H jam 07:00 (sebelum acara selesai)
        Carbon::setTestNow(Carbon::create(2026, 10, 11, 7, 0, 0));
        $this->assertTrue($peminjaman->canBeCancelled());
        $this->assertFalse($peminjaman->isEligibleForRefund());

        $response = $this->actingAs($this->pelanggan)->post(route('customer.peminjaman.cancel', $peminjaman->id), [
            'alasan_pembatalan' => 'Batal mendadak di hari H',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $peminjaman->refresh();
        $pembayaran->refresh();

        $this->assertEquals('cancelled', $peminjaman->status);
        $this->assertEquals('hangus', $pembayaran->status_pembayaran);
        $this->assertEquals(0, $pembayaran->total_refund);

        Carbon::setTestNow();
    }

    public function test_pembatalan_setelah_acara_selesai_dilaksanakan_ditolak(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 1, 10, 0, 0));

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $this->paket->id,
            'nama' => 'Budi Pemohon',
            'email_instansi' => 'budi@example.com',
            'tanggal_mulai' => Carbon::create(2026, 10, 11, 8, 0, 0),
            'tanggal_selesai' => Carbon::create(2026, 10, 11, 17, 0, 0),
            'status' => 'approved_final',
            'created_at' => Carbon::create(2026, 10, 1, 10, 0, 0),
        ]);

        // Setelah acara selesai (misal 12 Oktober)
        Carbon::setTestNow(Carbon::create(2026, 10, 12, 10, 0, 0));
        $this->assertFalse($peminjaman->canBeCancelled());

        $response = $this->actingAs($this->pelanggan)->post(route('customer.peminjaman.cancel', $peminjaman->id));
        $response->assertRedirect();
        $response->assertSessionHas('error');

        $peminjaman->refresh();
        $this->assertEquals('approved_final', $peminjaman->status);

        Carbon::setTestNow();
    }

    public function test_pembatalan_otomatis_memicu_refund_pending_jika_pembayaran_sudah_terverifikasi(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 1, 10, 0, 0));

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $this->paket->id,
            'nama' => 'Budi Pemohon',
            'email_instansi' => 'budi@example.com',
            'tanggal_mulai' => Carbon::create(2026, 10, 11, 8, 0, 0),
            'tanggal_selesai' => Carbon::create(2026, 10, 11, 17, 0, 0),
            'status' => 'approved_final',
            'created_at' => Carbon::create(2026, 10, 1, 10, 0, 0),
        ]);

        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'INV-TEST-001',
            'total_tagihan' => 5000000,
            'total_terbayar' => 2500000,
            'sisa_tagihan' => 2500000,
            'status_pembayaran' => 'partial',
        ]);

        DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'tipe_pembayaran' => 'dp',
            'jumlah_bayar' => 2500000,
            'status' => 'verified',
            'tanggal_bayar' => now(),
        ]);

        // Batalkan dalam masa tenggang (2 Oktober jam 10:00)
        Carbon::setTestNow(Carbon::create(2026, 10, 2, 10, 0, 0));

        $response = $this->actingAs($this->pelanggan)->post(route('customer.peminjaman.cancel', $peminjaman->id), [
            'alasan_pembatalan' => 'Pembatalan dengan refund DP',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $peminjaman->refresh();
        $pembayaran->refresh();

        $this->assertEquals('cancelled', $peminjaman->status);
        $this->assertEquals('refund_pending', $pembayaran->status_pembayaran);
        $this->assertEquals(2500000, $pembayaran->total_refund);
        $this->assertEquals(0, $pembayaran->sisa_tagihan);

        // Pastikan detail refund dibuat
        $refundDetail = $pembayaran->details()->where('tipe_pembayaran', 'refund')->first();
        $this->assertNotNull($refundDetail);
        $this->assertEquals(2500000, $refundDetail->jumlah_bayar);
        $this->assertEquals('pending', $refundDetail->status);

        Carbon::setTestNow();
    }

    public function test_pembatalan_dengan_pembayaran_belum_terverifikasi_menunggu_verifikasi_admin_sebelum_refund(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 1, 10, 0, 0));

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $this->paket->id,
            'nama' => 'Budi Pemohon',
            'email_instansi' => 'budi@example.com',
            'tanggal_mulai' => Carbon::create(2026, 10, 11, 8, 0, 0),
            'tanggal_selesai' => Carbon::create(2026, 10, 11, 17, 0, 0),
            'status' => 'pending',
            'created_at' => Carbon::create(2026, 10, 1, 10, 0, 0),
        ]);

        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'INV-TEST-002',
            'total_tagihan' => 5000000,
            'total_terbayar' => 0,
            'sisa_tagihan' => 5000000,
            'status_pembayaran' => 'pending',
        ]);

        // Pemohon mengunggah bukti pembayaran tetapi belum diverifikasi admin
        $detailPending = DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'tipe_pembayaran' => 'dp',
            'jumlah_bayar' => 2000000,
            'status' => 'pending',
            'bukti_pembayaran' => 'bukti/transfer.jpg',
            'tanggal_bayar' => now(),
        ]);

        // Pemohon membatalkan pada 2 Oktober jam 10:00 (masih masa tenggang)
        Carbon::setTestNow(Carbon::create(2026, 10, 2, 10, 0, 0));

        $response = $this->actingAs($this->pelanggan)->post(route('customer.peminjaman.cancel', $peminjaman->id), [
            'alasan_pembatalan' => 'Saya batalkan sebelum diverifikasi',
        ]);

        $response->assertRedirect();
        $peminjaman->refresh();
        $pembayaran->refresh();

        $this->assertEquals('cancelled', $peminjaman->status);
        // Karena pembayaran belum diverifikasi admin, status pembayaran belum langsung refund_pending
        $this->assertEquals('pending', $pembayaran->status_pembayaran);

        // Sekarang Admin memverifikasi bukti transfer tersebut
        $verifResponse = $this->actingAs($this->admin)->post(
            route('admin.peminjaman.verifikasi-pembayaran', [$peminjaman->id, $detailPending->id])
        );

        $verifResponse->assertRedirect();
        $detailPending->refresh();
        $pembayaran->refresh();

        // Setelah diverifikasi, karena peminjaman cancelled, status otomatis transisi ke refund_pending
        $this->assertEquals('verified', $detailPending->status);
        $this->assertEquals('refund_pending', $pembayaran->status_pembayaran);
        $this->assertEquals(2000000, $pembayaran->total_terbayar);
        $this->assertEquals(2000000, $pembayaran->total_refund);

        $refundDetail = $pembayaran->details()->where('tipe_pembayaran', 'refund')->first();
        $this->assertNotNull($refundDetail);
        $this->assertEquals(2000000, $refundDetail->jumlah_bayar);

        Carbon::setTestNow();
    }

    public function test_pembatalan_tanpa_pembayaran_langsung_ditutup_tanpa_refund(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 1, 10, 0, 0));

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $this->paket->id,
            'nama' => 'Budi Pemohon',
            'email_instansi' => 'budi@example.com',
            'tanggal_mulai' => Carbon::create(2026, 10, 11, 8, 0, 0),
            'tanggal_selesai' => Carbon::create(2026, 10, 11, 17, 0, 0),
            'status' => 'pending',
            'created_at' => Carbon::create(2026, 10, 1, 10, 0, 0),
        ]);

        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'INV-TEST-003',
            'total_tagihan' => 5000000,
            'total_terbayar' => 0,
            'sisa_tagihan' => 5000000,
            'status_pembayaran' => 'pending',
        ]);

        Carbon::setTestNow(Carbon::create(2026, 10, 2, 10, 0, 0));

        $response = $this->actingAs($this->pelanggan)->post(route('customer.peminjaman.cancel', $peminjaman->id), [
            'alasan_pembatalan' => 'Batal sebelum bayar',
        ]);

        $response->assertRedirect();
        $peminjaman->refresh();
        $pembayaran->refresh();

        $this->assertEquals('cancelled', $peminjaman->status);
        $this->assertEquals(0, $pembayaran->total_refund);
        $this->assertNull($pembayaran->details()->where('tipe_pembayaran', 'refund')->first());

        Carbon::setTestNow();
    }

    public function test_admin_bisa_membatalkan_peminjaman(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 1, 10, 0, 0));

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $this->paket->id,
            'nama' => 'Budi Pemohon',
            'email_instansi' => 'budi@example.com',
            'tanggal_mulai' => Carbon::create(2026, 10, 11, 8, 0, 0),
            'tanggal_selesai' => Carbon::create(2026, 10, 11, 17, 0, 0),
            'status' => 'pending',
            'created_at' => Carbon::create(2026, 10, 1, 10, 0, 0),
        ]);

        Carbon::setTestNow(Carbon::create(2026, 10, 2, 10, 0, 0));

        $response = $this->actingAs($this->admin)->post(route('admin.peminjaman.cancel', $peminjaman->id), [
            'alasan_pembatalan' => 'Pembatalan oleh admin atas permintaan pemohon',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $peminjaman->refresh();
        $this->assertEquals('cancelled', $peminjaman->status);

        Carbon::setTestNow();
    }

    public function test_super_admin_bisa_mengatur_offset_hari_pembatalan_di_konfigurasi_pembayaran(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'username' => 'root_super',
        ]);

        $response = $this->actingAs($superAdmin)->put(route('admin.payment-configuration.update'), [
            'bank_utama' => 'Bank Jateng',
            'norek_utama' => '1234567890',
            'atas_nama_utama' => 'SMKN 2 KRA',
            'jatuh_tempo_dp_jam' => 24,
            'jatuh_tempo_pelunasan_jam' => 48,
            'minimal_hari_booking' => 10,
            'offset_hari_pembatalan' => 9, // H-9
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $config = PaymentConfiguration::current();
        $this->assertEquals(9, $config->offset_hari_pembatalan);
        $this->assertEquals(10, $config->minimal_hari_booking);
    }

    public function test_offset_hari_pembatalan_yang_dikonfigurasi_mempengaruhi_perhitungan_maksimal_cancel(): void
    {
        // Ubah konfigurasi offset menjadi langsung H-8
        $config = PaymentConfiguration::current();
        $config->update([
            'minimal_hari_booking' => 10,
            'offset_hari_pembatalan' => 8,
        ]);

        Carbon::setTestNow(Carbon::create(2026, 10, 1, 10, 0, 0));

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $this->paket->id,
            'nama' => 'Budi Pemohon',
            'email_instansi' => 'budi@example.com',
            'tanggal_mulai' => Carbon::create(2026, 10, 11, 8, 0, 0),
            'tanggal_selesai' => Carbon::create(2026, 10, 11, 17, 0, 0),
            'status' => 'pending',
            'created_at' => Carbon::create(2026, 10, 1, 10, 0, 0),
        ]);

        // Selisih booking = 10 hari. Offset diatur H-8.
        // Maka hari_maksimal_cancel langsung = 8 (H-8).
        $this->assertEquals(10, $peminjaman->selisih_booking_hari);
        $this->assertEquals(8, $peminjaman->hari_maksimal_cancel);
        $this->assertEquals('2026-10-03 23:59:59', $peminjaman->batas_pembatalan->toDateTimeString());

        Carbon::setTestNow();
    }

    public function test_validasi_offset_hari_pembatalan_tidak_boleh_melebihi_minimal_hari_booking(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'username' => 'root_super',
        ]);

        // Jika minimal_hari_booking = 10 dan offset = 10 -> Masih boleh (tidak melebihi)
        $responseAllowed = $this->actingAs($superAdmin)->put(route('admin.payment-configuration.update'), [
            'bank_utama' => 'Bank Jateng',
            'norek_utama' => '1234567890',
            'atas_nama_utama' => 'SMKN 2 KRA',
            'jatuh_tempo_dp_jam' => 24,
            'jatuh_tempo_pelunasan_jam' => 48,
            'minimal_hari_booking' => 10,
            'offset_hari_pembatalan' => 10,
        ]);
        $responseAllowed->assertSessionHasNoErrors();

        // Jika minimal_hari_booking = 10 dan offset = 11 -> Melebihi -> Harus error
        $responseError = $this->actingAs($superAdmin)->put(route('admin.payment-configuration.update'), [
            'bank_utama' => 'Bank Jateng',
            'norek_utama' => '1234567890',
            'atas_nama_utama' => 'SMKN 2 KRA',
            'jatuh_tempo_dp_jam' => 24,
            'jatuh_tempo_pelunasan_jam' => 48,
            'minimal_hari_booking' => 10,
            'offset_hari_pembatalan' => 11,
        ]);

        $responseError->assertSessionHasErrors('offset_hari_pembatalan');
    }

    public function test_verifikasi_admin_atas_pembayaran_yang_dibatalkan_melewati_offset_tetap_hangus_tanpa_refund(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 1, 10, 0, 0));

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $this->paket->id,
            'nama' => 'Budi Pemohon',
            'email_instansi' => 'budi@example.com',
            'tanggal_mulai' => Carbon::create(2026, 10, 11, 8, 0, 0),
            'tanggal_selesai' => Carbon::create(2026, 10, 11, 17, 0, 0),
            'status' => 'pending',
            'created_at' => Carbon::create(2026, 10, 1, 10, 0, 0),
        ]);

        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'INV-TEST-006',
            'total_tagihan' => 5000000,
            'total_terbayar' => 0,
            'sisa_tagihan' => 5000000,
            'status_pembayaran' => 'pending',
        ]);

        // Pemohon mengunggah bukti pembayaran
        $detailPending = DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'tipe_pembayaran' => 'dp',
            'jumlah_bayar' => 2000000,
            'status' => 'pending',
            'bukti_pembayaran' => 'bukti/transfer.jpg',
            'tanggal_bayar' => now(),
        ]);

        // Pemohon membatalkan pada 5 Oktober jam 10:00 (H-6, MELEBIHI OFFSET CANCELATION H-9)
        Carbon::setTestNow(Carbon::create(2026, 10, 5, 10, 0, 0));

        $response = $this->actingAs($this->pelanggan)->post(route('customer.peminjaman.cancel', $peminjaman->id), [
            'alasan_pembatalan' => 'Membatalkan lewat batas offset',
        ]);

        $response->assertRedirect();
        $peminjaman->refresh();
        $pembayaran->refresh();

        $this->assertEquals('cancelled', $peminjaman->status);
        $this->assertEquals('hangus', $pembayaran->status_pembayaran);

        // Admin kemudian memverifikasi bukti transfer tersebut
        $verifResponse = $this->actingAs($this->admin)->post(
            route('admin.peminjaman.verifikasi-pembayaran', [$peminjaman->id, $detailPending->id])
        );

        $verifResponse->assertRedirect();
        $detailPending->refresh();
        $pembayaran->refresh();

        // Status bukti verified, namun status pembayaran TETAP HANGUS dan total_refund TETAP 0
        $this->assertEquals('verified', $detailPending->status);
        $this->assertEquals('hangus', $pembayaran->status_pembayaran);
        $this->assertEquals(0, $pembayaran->total_refund);
        $this->assertNull($pembayaran->details()->where('tipe_pembayaran', 'refund')->first());

        Carbon::setTestNow();
    }
}
