<?php

namespace Tests\Feature;

use App\Models\DetailPembayaran;
use App\Models\Facility;
use App\Models\Fitur;
use App\Models\PaymentConfiguration;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomPaketPeminjamanTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $pelanggan;

    private Facility $facility1;

    private Facility $facility2;

    private Facility $facility3;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_aula',
        ]);

        Fitur::create([
            'user_id' => $this->admin->id,
            'nama_fitur' => 'aula',
        ]);

        $this->pelanggan = User::factory()->create([
            'name' => 'Ahmad Pelanggan',
            'email' => 'ahmad@example.com',
            'role' => 'pelanggan',
        ]);

        $this->facility1 = Facility::create([
            'judul' => 'Sound System & Mic Wireless',
            'kategori' => 'Audio',
            'deskripsi' => 'Full audio standard aula',
        ]);

        $this->facility2 = Facility::create([
            'judul' => 'Lighting Panggung & Spot LED',
            'kategori' => 'Lighting',
            'deskripsi' => 'Set lighting panggung pesta',
        ]);

        $this->facility3 = Facility::create([
            'judul' => 'Kursi VIP 200 Unit',
            'kategori' => 'Mebel',
            'deskripsi' => 'Kursi berbungkus cover putih',
        ]);

        PaymentConfiguration::current()->update([
            'minimal_hari_booking' => 3,
            'persentase_dp' => 30,
            'batas_waktu_dp_hari' => 2,
            'batas_waktu_pelunasan_hari' => 3,
            'bank_utama' => 'Bank Mandiri',
            'norek_utama' => '138-00-1234567-8',
            'an_utama' => 'SMK Negeri 2 Kra',
        ]);
    }

    public function test_pelanggan_dapat_melihat_halaman_pengajuan_mode_custom(): void
    {
        $response = $this->actingAs($this->pelanggan)
            ->get(route('customer.peminjaman.create', ['custom' => 1]));

        $response->assertStatus(200);
        $response->assertSee('Sound System &amp; Mic Wireless', false);
        $response->assertSee('Lighting Panggung &amp; Spot LED', false);
        $response->assertSee('Kursi VIP 200 Unit');
        $response->assertSee('Paket Custom');
    }

    public function test_pelanggan_dapat_mengajukan_paket_custom_dengan_memilih_fasilitas(): void
    {
        $payload = [
            'is_custom' => '1',
            'facility_ids' => [$this->facility1->id, $this->facility2->id],
            'nama' => 'Ahmad Pelanggan',
            'email_instansi' => 'organisasi@example.com',
            'tanggal_mulai' => Carbon::now()->addDays(15)->format('Y-m-d H:i:s'),
            'tanggal_selesai' => Carbon::now()->addDays(15)->addHours(8)->format('Y-m-d H:i:s'),
            'catatan' => 'Kebutuhan panggung dan sound khusus seminar',
        ];

        $response = $this->actingAs($this->pelanggan)
            ->post(route('customer.peminjaman.store'), $payload);

        $peminjaman = Peminjaman::where('email_instansi', 'organisasi@example.com')->first();
        $this->assertNotNull($peminjaman);

        $response->assertRedirect(route('customer.pembayaran.show', $peminjaman->pembayaran->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('peminjamans', [
            'nama' => 'Ahmad Pelanggan',
            'email_instansi' => 'organisasi@example.com',
            'is_custom' => 1,
            'paket_peminjaman_id' => null,
            'status' => 'pending',
        ]);

        $this->assertCount(2, $peminjaman->facilities);
        $this->assertTrue($peminjaman->facilities->contains($this->facility1));
        $this->assertTrue($peminjaman->facilities->contains($this->facility2));
        $this->assertFalse($peminjaman->facilities->contains($this->facility3));

        // Memastikan tagihan awal dibuat 0 dan menunggu admin menentukan harga
        $this->assertDatabaseHas('pembayarans', [
            'peminjaman_id' => $peminjaman->id,
            'total_tagihan' => 0,
            'status_pembayaran' => 'pending',
        ]);
    }

    public function test_validasi_pengajuan_custom_membutuhkan_minimal_satu_fasilitas(): void
    {
        $payload = [
            'is_custom' => '1',
            // facility_ids sengaja kosong
            'nama' => 'Ahmad Pelanggan',
            'email_instansi' => 'organisasi@example.com',
            'tanggal_mulai' => Carbon::now()->addDays(15)->format('Y-m-d H:i:s'),
            'tanggal_selesai' => Carbon::now()->addDays(15)->addHours(4)->format('Y-m-d H:i:s'),
        ];

        $response = $this->actingAs($this->pelanggan)
            ->post(route('customer.peminjaman.store'), $payload);

        $response->assertSessionHasErrors('facility_ids');
        $this->assertDatabaseMissing('peminjamans', [
            'email_instansi' => 'organisasi@example.com',
        ]);
    }

    public function test_pelanggan_tidak_dapat_melakukan_pembayaran_sebelum_admin_menetapkan_harga(): void
    {
        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => null,
            'is_custom' => true,
            'harga_custom' => null,
            'nama' => 'Ahmad Pelanggan',
            'email_instansi' => 'ahmad@example.com',
            'tanggal_mulai' => Carbon::now()->addDays(10),
            'tanggal_selesai' => Carbon::now()->addDays(10)->addHours(6),
            'status' => 'pending',
        ]);

        $peminjaman->facilities()->sync([$this->facility1->id]);

        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'INV-CUSTOM-001',
            'total_tagihan' => 0,
            'status_pembayaran' => 'pending',
        ]);

        $file = UploadedFile::fake()->create('bukti.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($this->pelanggan)
            ->post(route('customer.pembayaran.bayar', $pembayaran->id), [
                'tipe_pembayaran' => 'dp',
                'bank_tujuan' => 'Bank Mandiri',
                'bank_pengirim' => 'Bank BCA',
                'norek_pengirim' => '1234567890',
                'atas_nama_pengirim' => 'Ahmad Pelanggan',
                'jumlah_bayar' => 500000,
                'tanggal_bayar' => Carbon::now()->format('Y-m-d'),
                'bukti_pembayaran' => $file,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString('belum ditetapkan oleh Admin Aula', session('error'));
    }

    public function test_admin_aula_dapat_menetapkan_harga_paket_custom(): void
    {
        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => null,
            'is_custom' => true,
            'harga_custom' => null,
            'nama' => 'Ahmad Pelanggan',
            'email_instansi' => 'ahmad@example.com',
            'tanggal_mulai' => Carbon::now()->addDays(10),
            'tanggal_selesai' => Carbon::now()->addDays(10)->addHours(8),
            'status' => 'pending',
        ]);

        $peminjaman->facilities()->sync([$this->facility1->id, $this->facility2->id]);

        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'INV-CUSTOM-002',
            'total_tagihan' => 0,
            'status_pembayaran' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.peminjaman.set-harga-custom', $peminjaman->id), [
                'harga' => 3500000,
                'catatan_harga' => 'Harga disesuaikan dengan kebutuhan sound dan lighting konser',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $peminjaman->refresh();
        $this->assertEquals(3500000, (float) $peminjaman->harga_custom);

        $pembayaran->refresh();
        $this->assertEquals(3500000, (float) $pembayaran->total_tagihan);
        $this->assertEquals(3500000, (float) $pembayaran->sisa_tagihan);
        $this->assertNotNull($pembayaran->jatuh_tempo_dp);
    }

    public function test_pelanggan_dapat_melakukan_pembayaran_setelah_harga_custom_ditetapkan(): void
    {
        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => null,
            'is_custom' => true,
            'harga_custom' => 3000000,
            'nama' => 'Ahmad Pelanggan',
            'email_instansi' => 'ahmad@example.com',
            'tanggal_mulai' => Carbon::now()->addDays(10),
            'tanggal_selesai' => Carbon::now()->addDays(10)->addHours(7),
            'status' => 'pending',
        ]);

        $peminjaman->facilities()->sync([$this->facility1->id]);

        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'INV-CUSTOM-003',
            'total_tagihan' => 3000000,
            'sisa_tagihan' => 3000000,
            'status_pembayaran' => 'pending',
            'jatuh_tempo_dp' => Carbon::now()->addDays(2),
        ]);

        $file = UploadedFile::fake()->create('bukti_dp.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($this->pelanggan)
            ->post(route('customer.pembayaran.bayar', $pembayaran->id), [
                'tipe_pembayaran' => 'dp',
                'bank_tujuan' => 'Bank Mandiri',
                'bank_pengirim' => 'Bank BCA',
                'norek_pengirim' => '987654321',
                'atas_nama_pengirim' => 'Ahmad Pelanggan',
                'jumlah_bayar' => 900000,
                'tanggal_bayar' => Carbon::now()->format('Y-m-d'),
                'bukti_pembayaran' => $file,
            ]);

        $response->assertRedirect(route('customer.pembayaran.show', $pembayaran->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('detail_pembayarans', [
            'pembayaran_id' => $pembayaran->id,
            'tipe_pembayaran' => 'dp',
            'jumlah_bayar' => 900000,
            'status' => 'pending',
        ]);
    }

    public function test_harga_paket_custom_tidak_bisa_diupdate_jika_sudah_ada_pembayaran_terverifikasi(): void
    {
        $peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => null,
            'is_custom' => true,
            'harga_custom' => 3000000,
            'nama' => 'Ahmad Pelanggan',
            'email_instansi' => 'ahmad@example.com',
            'tanggal_mulai' => Carbon::now()->addDays(10),
            'tanggal_selesai' => Carbon::now()->addDays(10)->addHours(8),
            'status' => 'pending',
        ]);

        $pembayaran = Pembayaran::create([
            'peminjaman_id' => $peminjaman->id,
            'kode_pembayaran' => 'INV-CUSTOM-004',
            'total_tagihan' => 3000000,
            'total_terbayar' => 900000,
            'sisa_tagihan' => 2100000,
            'status_pembayaran' => 'partial',
        ]);

        DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'kode_transaksi' => 'TRX-DP-202610-001',
            'tipe_pembayaran' => 'dp',
            'bank_tujuan' => 'Bank Mandiri',
            'bank_pengirim' => 'Bank BCA',
            'norek_pengirim' => '1234567890',
            'atas_nama_pengirim' => 'Ahmad Pelanggan',
            'jumlah_bayar' => 900000,
            'tanggal_bayar' => Carbon::now()->toDateString(),
            'bukti_pembayaran' => 'bukti_pembayaran/sample.jpg',
            'status' => 'verified',
        ]);

        $this->assertTrue($peminjaman->hasVerifiedPayment());

        // Admin mencoba mengubah harga paket custom
        $response = $this->actingAs($this->admin)
            ->post(route('admin.peminjaman.set-harga-custom', $peminjaman->id), [
                'harga' => 4500000,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString('sudah ada pembayaran yang terverifikasi', session('error'));

        $peminjaman->refresh();
        $this->assertEquals(3000000, (float) $peminjaman->harga_custom);
    }
}
