<?php

namespace Tests\Feature;

use App\Models\DetailPembayaran;
use App\Models\PaketPeminjaman;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PembayaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_partial_payment_dp_dan_pelunasan_bekerja_dengan_benar(): void
    {
        // 1. Buat paket peminjaman aula dengan harga 5.000.000
        $paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Wedding Standard',
            'kategori' => 'standar 1',
            'harga' => 5000000,
            'deskripsi' => 'Fasilitas aula lengkap untuk acara resepsi',
        ]);

        // 2. Buat data peminjaman
        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $paket->id,
            'nama' => 'Budi Santoso',
            'email_instansi' => 'budi@gmail.com',
            'tanggal_mulai' => now()->addDays(14)->setTime(8, 0),
            'tanggal_selesai' => now()->addDays(14)->setTime(16, 0),
            'catatan' => 'Peminjaman aula acara pernikahan',
            'status' => 'approved_1',
        ]);

        // 3. Buat ringkasan tagihan utama (pembayarans)
        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'INV-202609-0001',
            'total_tagihan' => 5000000,
            'total_terbayar' => 0,
            'sisa_tagihan' => 5000000,
            'status_pembayaran' => 'pending',
            'jatuh_tempo_pelunasan' => now()->addDays(10),
        ]);

        $this->assertEquals(5000000, $pembayaran->total_tagihan);
        $this->assertEquals(0, $pembayaran->total_terbayar);
        $this->assertEquals(5000000, $pembayaran->sisa_tagihan);
        $this->assertEquals('pending', $pembayaran->status_pembayaran);

        // 4. User melakukan transfer Cicilan 1 / DP (50% = 2.500.000)
        $dp = DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'kode_transaksi' => 'TRX-DP-001',
            'tipe_pembayaran' => 'dp',
            'jumlah_bayar' => 2500000,
            'metode' => 'transfer',
            'bank_tujuan' => 'Bank Jateng',
            'norek_tujuan' => '1234567890',
            'bank_pengirim' => 'BCA',
            'atas_nama_pengirim' => 'Budi Santoso',
            'tanggal_bayar' => now(),
            'status' => 'pending',
        ]);

        // Sebelum diverifikasi, status tagihan masih pending
        $pembayaran->syncAkumulasiPembayaran();
        $this->assertEquals('pending', $pembayaran->status_pembayaran);

        // Admin memverifikasi DP
        $admin = User::factory()->create(['role' => 'admin']);
        $dp->update([
            'status' => 'verified',
            'diverifikasi_oleh' => $admin->id,
            'diverifikasi_pada' => now(),
        ]);

        // Sinkronisasi tagihan setelah DP diverifikasi
        $pembayaran->syncAkumulasiPembayaran();
        $pembayaran->refresh();

        $this->assertEquals(2500000, $pembayaran->total_terbayar);
        $this->assertEquals(2500000, $pembayaran->sisa_tagihan);
        $this->assertEquals('partial', $pembayaran->status_pembayaran);
        $this->assertTrue($pembayaran->isPartial());
        $this->assertFalse($pembayaran->isLunas());

        // 5. User melakukan transfer Cicilan 2 / Pelunasan (sisa 2.500.000)
        $pelunasan = DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'kode_transaksi' => 'TRX-LNS-002',
            'tipe_pembayaran' => 'pelunasan',
            'jumlah_bayar' => 2500000,
            'metode' => 'transfer',
            'bank_tujuan' => 'Bank Jateng',
            'norek_tujuan' => '1234567890',
            'bank_pengirim' => 'BCA',
            'atas_nama_pengirim' => 'Budi Santoso',
            'tanggal_bayar' => now(),
            'status' => 'verified',
            'diverifikasi_oleh' => $admin->id,
            'diverifikasi_pada' => now(),
        ]);

        // Sinkronisasi tagihan setelah Pelunasan diverifikasi
        $pembayaran->syncAkumulasiPembayaran();
        $pembayaran->refresh();

        $this->assertEquals(5000000, $pembayaran->total_terbayar);
        $this->assertEquals(0, $pembayaran->sisa_tagihan);
        $this->assertEquals('lunas', $pembayaran->status_pembayaran);
        $this->assertTrue($pembayaran->isLunas());
        $this->assertCount(2, $pembayaran->details);
        $this->assertEquals('TRX-DP-001', $pembayaran->dp->kode_transaksi);
        $this->assertEquals('TRX-LNS-002', $pembayaran->pelunasan->kode_transaksi);
    }
}
