<?php

namespace Tests\Feature;

use App\Models\Dudi;
use App\Models\Guru;
use App\Models\Lowongan;
use App\Models\PenempatanPkl;
use App\Models\Siswa;
use App\Models\SuratPengajuan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPklBkkSeparatedTest extends TestCase
{
    use RefreshDatabase;

    private function buatDudi(string $nama, array $extra = []): Dudi
    {
        return Dudi::create(array_merge([
            'nama_dudi' => $nama,
            'alamat' => 'Jl. Lawu No. 120, Jaten',
            'kota' => 'Karanganyar',
            'bidang_usaha' => 'Teknologi Informasi & Rekayasa Perangkat Lunak',
            'kontak_person' => 'Bapak Joko',
            'no_hp' => '081234567890',
            'kuota_maksimal' => 8,
            'is_mitra_resmi' => true,
            'tampil_di_landing' => true,
            'program_1' => 'Web Application Developer',
            'program_2' => 'Mobile App Support',
            'deskripsi' => 'Perusahaan pengembangan perangkat lunak mitra industri resmi SMKN 2 Karanganyar.',
        ], $extra));
    }

    private function buatLowongan(array $extra = []): Lowongan
    {
        return Lowongan::create(array_merge([
            'nama_perusahaan' => 'PT Karanganyar Digital Solusindo',
            'posisi' => 'Fullstack Web Developer',
            'tipe' => Lowongan::TIPE_PEKERJAAN,
            'jurusan_sesuai' => 'Rekayasa Perangkat Lunak',
            'deskripsi' => "Kualifikasi:\n- Lulusan SMK RPL\n- Menguasai PHP & Laravel\n- Mampu bekerja dalam tim",
            'link_daftar' => 'https://karir.example.com/apply/1',
            'deadline' => now()->addDays(14)->toDateString(),
            'is_active' => true,
        ], $extra));
    }

    // =========================================================================
    // PENGUJIAN HALAMAN PKL (Praktik Kerja Lapangan)
    // =========================================================================

    public function test_halaman_pkl_dapat_diakses_dan_menampilkan_komponen_utama(): void
    {
        $dudi = $this->buatDudi('PT Mega Kreasi Digital');

        $response = $this->get(route('pkl'));

        $response->assertOk();
        $response->assertViewIs('Public.pkl');
        $response->assertSee('Praktik Kerja Lapangan');
        $response->assertSee('Kompetensi Keahlian');
        $response->assertSee('Teknik Pemesinan');
        $response->assertSee('Rekayasa Perangkat Lunak');
        $response->assertSee('PT Mega Kreasi Digital');
        $response->assertSee(route('pkl.detail', $dudi->id));
        $response->assertDontSee('Mitra DUDI Aktif');
        $response->assertDontSee('Belum Terdaftar PKL');
    }

    public function test_halaman_pkl_hanya_menampilkan_mitra_yang_diizinkan_tampil(): void
    {
        $this->buatDudi('PT Mitra Resmi Publik', ['tampil_di_landing' => true]);
        $this->buatDudi('PT Rahasia Internal', ['tampil_di_landing' => false]);

        $response = $this->get(route('pkl'));

        $response->assertOk();
        $response->assertSee('PT Mitra Resmi Publik');
        $response->assertDontSee('PT Rahasia Internal');
    }

    public function test_halaman_detail_pkl_menampilkan_profil_mitra_dan_siswa_aktif(): void
    {
        $dudi = $this->buatDudi('PT Nasmoco Solo');

        $guru = Guru::create([
            'nip' => '198901012015011003',
            'nama' => 'Hartanto, S.T.',
            'jurusan' => 'Teknik Ototronik',
        ]);

        $siswa = Siswa::create([
            'nis' => '2402001',
            'nama' => 'Andi Wijaya',
            'kelas' => 'XII',
            'jurusan' => 'Teknik Ototronik',
        ]);

        $surat = SuratPengajuan::create([
            'nomor_surat' => '421.5/099/2026',
            'dudi_id' => $dudi->id,
            'tanggal_surat' => now()->toDateString(),
            'tgl_mulai_pkl' => now()->startOfMonth()->toDateString(),
            'tgl_selesai_pkl' => now()->addMonths(3)->toDateString(),
            'status_surat' => 'disetujui',
        ]);

        PenempatanPkl::create([
            'surat_pengajuan_id' => $surat->id,
            'siswa_id' => $siswa->id,
            'dudi_id' => $dudi->id,
            'guru_id' => $guru->id,
            'status_penempatan' => PenempatanPkl::STATUS_FIX,
        ]);

        $response = $this->get(route('pkl.detail', $dudi->id));

        $response->assertOk();
        $response->assertViewIs('Public.pkl-detail');
        $response->assertSee('PT Nasmoco Solo');
        $response->assertSee('Andi Wijaya');
        $response->assertSee('Teknik Ototronik');
        $response->assertSee('Berlangsung');
        $response->assertSee('Siswa Aktif PKL');
        $response->assertSee('Program Tersedia');
        $response->assertSee(route('pkl'));
    }

    public function test_detail_mitra_yang_tidak_tampil_di_landing_menghasilkan_404(): void
    {
        $dudi = $this->buatDudi('PT Tersembunyi', ['tampil_di_landing' => false]);

        $response = $this->get(route('pkl.detail', $dudi->id));

        $response->assertNotFound();
    }

    // =========================================================================
    // PENGUJIAN HALAMAN BKK (Bursa Kerja Khusus)
    // =========================================================================

    public function test_halaman_bkk_dapat_diakses_dan_menampilkan_lowongan_aktif(): void
    {
        $dudi = $this->buatDudi('PT Maju Bersama');
        $lowongan = $this->buatLowongan([
            'dudi_id' => $dudi->id,
            'posisi' => 'Staff Quality Control',
        ]);

        $response = $this->get(route('bkk'));

        $response->assertOk();
        $response->assertViewIs('Public.bkk');
        $response->assertSee('Bursa Kerja Khusus');
        $response->assertSee('Staff Quality Control');
        $response->assertSee(route('bkk.detail', $lowongan->id));
        $response->assertDontSee('Lowongan Aktif');
        $response->assertDontSee('Terverifikasi BKK');
    }

    public function test_halaman_bkk_tidak_menampilkan_lowongan_kedaluwarsa_atau_nonaktif(): void
    {
        $this->buatLowongan([
            'posisi' => 'Teknisi Kedaluwarsa',
            'deadline' => now()->subDay()->toDateString(),
            'is_active' => true,
        ]);

        $this->buatLowongan([
            'posisi' => 'Operator Nonaktif',
            'deadline' => now()->addDays(5)->toDateString(),
            'is_active' => false,
        ]);

        $response = $this->get(route('bkk'));

        $response->assertOk();
        $response->assertDontSee('Teknisi Kedaluwarsa');
        $response->assertDontSee('Operator Nonaktif');
    }

    public function test_halaman_detail_bkk_menampilkan_kualifikasi_dan_tautan_lamaran(): void
    {
        $dudi = $this->buatDudi('PT Indaco Warna Dunia');
        $lowongan = $this->buatLowongan([
            'dudi_id' => $dudi->id,
            'posisi' => 'Operator Produksi Cat',
            'link_daftar' => 'https://indaco.example.com/apply',
        ]);

        $response = $this->get(route('bkk.detail', $lowongan->id));

        $response->assertOk();
        $response->assertViewIs('Public.bkk-detail');
        $response->assertSee('Operator Produksi Cat');
        $response->assertSee('PT Indaco Warna Dunia');
        $response->assertSee('Profil Perusahaan');
        $response->assertSee('https://indaco.example.com/apply');
        $response->assertSee(route('bkk'));
    }

    public function test_detail_lowongan_nonaktif_menghasilkan_404(): void
    {
        $lowongan = $this->buatLowongan([
            'posisi' => 'Posisi Tidak Aktif',
            'is_active' => false,
        ]);

        $response = $this->get(route('bkk.detail', $lowongan->id));

        $response->assertNotFound();
    }

    public function test_header_publik_memiliki_tautan_ke_halaman_pkl_dan_bkk(): void
    {
        $response = $this->get(route('landing'));

        $response->assertOk();
        $response->assertSee(route('pkl'), false);
        $response->assertSee(route('bkk'), false);
        $response->assertSee('PKL');
        $response->assertSee('Lowongan Kerja');
    }
}
