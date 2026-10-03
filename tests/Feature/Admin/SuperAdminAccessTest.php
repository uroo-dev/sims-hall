<?php

namespace Tests\Feature\Admin;

use App\Models\Facility;
use App\Models\PaketPeminjaman;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SuperAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    private Facility $facility;

    private PaketPeminjaman $paket;

    private Peminjaman $peminjaman;

    private Pembayaran $pembayaran;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        $this->facility = Facility::create([
            'judul' => 'Kursi Lipat 100 Pcs',
            'deskripsi' => 'Kursi lipat besi hitam',
        ]);

        $this->paket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Gold Aula',
            'kategori' => 'unggulan',
            'harga' => 3000000,
            'harga_dp' => 1000000,
        ]);
        $this->paket->facilities()->sync([$this->facility->id]);

        $this->peminjaman = Peminjaman::create([
            'paket_peminjaman_id' => $this->paket->id,
            'nama' => 'PT Super Admin Test',
            'email_instansi' => 'instansi@test.com',
            'tanggal_mulai' => now()->addDays(7),
            'tanggal_selesai' => now()->addDays(7)->addHours(5),
            'status' => 'pending',
        ]);

        $this->pembayaran = Pembayaran::create([
            'peminjaman_id' => $this->peminjaman->id,
            'kode_pembayaran' => 'PAY-TEST-001',
            'total_tagihan' => 3000000,
            'total_terbayar' => 0,
            'sisa_tagihan' => 3000000,
            'status_pembayaran' => 'pending',
        ]);
    }

    public function test_super_admin_can_read_all_modules(): void
    {
        $this->actingAs($this->superAdmin);

        $this->get(route('dashboard'))->assertRedirect(route('admin.peminjaman.dashboard'));
        $this->get(route('admin.peminjaman.dashboard'))->assertOk();
        $this->get(route('admin.fasilitas.index'))->assertOk();
        $this->get(route('admin.paket.index'))->assertOk();
        $this->get(route('admin.peminjaman.index'))->assertOk();
        $this->get(route('admin.peminjaman.show', $this->peminjaman->id))->assertOk();
        $this->get(route('admin.peminjaman.export-pdf'))->assertOk();
        $this->get(route('admin.laporan.index'))->assertOk();
        $this->get(route('admin.laporan.pdf'))->assertOk();
        $this->get(route('admin.payment-configuration.index'))->assertOk();
    }

    public function test_super_admin_can_update_payment_configuration(): void
    {
        $this->actingAs($this->superAdmin);

        $response = $this->put(route('admin.payment-configuration.update'), [
            'jatuh_tempo_dp_jam' => 24,
            'jatuh_tempo_pelunasan_jam' => 24,
            'minimal_hari_booking' => 3,
            'persentase_dp' => 30,
            'persentase_pengembalian_batal' => 75,
            'bank_utama' => 'Bank Jateng',
            'norek_utama' => '1234567890',
            'atas_nama_utama' => 'SMKN 2 Karanganyar Aula',
            'catatan_pembayaran' => 'Harap transfer sesuai nominal',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_super_admin_can_mutate_fasilitas(): void
    {
        $this->actingAs($this->superAdmin);

        // Store
        $resStore = $this->post(route('admin.fasilitas.store'), [
            'judul' => 'Fasilitas Baru',
            'deskripsi' => 'Deskripsi',
        ]);
        $resStore->assertRedirect(route('admin.fasilitas.index'));
        $this->assertDatabaseHas('facilities', ['judul' => 'Fasilitas Baru']);

        // Update
        $resUpdate = $this->put(route('admin.fasilitas.update', $this->facility->id), [
            'judul' => 'Fasilitas Diubah',
            'deskripsi' => 'Deskripsi diubah',
        ]);
        $resUpdate->assertRedirect(route('admin.fasilitas.index'));
        $this->assertDatabaseHas('facilities', [
            'id' => $this->facility->id,
            'judul' => 'Fasilitas Diubah',
        ]);

        // Destroy
        $newFacility = Facility::create([
            'judul' => 'Fasilitas Dihapus',
            'deskripsi' => 'Akan dihapus',
        ]);
        $resDestroy = $this->delete(route('admin.fasilitas.destroy', $newFacility->id));
        $resDestroy->assertRedirect(route('admin.fasilitas.index'));
        $this->assertDatabaseMissing('facilities', ['id' => $newFacility->id]);
    }

    public function test_super_admin_can_mutate_paket(): void
    {
        $this->actingAs($this->superAdmin);

        // Store
        $resStore = $this->post(route('admin.paket.store'), [
            'nama_paket' => 'Paket Baru',
            'kategori' => 'standar 1',
            'harga' => 1500000,
            'facility_ids' => [$this->facility->id],
        ]);
        $resStore->assertRedirect(route('admin.paket.index'));
        $this->assertDatabaseHas('paket_peminjamans', ['nama_paket' => 'Paket Baru']);

        // Update
        $resUpdate = $this->put(route('admin.paket.update', $this->paket->id), [
            'nama_paket' => 'Paket Update',
            'kategori' => 'unggulan',
            'harga' => 3500000,
            'facility_ids' => [$this->facility->id],
        ]);
        $resUpdate->assertRedirect(route('admin.paket.index'));
        $this->assertDatabaseHas('paket_peminjamans', [
            'id' => $this->paket->id,
            'nama_paket' => 'Paket Update',
        ]);

        // Destroy
        $unlinkedPaket = PaketPeminjaman::create([
            'nama_paket' => 'Paket Dihapus',
            'kategori' => 'terjangkau',
            'harga' => 1000000,
        ]);
        $resDestroy = $this->delete(route('admin.paket.destroy', $unlinkedPaket->id));
        $resDestroy->assertRedirect(route('admin.paket.index'));
        $this->assertDatabaseMissing('paket_peminjamans', ['id' => $unlinkedPaket->id]);
    }

    public function test_super_admin_can_mutate_peminjaman(): void
    {
        $this->actingAs($this->superAdmin);

        // Cancel
        $resCancel = $this->post(route('admin.peminjaman.cancel', $this->peminjaman->id), [
            'alasan_pembatalan' => 'Alasan pembatalan oleh super admin',
        ]);
        $resCancel->assertRedirect();
        $this->assertDatabaseHas('peminjamans', [
            'id' => $this->peminjaman->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_super_admin_cannot_approve_or_reject_in_kepala_sekolah_panel(): void
    {
        $this->actingAs($this->superAdmin);

        $resApprove = $this->post(route('kepala-sekolah.peminjaman.approve', $this->peminjaman->id));
        $resApprove->assertForbidden();

        $resReject = $this->post(route('kepala-sekolah.peminjaman.reject', $this->peminjaman->id), [
            'alasan_penolakan' => 'Ditolak super admin',
        ]);
        $resReject->assertForbidden();
    }

    public function test_super_admin_sidebar_has_no_duplicate_peminjaman_links(): void
    {
        $this->actingAs($this->superAdmin);

        $res = $this->get(route('admin.peminjaman.dashboard'));
        $res->assertOk();

        // Pastikan hanya terdapat 1 tautan "Daftar Peminjaman" pada sidebar
        $content = $res->getContent();
        $occurrences = substr_count($content, 'Daftar Peminjaman');
        $this->assertEquals(1, $occurrences, 'Menu Daftar Peminjaman tidak boleh terduplikasi pada panel Super Admin');
    }
}
