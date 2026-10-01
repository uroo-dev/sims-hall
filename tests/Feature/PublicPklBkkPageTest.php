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

/**
 * Halaman publik /pkl-bkk.
 *
 * Fokus: halaman ini memakai data publik dari modul PKL & BKK, jadi dua
 * aturan yang diuji adalah (1) tidak ada data internal yang bocor ke publik,
 * dan (2) halaman tetap hidup meski datanya kosong.
 */
class PublicPklBkkPageTest extends TestCase
{
    use RefreshDatabase;

    private function buatDudi(string $nama, array $extra = []): Dudi
    {
        return Dudi::create(array_merge([
            'nama_dudi' => $nama,
            'alamat' => 'Jl. Slamet Riyadi No. 145',
            'kota' => 'Surakarta',
            'bidang_usaha' => 'Pengembangan Perangkat Lunak',
            'kontak_person' => 'Bapak Wanto',
            'no_hp' => '08123456789',
            'kuota_maksimal' => 10,
            'is_mitra_resmi' => true,
            'tampil_di_landing' => true,
        ], $extra));
    }

    private function buatLowongan(array $extra = []): Lowongan
    {
        return Lowongan::create(array_merge([
            'nama_perusahaan' => 'PT Contoh Teknologi',
            'posisi' => 'Junior Frontend Developer',
            'tipe' => Lowongan::TIPE_PEKERJAAN,
            'jurusan_sesuai' => Lowongan::SEMUA_JURUSAN,
            'deskripsi' => 'Deskripsi lowongan untuk keperluan pengujian halaman publik.',
            'link_daftar' => 'https://example.com/daftar',
            'deadline' => now()->addDays(10)->toDateString(),
            'is_active' => true,
        ], $extra));
    }

    public function test_halaman_pkl_bkk_publik_dapat_diakses_tanpa_login(): void
    {
        $this->get(route('pkl-bkk'))
            ->assertOk()
            ->assertViewIs('Public.pkl-bkk')
            // escape=false: teks '&' sudah ter-escape di HTML jadi jangan di-escape dua kali.
            ->assertSee('PKL &amp; BKK', false);
    }

    public function test_halaman_pkl_bkk_menampilkan_mitra_dan_lowongan(): void
    {
        $dudi = $this->buatDudi('PT Sekaruna Prima Teknologi');
        $this->buatLowongan(['dudi_id' => $dudi->id, 'nama_perusahaan' => 'PT Contoh Teknologi']);

        $this->get(route('pkl-bkk'))
            ->assertOk()
            ->assertSee('PT Sekaruna Prima Teknologi')
            ->assertSee('Junior Frontend Developer')
            ->assertDontSee('Belum ada mitra DUDI');
    }

    public function test_mitra_yang_belum_disetujui_tidak_ditampilkan(): void
    {
        // DUDI baru diinput BKK selalu tampil_di_landing = false sampai disetujui.
        $this->buatDudi('PT Rahasia Belum Disetujui', ['tampil_di_landing' => false]);

        $this->get(route('pkl-bkk'))
            ->assertOk()
            ->assertDontSee('PT Rahasia Belum Disetujui');
    }

    public function test_lowongan_nonaktif_dan_kedaluwarsa_tidak_ditampilkan(): void
    {
        $this->buatLowongan([
            'posisi' => 'Posisi Sudah Lewat',
            'deadline' => now()->subDay()->toDateString(),
        ]);

        $this->buatLowongan([
            'posisi' => 'Posisi Nonaktif',
            'is_active' => false,
        ]);

        $this->get(route('pkl-bkk'))
            ->assertOk()
            ->assertDontSee('Posisi Sudah Lewat')
            ->assertDontSee('Posisi Nonaktif')
            ->assertSee('Belum ada lowongan yang aktif');
    }

    public function test_rekap_menampilkan_angka_penempatan_fix(): void
    {
        $dudi = $this->buatDudi('PT Sekaruna Prima Teknologi');

        $guru = Guru::create([
            'nip' => '198705122011011002',
            'nama' => 'Budi Santoso, S.Pd.',
            'jurusan' => 'RPL',
        ]);

        $siswa = Siswa::create([
            'nis' => '2401001',
            'nama' => 'Rizky Pratama',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
        ]);

        $surat = SuratPengajuan::create([
            'nomor_surat' => '421.5/017/2026',
            'dudi_id' => $dudi->id,
            'tanggal_surat' => now()->subDays(3)->toDateString(),
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

        $this->get(route('pkl-bkk'))
            ->assertOk()
            ->assertSee('Siswa PKL (FIX)')
            // Kuota 10, terisi 1 -> kapasitas tampil sebagai "1 dari 10 kuota terisi".
            ->assertSee('dari')
            ->assertSee('kuota terisi');
    }

    public function test_tanggal_deadline_dicetak_dalam_bahasa_indonesia(): void
    {
        $deadline = now()->addDays(10);
        $this->buatLowongan(['deadline' => $deadline->toDateString()]);

        $this->get(route('pkl-bkk'))
            ->assertOk()
            ->assertSee($deadline->locale('id')->translatedFormat('d F Y'))
            // Nama bulan Indonesia, bukan "Oct" bawaan locale en.
            ->assertDontSee('Oct');
    }

    public function test_tampilan_kosong_ditangani_ketika_belum_ada_data(): void
    {
        $this->get(route('pkl-bkk'))
            ->assertOk()
            ->assertSee('Belum ada mitra DUDI')
            ->assertSee('Belum ada lowongan yang aktif');
    }

    public function test_landing_menghubungkan_ke_halaman_pkl_bkk(): void
    {
        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('Lihat Semua Mitra &amp; Lowongan PKL/BKK', false);
    }

    public function test_nav_publik_menghubungkan_ke_halaman_pkl_bkk(): void
    {
        $this->get(route('landing'))
            ->assertOk()
            ->assertSee(route('pkl-bkk'), false);
    }
}
