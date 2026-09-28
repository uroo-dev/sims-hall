<?php

namespace Tests\Feature;

use App\Models\Dudi;
use App\Models\Guru;
use App\Models\Lowongan;
use App\Models\PenempatanPkl;
use App\Models\Siswa;
use App\Models\SuratPengajuan;
use Database\Seeders\BkkSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BkkSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seed_membuat_data_dasar_sesuai_kebutuhan(): void
    {
        $this->seed(BkkSeeder::class);

        $this->assertSame(2, Guru::count());
        $this->assertSame(5, Siswa::count());
        $this->assertSame(3, Dudi::count());
        $this->assertSame(2, Lowongan::count());

        $this->assertSame(1, PenempatanPkl::count());
        $this->assertSame(1, PenempatanPkl::where('status_penempatan', PenempatanPkl::STATUS_FIX)->count());
        $this->assertSame(1, SuratPengajuan::count());
    }

    public function test_seed_bersifat_idempotent(): void
    {
        $this->seed(BkkSeeder::class);
        $sebelum = [
            'guru' => Guru::count(),
            'siswa' => Siswa::count(),
            'dudi' => Dudi::count(),
            'lowongan' => Lowongan::count(),
            'surat' => SuratPengajuan::count(),
            'penempatan' => PenempatanPkl::count(),
        ];
        $nomorSuratAwal = SuratPengajuan::pluck('nomor_surat')->all();

        // Jalankan 2x lagi
        $this->seed(BkkSeeder::class);
        $this->seed(BkkSeeder::class);

        $sesudah = [
            'guru' => Guru::count(),
            'siswa' => Siswa::count(),
            'dudi' => Dudi::count(),
            'lowongan' => Lowongan::count(),
            'surat' => SuratPengajuan::count(),
            'penempatan' => PenempatanPkl::count(),
        ];

        $this->assertSame($sebelum, $sesudah, 'Menjalankan seeder berulang tidak boleh menambah data.');
        $this->assertSame($nomorSuratAwal, SuratPengajuan::pluck('nomor_surat')->all(), 'Nomor surat harus stabil.');
    }

    public function test_lowongan_seed_punya_link_eksternal_dan_deadline_aktif(): void
    {
        $this->seed(BkkSeeder::class);

        $lowongan = Lowongan::where('is_active', true)->firstOrFail();

        $this->assertNotEmpty($lowongan->link_daftar);
        $this->assertNotEmpty($lowongan->deadline);
        $this->assertFalse($lowongan->sudah_lewat);
        $this->assertTrue(Lowongan::active()->whereKey($lowongan->id)->exists());
    }

    public function test_user_seeder_membuat_akun_bkk_dengan_role_bkk(): void
    {
        $this->seed(UserSeeder::class);

        $this->assertDatabaseHas('users', ['username' => 'bkk', 'role' => 'bkk']);
        $this->assertDatabaseHas('users', ['username' => 'uroo', 'role' => 'bkk']);
        $this->assertDatabaseHas('users', ['username' => 'root', 'role' => 'super_admin']);
    }

    public function test_dudi_seed_baru_tidak_otomatis_tayang_di_landing(): void
    {
        $this->seed(BkkSeeder::class);

        $this->assertGreaterThanOrEqual(1, Dudi::where('tampil_di_landing', true)->count());
        $this->assertSame(0, Dudi::where('is_mitra_resmi', false)->where('tampil_di_landing', true)->count());
    }
}
