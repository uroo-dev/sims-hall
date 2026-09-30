<?php

namespace Tests\Feature\Admin;

use App\Models\DetailPembayaran;
use App\Models\Fitur;
use App\Models\PaketPeminjaman;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanPemasukanTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminAula(): User
    {
        $user = User::factory()->create([
            'username' => 'admin_aula',
            'role' => 'admin',
        ]);

        Fitur::create([
            'user_id' => $user->id,
            'nama_fitur' => 'aula',
        ]);

        return $user;
    }

    private function createKepalaSekolah(): User
    {
        return User::factory()->create([
            'name' => 'Kepala Sekolah',
            'username' => 'kepsek',
            'role' => 'kepala_sekolah',
        ]);
    }

    private function createPelanggan(): User
    {
        return User::factory()->create([
            'username' => 'pelanggan1',
            'role' => 'pelanggan',
        ]);
    }

    private function createDummyData(): array
    {
        $paket1 = PaketPeminjaman::create([
            'nama_paket' => 'Paket Aula Pernikahan Platinum',
            'kategori' => 'unggulan',
            'harga' => 5000000,
            'harga_dp' => 2000000,
            'deskripsi' => 'Paket pernikahan komplit aula SMKN 2 Kra',
        ]);

        $paket2 = PaketPeminjaman::create([
            'nama_paket' => 'Paket Aula Seminar Standar',
            'kategori' => 'standar 1',
            'harga' => 2000000,
            'harga_dp' => 1000000,
            'deskripsi' => 'Paket seminar dan workshop kedinasan',
        ]);

        // Peminjaman 1: LUNAS (5.000.000 terbayar)
        $p1 = Peminjaman::create([
            'paket_peminjaman_id' => $paket1->id,
            'nama' => 'Budi Santoso (Pernikahan)',
            'email_instansi' => 'budi@gmail.com',
            'tanggal_mulai' => Carbon::now()->addDays(5)->setTime(8, 0),
            'tanggal_selesai' => Carbon::now()->addDays(5)->setTime(16, 0),
            'status' => 'approved_final',
        ]);
        $pemb1 = Pembayaran::create([
            'peminjaman_id' => $p1->id,
            'kode_pembayaran' => 'INV-2026-0001',
            'total_tagihan' => 5000000,
            'total_terbayar' => 5000000,
            'total_refund' => 0,
            'sisa_tagihan' => 0,
            'status_pembayaran' => 'lunas',
        ]);
        DetailPembayaran::create([
            'pembayaran_id' => $pemb1->id,
            'kode_transaksi' => 'TRX-DP-001',
            'tipe_pembayaran' => 'dp',
            'jumlah_bayar' => 2000000,
            'status' => 'verified',
            'tanggal_bayar' => Carbon::now()->subDays(2),
        ]);
        DetailPembayaran::create([
            'pembayaran_id' => $pemb1->id,
            'kode_transaksi' => 'TRX-PEL-001',
            'tipe_pembayaran' => 'pelunasan',
            'jumlah_bayar' => 3000000,
            'status' => 'verified',
            'tanggal_bayar' => Carbon::now()->subDay(),
        ]);

        // Peminjaman 2: DP / PARTIAL (1.000.000 terbayar dari 2.000.000)
        $p2 = Peminjaman::create([
            'paket_peminjaman_id' => $paket2->id,
            'nama' => 'Dinas Pendidikan Solo',
            'email_instansi' => 'disdik@solo.go.id',
            'tanggal_mulai' => Carbon::now()->addDays(10)->setTime(9, 0),
            'tanggal_selesai' => Carbon::now()->addDays(10)->setTime(15, 0),
            'status' => 'approved_1',
        ]);
        $pemb2 = Pembayaran::create([
            'peminjaman_id' => $p2->id,
            'kode_pembayaran' => 'INV-2026-0002',
            'total_tagihan' => 2000000,
            'total_terbayar' => 1000000,
            'total_refund' => 0,
            'sisa_tagihan' => 1000000,
            'status_pembayaran' => 'partial',
        ]);
        DetailPembayaran::create([
            'pembayaran_id' => $pemb2->id,
            'kode_transaksi' => 'TRX-DP-002',
            'tipe_pembayaran' => 'dp',
            'jumlah_bayar' => 1000000,
            'status' => 'verified',
            'tanggal_bayar' => Carbon::now(),
        ]);

        return [$paket1, $paket2, $p1, $p2];
    }

    public function test_admin_aula_dapat_mengakses_halaman_laporan_pemasukan(): void
    {
        $admin = $this->createAdminAula();
        $this->createDummyData();

        $response = $this->actingAs($admin)->get(route('admin.laporan.index', ['preset' => 'semua']));

        $response->assertOk();
        $response->assertViewIs('Admin.laporan.index');
        $response->assertSee('Rekapitulasi Pemasukan Sewa Aula');
        $response->assertSee('Budi Santoso (Pernikahan)');
        $response->assertSee('Dinas Pendidikan Solo');
        $response->assertSee('INV-2026-0001');
        $response->assertSee('INV-2026-0002');
    }

    public function test_kepala_sekolah_dapat_mengakses_halaman_laporan_pemasukan(): void
    {
        $kepsek = $this->createKepalaSekolah();
        $this->createDummyData();

        $response = $this->actingAs($kepsek)->get(route('kepala-sekolah.laporan.index', ['preset' => 'semua']));

        $response->assertOk();
        $response->assertViewIs('Admin.laporan.index');
        $response->assertSee('Rekapitulasi Pemasukan Sewa Aula');
        $response->assertSee('Budi Santoso (Pernikahan)');
    }

    public function test_admin_aula_dapat_mengekspor_laporan_ke_format_pdf(): void
    {
        $admin = $this->createAdminAula();
        $this->createDummyData();

        $response = $this->actingAs($admin)->get(route('admin.laporan.pdf', ['preset' => 'semua']));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_kepala_sekolah_dapat_mengekspor_laporan_ke_format_pdf(): void
    {
        $kepsek = $this->createKepalaSekolah();
        $this->createDummyData();

        $response = $this->actingAs($kepsek)->get(route('kepala-sekolah.laporan.pdf', ['preset' => 'semua', 'stream' => '1']));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_pelanggan_tidak_dapat_mengakses_laporan_keuangan_admin_maupun_kepsek(): void
    {
        $pelanggan = $this->createPelanggan();

        $resAdmin = $this->actingAs($pelanggan)->get(route('admin.laporan.index'));
        $resAdmin->assertForbidden();

        $resKepsek = $this->actingAs($pelanggan)->get(route('kepala-sekolah.laporan.index'));
        $resKepsek->assertForbidden();
    }

    public function test_tamu_unauthenticated_diarahkan_ke_halaman_login(): void
    {
        $response = $this->get(route('admin.laporan.index'));
        $response->assertRedirect(route('login'));

        $responseKepsek = $this->get(route('kepala-sekolah.laporan.index'));
        $responseKepsek->assertRedirect(route('login'));
    }

    public function test_filter_status_pembayaran_bekerja_dengan_tepat(): void
    {
        $admin = $this->createAdminAula();
        $this->createDummyData();

        // Filter hanya status 'lunas'
        $responseLunas = $this->actingAs($admin)->get(route('admin.laporan.index', [
            'status_pembayaran' => 'lunas',
            'preset' => 'semua',
        ]));

        $responseLunas->assertOk();
        $responseLunas->assertSee('Budi Santoso (Pernikahan)');
        $responseLunas->assertDontSee('Dinas Pendidikan Solo');

        // Filter hanya status 'partial'
        $responsePartial = $this->actingAs($admin)->get(route('admin.laporan.index', [
            'status_pembayaran' => 'partial',
            'preset' => 'semua',
        ]));

        $responsePartial->assertOk();
        $responsePartial->assertSee('Dinas Pendidikan Solo');
        $responsePartial->assertDontSee('Budi Santoso (Pernikahan)');
    }

    public function test_filter_pencarian_nama_pemohon_bekerja(): void
    {
        $admin = $this->createAdminAula();
        $this->createDummyData();

        $response = $this->actingAs($admin)->get(route('admin.laporan.index', [
            'search' => 'Dinas Pendidikan',
            'preset' => 'semua',
        ]));

        $response->assertOk();
        $response->assertSee('Dinas Pendidikan Solo');
        $response->assertDontSee('Budi Santoso (Pernikahan)');
    }

    public function test_kalkulasi_total_pemasukan_bruto_dan_netto_sesuai(): void
    {
        $admin = $this->createAdminAula();
        [$paket1, $paket2, $p1, $p2] = $this->createDummyData();

        // Total Tagihan = 5.000.000 + 2.000.000 = 7.000.000
        // Total Pemasukan Bruto = 5.000.000 + 1.000.000 = 6.000.000
        // Sisa Tagihan = 0 + 1.000.000 = 1.000.000
        $response = $this->actingAs($admin)->get(route('admin.laporan.index', ['preset' => 'semua']));

        $response->assertOk();
        $response->assertViewHas('stats', function ($stats) {
            return $stats['total_tagihan'] == 7000000
                && $stats['total_pemasukan_bruto'] == 6000000
                && $stats['total_pemasukan_netto'] == 6000000
                && $stats['total_sisa_tagihan'] == 1000000
                && $stats['count_lunas'] == 1
                && $stats['count_partial'] == 1;
        });
    }

    public function test_filter_berdasarkan_paket_bekerja(): void
    {
        $admin = $this->createAdminAula();
        [$paket1, $paket2, $p1, $p2] = $this->createDummyData();

        $response = $this->actingAs($admin)->get(route('admin.laporan.index', [
            'paket_id' => $paket1->id,
            'preset' => 'semua',
        ]));

        $response->assertOk();
        $response->assertSee('Budi Santoso (Pernikahan)');
        $response->assertDontSee('Dinas Pendidikan Solo');
    }

    public function test_filter_berdasarkan_tanggal_sewa_bekerja(): void
    {
        $admin = $this->createAdminAula();
        $this->createDummyData();

        // Tanggal sewa p1 adalah now + 5 days
        $targetDate = Carbon::now()->addDays(5)->toDateString();

        $response = $this->actingAs($admin)->get(route('admin.laporan.index', [
            'filter_by' => 'sewa',
            'custom_range' => 1,
            'preset' => 'custom',
            'tanggal_dari' => $targetDate,
            'tanggal_sampai' => $targetDate,
        ]));

        $response->assertOk();
        $response->assertSee('Budi Santoso (Pernikahan)');
        $response->assertDontSee('Dinas Pendidikan Solo');
    }

    public function test_ekspor_pdf_berhasil_meskipun_tidak_ada_data_yang_cocok(): void
    {
        $admin = $this->createAdminAula();

        $response = $this->actingAs($admin)->get(route('admin.laporan.pdf', [
            'search' => 'DataYangPastiTidakAda12345',
            'preset' => 'semua',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}
