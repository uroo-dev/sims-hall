<?php

namespace Tests\Feature\Admin;

use App\Models\DetailPembayaran;
use App\Models\PaketPeminjaman;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Models\Persetujuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KepalaSekolahTest extends TestCase
{
    use RefreshDatabase;

    private function createKepalaSekolah(): User
    {
        return User::factory()->create([
            'username' => 'kepsek',
            'role' => 'kepala_sekolah',
        ]);
    }

    private function createPeminjaman(string $status = 'approved_1'): Peminjaman
    {
        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Aula Pertemuan',
            'kategori' => 'standar 1',
            'harga' => 3000000,
            'harga_dp' => 1000000,
            'deskripsi' => 'Fasilitas pertemuan lengkap',
        ]);

        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'PT Solusi Bangsa',
            'email_instansi' => 'kontak@solusibangsa.id',
            'tanggal_mulai' => now()->addDays(5)->setTime(9, 0),
            'tanggal_selesai' => now()->addDays(5)->setTime(15, 0),
            'catatan' => 'Pertemuan dinas tahunan',
            'status' => $status,
        ]);

        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'INV-2026-'.str_pad((string) $peminjaman->id, 4, '0', STR_PAD_LEFT),
            'total_tagihan' => 3000000,
            'total_terbayar' => 1000000,
            'sisa_tagihan' => 2000000,
            'status_pembayaran' => 'partial',
        ]);

        DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'kode_transaksi' => 'TRX-DP-'.str_pad((string) $peminjaman->id, 4, '0', STR_PAD_LEFT),
            'jumlah_bayar' => 1000000,
            'metode' => 'transfer',
            'status' => 'verified',
            'tipe_pembayaran' => 'dp',
            'bank_tujuan' => 'Bank Jateng',
            'bank_pengirim' => 'BCA',
            'norek_pengirim' => '1234567890',
            'atas_nama_pengirim' => 'PT Solusi Bangsa',
            'tanggal_bayar' => now()->subDay(),
        ]);

        // Jika approved_1, catat persetujuan tahap admin
        if (in_array($status, ['approved_1', 'approved_final'])) {
            Persetujuan::create([
                'peminjaman_id' => $peminjaman->id,
                'approver_id' => User::factory()->create(['role' => 'admin'])->id,
                'level' => 'admin',
                'status' => 'approved',
                'catatan_approval' => 'Disetujui oleh admin sarpras.',
                'tanggal_proses' => now()->subHours(2),
            ]);
        }

        return $peminjaman;
    }

    public function test_guest_cannot_access_kepala_sekolah_panel(): void
    {
        $response = $this->get(route('kepala-sekolah.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_pelanggan_cannot_access_kepala_sekolah_panel(): void
    {
        $pelanggan = User::factory()->create(['role' => 'pelanggan']);
        $response = $this->actingAs($pelanggan)->get(route('kepala-sekolah.dashboard'));
        $response->assertStatus(403);
    }

    public function test_kepala_sekolah_can_access_dashboard_and_index(): void
    {
        $kepsek = $this->createKepalaSekolah();
        $this->createPeminjaman('approved_1');

        $responseDashboard = $this->actingAs($kepsek)->get(route('kepala-sekolah.dashboard'));
        $responseDashboard->assertOk();
        $responseDashboard->assertSee('Selamat Datang');
        $responseDashboard->assertSee('PT Solusi Bangsa');

        $responseIndex = $this->actingAs($kepsek)->get(route('kepala-sekolah.peminjaman.index'));
        $responseIndex->assertOk();
        $responseIndex->assertSee('Daftar Permohonan Peminjaman Aula');
        $responseIndex->assertSee('PT Solusi Bangsa');
    }

    public function test_kepala_sekolah_can_view_detail_peminjaman(): void
    {
        $kepsek = $this->createKepalaSekolah();
        $peminjaman = $this->createPeminjaman('approved_1');

        $response = $this->actingAs($kepsek)->get(route('kepala-sekolah.peminjaman.show', $peminjaman->id));
        $response->assertOk();
        $response->assertSee('Menunggu Keputusan Persetujuan Final Kepala Sekolah');
        $response->assertSee('Setujui Permohonan (Final)');
        $response->assertSee('Tolak Permohonan');
    }

    public function test_kepala_sekolah_can_approve_final_peminjaman(): void
    {
        $kepsek = $this->createKepalaSekolah();
        $peminjaman = $this->createPeminjaman('approved_1');

        $response = $this->actingAs($kepsek)->post(route('kepala-sekolah.peminjaman.approve', $peminjaman->id), [
            'catatan_approval' => 'Disetujui resmi oleh Kepala Sekolah.',
        ]);

        $response->assertRedirect(route('kepala-sekolah.peminjaman.show', $peminjaman->id));
        $response->assertSessionHas('success');

        $peminjaman->refresh();
        $this->assertEquals('approved_final', $peminjaman->status);

        $this->assertDatabaseHas('persetujuans', [
            'peminjaman_id' => $peminjaman->id,
            'approver_id' => $kepsek->id,
            'level' => 'pimpinan',
            'status' => 'approved',
            'catatan_approval' => 'Disetujui resmi oleh Kepala Sekolah.',
        ]);
    }

    public function test_kepala_sekolah_cannot_approve_peminjaman_that_has_not_passed_admin(): void
    {
        $kepsek = $this->createKepalaSekolah();
        $peminjaman = $this->createPeminjaman('pending');

        $response = $this->actingAs($kepsek)->post(route('kepala-sekolah.peminjaman.approve', $peminjaman->id));

        $response->assertSessionHas('error');
        $peminjaman->refresh();
        $this->assertEquals('pending', $peminjaman->status);
    }

    public function test_kepala_sekolah_can_reject_peminjaman_with_refund_trigger(): void
    {
        $kepsek = $this->createKepalaSekolah();
        $peminjaman = $this->createPeminjaman('approved_1');

        $response = $this->actingAs($kepsek)->post(route('kepala-sekolah.peminjaman.reject', $peminjaman->id), [
            'alasan_penolakan' => 'Aula akan digunakan untuk agenda kunjungan dinas pendidikan.',
        ]);

        $response->assertRedirect(route('kepala-sekolah.peminjaman.show', $peminjaman->id));
        $response->assertSessionHas('success');

        $peminjaman->refresh();
        $this->assertEquals('rejected', $peminjaman->status);

        $this->assertDatabaseHas('persetujuans', [
            'peminjaman_id' => $peminjaman->id,
            'approver_id' => $kepsek->id,
            'level' => 'pimpinan',
            'status' => 'rejected',
        ]);

        // Karena pembayaran sudah DP 1.000.000 (verified), otomatis masuk refund flow
        $pembayaran = $peminjaman->pembayaran->fresh();
        $this->assertEquals('refund_pending', $pembayaran->status_pembayaran);
        $this->assertEquals(1000000, $pembayaran->total_refund);
        $this->assertDatabaseHas('detail_pembayarans', [
            'pembayaran_id' => $pembayaran->id,
            'tipe_pembayaran' => 'refund',
            'status' => 'pending',
            'jumlah_bayar' => 1000000,
        ]);
    }

    public function test_login_as_kepala_sekolah_redirects_to_kepala_sekolah_dashboard(): void
    {
        $kepsek = User::factory()->create([
            'username' => 'kepsek_test',
            'role' => 'kepala_sekolah',
            'password' => 'password',
        ]);

        $response = $this->post(route('login'), [
            'username' => 'kepsek_test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('kepala-sekolah.dashboard'));
    }

    public function test_kepala_sekolah_dapat_mengekspor_pdf_daftar_peminjaman(): void
    {
        $kepsek = $this->createKepalaSekolah();
        $this->createPeminjaman('approved_1');

        $response = $this->actingAs($kepsek)->get(route('kepala-sekolah.peminjaman.export-pdf'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_kepala_sekolah_dapat_mengekspor_pdf_daftar_peminjaman_dengan_custom_tanggal(): void
    {
        $kepsek = $this->createKepalaSekolah();
        $this->createPeminjaman('approved_1');

        $response = $this->actingAs($kepsek)->get(route('kepala-sekolah.peminjaman.export-pdf', [
            'tanggal_dari' => now()->toDateString(),
            'tanggal_sampai' => now()->addDays(10)->toDateString(),
            'stream' => '1',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}
