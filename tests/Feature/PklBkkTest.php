<?php

namespace Tests\Feature;

use App\Models\Dudi;
use App\Models\Guru;
use App\Models\Lowongan;
use App\Models\PenempatanPkl;
use App\Models\Siswa;
use App\Models\SuratPengajuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PklBkkTest extends TestCase
{
    use RefreshDatabase;

    private User $bkk;

    private Guru $guru;

    private Dudi $dudi;

    private Siswa $siswaA;

    private Siswa $siswaB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bkk = User::factory()->create([
            'username' => 'bkk',
            'role' => 'bkk',
        ]);

        $this->guru = Guru::create([
            'nip' => '198705122011011002',
            'nama' => 'Budi Santoso, S.Pd.',
            'jurusan' => 'RPL',
        ]);

        $this->dudi = Dudi::create([
            'nama_dudi' => 'PT Sekaruna Prima Teknologi',
            'alamat' => 'Jl. Slamet Riyadi No. 145',
            'kota' => 'Surakarta',
            'bidang_usaha' => 'Pengembangan Perangkat Lunak',
            'kuota_maksimal' => 10,
            'is_mitra_resmi' => true,
            'tampil_di_landing' => true,
        ]);

        $this->siswaA = Siswa::create([
            'nis' => '2401001',
            'nama' => 'Rizky Pratama',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
        ]);

        $this->siswaB = Siswa::create([
            'nis' => '2401002',
            'nama' => 'Putri Anggraini',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
        ]);
    }

    public function test_halaman_bkk_menggunakan_layout_admin(): void
    {
        $this->actingAs($this->bkk)
            ->get(route('pkl.dashboard'))
            ->assertOk()
            ->assertViewIs('Admin.bkk.dashboard')
            ->assertSee('Rekap Status PKL');
    }

    public function test_role_bkk_terbatas_pada_modul_pkl_bkk(): void
    {
        $this->actingAs($this->bkk)->get(route('pkl.dashboard'))->assertOk();

        // Modul milik role lain harus forbidden
        $this->actingAs($this->bkk)->get(route('datamaster.index'))->assertForbidden();
        $this->actingAs($this->bkk)->get(route('admin.peminjaman.dashboard'))->assertForbidden();
    }

    public function test_sidebar_bkk_hanya_menampilkan_modul_pkl_bkk(): void
    {
        $response = $this->actingAs($this->bkk)->get(route('pkl.dashboard'))->assertOk();

        // Menu milik modul lain TIDAK boleh muncul untuk role bkk
        $response->assertDontSee('Data Master Sekolah');
        $response->assertDontSee('Peminjaman Aula');
        $response->assertDontSee('Kepala Sekolah');
        $response->assertDontSee('Kesiswaan');
        $response->assertDontSee('Produk Unggulan');
        $response->assertDontSee('PPDB');

        // Menu PKL & BKK wajib ada. Panel khusus role bkk adalah daftar
        // datar tanpa header section, jadi yang diuji adalah link-nya.
        $response->assertSee(route('pkl.dashboard'), false);
        $response->assertSee(route('pkl.dudi.index'), false);
        $response->assertSee(route('pkl.lowongan.index'), false);
        $response->assertSee(route('pkl.siswa.index'), false);
        $response->assertSee(route('pkl.index'), false);
        $response->assertSee(route('pkl.create'), false);
    }

    public function test_sidebar_admin_melihat_semua_modul(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($admin)->get(route('pkl.dashboard'))->assertOk();

        $response->assertSee('Data Master Sekolah');
        // Header section ada di HTML comment dengan '&' mentah, bukan entity.
        $response->assertSee('PKL & BKK', false);
    }

    public function test_admin_tetap_bisa_mengakses_modul_pkl_bkk(): void
    {
        $admin = User::factory()->create(['role' => 'admin_pklbkk']);

        $this->actingAs($admin)->get(route('pkl.dashboard'))->assertOk();

        // admin_aula tidak boleh mengakses modul PKL & BKK
        $adminAula = User::factory()->create(['role' => 'admin_aula']);
        $this->actingAs($adminAula)->get(route('pkl.dashboard'))->assertForbidden();
    }

    public function test_tamu_tidak_bisa_mengakses_modul_pkl_bkk(): void
    {
        $this->get(route('pkl.dashboard'))->assertRedirect(route('login'));
    }

    public function test_menyimpan_pengajuan_membuat_surat_dan_penempatan(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->bkk)->post(route('pkl.store'), [
            'dudi_id' => $this->dudi->id,
            'siswa_ids' => [$this->siswaA->id, $this->siswaB->id],
            'guru_id' => $this->guru->id,
            'tanggal_surat' => now()->toDateString(),
            'tgl_mulai_pkl' => now()->toDateString(),
            'tgl_selesai_pkl' => now()->addMonths(3)->toDateString(),
        ]);

        $surat = SuratPengajuan::firstOrFail();

        $response->assertRedirect(route('pkl.surat.show', $surat));

        // Nomor surat mengikuti format 421/BKK/{tahun}/{index}
        $this->assertSame('421/BKK/'.now()->year.'/1', $surat->nomor_surat);

        // Satu surat menghasilkan record penempatan untuk tiap siswa terpilih
        $this->assertCount(2, $surat->penempatanPkls);

        $surat->penempatanPkls->each(function (PenempatanPkl $p) use ($surat) {
            $this->assertSame(PenempatanPkl::STATUS_PENGAJUAN, $p->status_penempatan);
            $this->assertSame($surat->id, $p->surat_pengajuan_id);
            $this->assertSame($this->dudi->id, $p->dudi_id);
            $this->assertSame($this->guru->id, $p->guru_id);
        });
    }

    public function test_dudi_baru_dibuat_dengan_tampil_di_landing_false(): void
    {
        Storage::fake('public');

        $this->actingAs($this->bkk)->post(route('pkl.store'), [
            'dudi_baru' => [
                'nama' => 'Toko Berkah Computer',
                'alamat' => 'Jl. Ahmad Yani No. 12',
                'kota' => 'Karanganyar',
                'bidang_usaha' => 'Jasa Service Komputer',
                'kontak_person' => 'Budi Santoso',
                'no_hp' => '08123456789',
                'kuota_maksimal' => 5,
            ],
            'siswa_ids' => [$this->siswaA->id],
            'guru_id' => $this->guru->id,
            'tanggal_surat' => now()->toDateString(),
            'tgl_mulai_pkl' => now()->toDateString(),
            'tgl_selesai_pkl' => now()->addMonths(3)->toDateString(),
        ]);

        $dudiBaru = Dudi::where('nama_dudi', 'Toko Berkah Computer')->firstOrFail();

        $this->assertFalse($dudiBaru->tampil_di_landing, 'DUDI baru harus default tidak tayang di landing');
        $this->assertFalse($dudiBaru->is_mitra_resmi, 'DUDI baru harus default bukan mitra resmi');
    }

    public function test_didu_dan_dudi_baru_tidak_boleh_dikirim_bersamaan(): void
    {
        $this->actingAs($this->bkk)->post(route('pkl.store'), [
            'dudi_id' => $this->dudi->id,
            'dudi_baru' => [
                'nama' => 'Toko Berkah Computer',
                'alamat' => 'Jl. Ahmad Yani No. 12',
                'kota' => 'Karanganyar',
                'bidang_usaha' => 'Jasa Service Komputer',
            ],
            'siswa_ids' => [$this->siswaA->id],
            'guru_id' => $this->guru->id,
            'tanggal_surat' => now()->toDateString(),
            'tgl_mulai_pkl' => now()->toDateString(),
            'tgl_selesai_pkl' => now()->addMonths(3)->toDateString(),
        ])->assertSessionHasErrors('dudi_baru');

        $this->assertSame(0, Dudi::count() - 1, 'DUDI baru tidak boleh ikut dibuat.');
        $this->assertSame(0, SuratPengajuan::count());
    }

    public function test_pengajuan_gagal_jika_tidak_ada_siswa_dipilih(): void
    {
        $this->actingAs($this->bkk)->post(route('pkl.store'), [
            'dudi_id' => $this->dudi->id,
            'siswa_ids' => [],
            'guru_id' => $this->guru->id,
            'tanggal_surat' => now()->toDateString(),
            'tgl_mulai_pkl' => now()->toDateString(),
            'tgl_selesai_pkl' => now()->addMonths(3)->toDateString(),
        ])->assertSessionHasErrors('siswa_ids');

        $this->assertSame(0, SuratPengajuan::count());
    }

    public function test_pengajuan_gagal_jika_tanggal_selesai_sebelum_mulai(): void
    {
        $this->actingAs($this->bkk)->post(route('pkl.store'), [
            'dudi_id' => $this->dudi->id,
            'siswa_ids' => [$this->siswaA->id],
            'guru_id' => $this->guru->id,
            'tanggal_surat' => now()->toDateString(),
            'tgl_mulai_pkl' => now()->addMonth()->toDateString(),
            'tgl_selesai_pkl' => now()->toDateString(),
        ])->assertSessionHasErrors('tgl_selesai_pkl');
    }

    public function test_siswa_yang_sudah_fix_tidak_bisa_diajukan_ulang(): void
    {
        $surat = SuratPengajuan::create([
            'nomor_surat' => '421/BKK/'.now()->year.'/99',
            'dudi_id' => $this->dudi->id,
            'tanggal_surat' => now()->toDateString(),
            'tgl_mulai_pkl' => now()->toDateString(),
            'tgl_selesai_pkl' => now()->addMonths(3)->toDateString(),
        ]);

        PenempatanPkl::create([
            'surat_pengajuan_id' => $surat->id,
            'siswa_id' => $this->siswaA->id,
            'dudi_id' => $this->dudi->id,
            'guru_id' => $this->guru->id,
            'status_penempatan' => PenempatanPkl::STATUS_FIX,
        ]);

        $this->actingAs($this->bkk)->post(route('pkl.store'), [
            'dudi_id' => $this->dudi->id,
            'siswa_ids' => [$this->siswaA->id],
            'guru_id' => $this->guru->id,
            'tanggal_surat' => now()->toDateString(),
            'tgl_mulai_pkl' => now()->toDateString(),
            'tgl_selesai_pkl' => now()->addMonths(3)->toDateString(),
        ])->assertSessionHasErrors('siswa_ids.0');
    }

    public function test_update_status_menjadi_fix(): void
    {
        $p = $this->buatPenempatan();

        $this->actingAs($this->bkk)
            ->patch(route('pkl.penempatan.status', $p), [
                'status_penempatan' => PenempatanPkl::STATUS_FIX,
                'catatan' => 'DUDI menyetujui.',
            ])
            ->assertRedirect();

        $this->assertSame(PenempatanPkl::STATUS_FIX, $p->fresh()->status_penempatan);
        $this->assertTrue($this->siswaA->fresh()->penempatanFix()->exists());
    }

    public function test_update_status_menjadi_ditolak_mengembalikan_siswa_belum_pkl(): void
    {
        $p = $this->buatPenempatan();

        $this->actingAs($this->bkk)
            ->patch(route('pkl.penempatan.status', $p), [
                'status_penempatan' => PenempatanPkl::STATUS_DITOLAK,
            ])
            ->assertRedirect();

        $this->assertSame(PenempatanPkl::STATUS_DITOLAK, $p->fresh()->status_penempatan);
        $this->assertFalse($this->siswaA->fresh()->penempatanFix()->exists());
        $this->assertTrue(
            Siswa::where('nis', $this->siswaA->nis)->belumPkl()->exists(),
            'Siswa yang ditolak harus kembali berstatus Belum Ada Tempat PKL.'
        );
    }

    public function test_status_tidak_bisa_diubah_ke_pengajuan(): void
    {
        $p = $this->buatPenempatan();

        $this->actingAs($this->bkk)
            ->patch(route('pkl.penempatan.status', $p), [
                'status_penempatan' => PenempatanPkl::STATUS_PENGAJUAN,
            ])
            ->assertSessionHasErrors('status_penempatan');
    }

    public function test_acc_landing_menampilkan_dudi_di_halaman_publik(): void
    {
        $dudi = Dudi::create([
            'nama_dudi' => 'CV Jaya Mandiri',
            'alamat' => 'Jl. Adi Sucipto',
            'kota' => 'Karanganyar',
            'bidang_usaha' => 'Elektronika',
            'tampil_di_landing' => false,
        ]);

        $this->actingAs($this->bkk)
            ->patch(route('pkl.dudi.acc-landing', $dudi))
            ->assertRedirect();

        $this->assertTrue($dudi->fresh()->tampil_di_landing);
    }

    public function test_lowongan_crud(): void
    {
        $this->actingAs($this->bkk)->get(route('pkl.lowongan.create'))->assertOk();

        $this->actingAs($this->bkk)->post(route('pkl.lowongan.store'), [
            'dudi_id' => $this->dudi->id,
            'nama_perusahaan' => 'PT Sekaruna Prima Teknologi',
            'posisi' => 'Junior Backend Developer',
            'tipe' => Lowongan::TIPE_PEKERJAAN,
            'jurusan_sesuai' => 'RPL, TKJ',
            'deskripsi' => 'Mencari kandidat yang paham PHP dan MySQL untuk pengembangan backend.',
            'link_daftar' => 'https://forms.glow/contoh',
            'deadline' => now()->addDays(30)->toDateString(),
            'is_active' => 1,
        ])->assertRedirect(route('pkl.lowongan.index'));

        $lowongan = Lowongan::firstOrFail();
        $this->assertSame('RPL, TKJ', $lowongan->jurusan_sesuai);
        $this->assertTrue($lowongan->is_active);

        $this->actingAs($this->bkk)
            ->patch(route('pkl.lowongan.toggle', $lowongan))
            ->assertRedirect();
        $this->assertFalse($lowongan->fresh()->is_active);

        $this->actingAs($this->bkk)
            ->delete(route('pkl.lowongan.destroy', $lowongan))
            ->assertRedirect(route('pkl.lowongan.index'));
        $this->assertSame(0, Lowongan::count());
    }

    public function test_lowongan_gagal_jika_link_bukan_url(): void
    {
        $this->actingAs($this->bkk)->post(route('pkl.lowongan.store'), [
            'nama_perusahaan' => 'PT Test',
            'posisi' => 'Staff',
            'tipe' => Lowongan::TIPE_PEKERJAAN,
            'jurusan_sesuai' => 'Semua Jurusan',
            'deskripsi' => 'Deskripsi lowongan yang cukup panjang untuk lolos validasi minimal.',
            'link_daftar' => 'bukan-url',
            'deadline' => now()->addDays(10)->toDateString(),
        ])->assertSessionHasErrors('link_daftar');
    }

    public function test_api_publik_dudi_hanya_menampilkan_yang_di_acc(): void
    {
        Dudi::create([
            'nama_dudi' => 'DUDI Belum ACC',
            'alamat' => 'Alamat',
            'kota' => 'Solo',
            'bidang_usaha' => 'Usaha',
            'tampil_di_landing' => false,
        ]);

        $response = $this->getJson(route('api.pkl.dudi'));

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data');

        $this->assertSame(
            'PT Sekaruna Prima Teknologi',
            $response->json('data.0.nama_dudi')
        );
    }

    public function test_api_publik_rekap(): void
    {
        $this->getJson(route('api.pkl.rekap'))
            ->assertOk()
            ->assertJsonPath('data.total_siswa', 2)
            ->assertJsonPath('data.siswa_fix', 0);
    }

    public function test_api_publik_lowongan_hanya_aktif_dan_belum_deadline(): void
    {
        Lowongan::create([
            'nama_perusahaan' => 'PT Aktif',
            'posisi' => 'Dev',
            'tipe' => Lowongan::TIPE_PEKERJAAN,
            'jurusan_sesuai' => Lowongan::SEMUA_JURUSAN,
            'deskripsi' => 'Deskripsi lowongan aktif yang panjangnya cukup untuk validasi.',
            'link_daftar' => 'https://example.com/aktif',
            'deadline' => now()->addDays(10)->toDateString(),
            'is_active' => true,
        ]);

        Lowongan::create([
            'nama_perusahaan' => 'PT Expired',
            'posisi' => 'Dev',
            'tipe' => Lowongan::TIPE_PEKERJAAN,
            'jurusan_sesuai' => Lowongan::SEMUA_JURUSAN,
            'deskripsi' => 'Deskripsi lowongan expired yang panjangnya cukup untuk validasi.',
            'link_daftar' => 'https://example.com/expired',
            'deadline' => now()->subDay()->toDateString(),
            'is_active' => true,
        ]);

        $response = $this->getJson(route('api.pkl.lowongan'));

        $response->assertOk()->assertJsonCount(1, 'data');
        $this->assertSame('PT Aktif', $response->json('data.0.nama_perusahaan'));
    }

    public function test_api_publik_status_siswa(): void
    {
        $this->getJson(route('api.pkl.siswa', $this->siswaA->nis))
            ->assertOk()
            ->assertJsonPath('data.nama', 'Rizky Pratama')
            ->assertJsonPath('data.sudah_pkl', false);

        $this->getJson(route('api.pkl.siswa', '9999999'))->assertNotFound();
    }

    public function test_pdf_surat_sesuai_nomor_surat_dan_bisa_didownload(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->bkk)->post(route('pkl.store'), [
            'dudi_id' => $this->dudi->id,
            'siswa_ids' => [$this->siswaA->id],
            'guru_id' => $this->guru->id,
            'tanggal_surat' => now()->toDateString(),
            'tgl_mulai_pkl' => now()->toDateString(),
            'tgl_selesai_pkl' => now()->addMonths(3)->toDateString(),
        ]);

        $surat = SuratPengajuan::firstOrFail();
        $response->assertRedirect(route('pkl.surat.show', $surat));

        $this->assertNotNull($surat->file_pdf_path, 'PDF harus ter-generate saat pengajuan disimpan.');

        // Nomor surat "421/BKK/2026/1" -> file "surat-pkl/surat-pkl-2026-1.pdf"
        $expected = sprintf('surat-pkl/surat-pkl-%d-%s.pdf', now()->year, '1');
        $this->assertSame($expected, $surat->file_pdf_path);
        Storage::disk('public')->assertExists($expected);

        // Isi file benar-benar PDF
        $this->assertStringStartsWith('%PDF-', Storage::disk('public')->get($expected));

        $this->actingAs($this->bkk)
            ->get(route('pkl.surat.download', $surat))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_surat_tanpa_pdf_bisa_diregenerasi(): void
    {
        Storage::fake('public');

        $surat = SuratPengajuan::create([
            'nomor_surat' => '421/BKK/'.now()->year.'/1',
            'dudi_id' => $this->dudi->id,
            'tanggal_surat' => now()->toDateString(),
            'tgl_mulai_pkl' => now()->toDateString(),
            'tgl_selesai_pkl' => now()->addMonths(3)->toDateString(),
            'file_pdf_path' => null,
        ]);

        $this->actingAs($this->bkk)
            ->from(route('pkl.surat.show', $surat))
            ->post(route('pkl.surat.regenerate', $surat))
            ->assertRedirect(route('pkl.surat.show', $surat))
            ->assertSessionHasNoErrors();

        $this->assertNotNull($surat->fresh()->file_pdf_path);
        Storage::disk('public')->assertExists($surat->fresh()->file_pdf_path);
    }

    public function test_nomor_surat_berikutnya_melanjutkan_index(): void
    {
        $this->assertSame('421/BKK/'.now()->year.'/1', SuratPengajuan::generateNomorSurat(now()->year));

        SuratPengajuan::create([
            'nomor_surat' => '421/BKK/'.now()->year.'/1',
            'dudi_id' => $this->dudi->id,
            'tanggal_surat' => now()->toDateString(),
            'tgl_mulai_pkl' => now()->toDateString(),
            'tgl_selesai_pkl' => now()->addMonths(3)->toDateString(),
        ]);

        $this->assertSame('421/BKK/'.now()->year.'/2', SuratPengajuan::generateNomorSurat(now()->year));
    }

    public function test_halaman_penempatan_menampilkan_hierarki_jurusan_kelas_dan_siswa(): void
    {
        $penempatan = $this->buatPenempatan();
        $siswa = $penempatan->siswa;

        // Level 1: Jurusan
        $resJurusan = $this->actingAs($this->bkk)->get(route('pkl.index'))->assertOk();
        $resJurusan->assertSee('Penempatan PKL per Jurusan');
        $resJurusan->assertSee($siswa->jurusan);

        // Level 2: Kelas dalam Jurusan
        $resKelas = $this->actingAs($this->bkk)->get(route('pkl.index', ['jurusan' => $siswa->jurusan]))->assertOk();
        $resKelas->assertSee('Jurusan '.$siswa->jurusan);
        $resKelas->assertSee($siswa->kelas);

        // Level 3: Siswa dalam Kelas
        $resSiswa = $this->actingAs($this->bkk)->get(route('pkl.index', ['jurusan' => $siswa->jurusan, 'kelas' => $siswa->kelas]))->assertOk();
        $resSiswa->assertSee('Daftar Siswa Kelas '.$siswa->kelas);
        $resSiswa->assertSee($siswa->nama);
        $resSiswa->assertSee(route('pkl.surat.download', $penempatan->surat_pengajuan_id));
        $resSiswa->assertSee(route('pkl.surat.show', $penempatan->surat_pengajuan_id));
    }

    public function test_surat_pengajuan_bisa_diupdate_dan_pdf_diregenerate(): void
    {
        Storage::fake('public');

        $penempatan = $this->buatPenempatan();
        $surat = $penempatan->suratPengajuan;

        $dudiBaru = Dudi::create([
            'nama_dudi' => 'PT Revisi Baru',
            'alamat' => 'Jl. Baru No. 1',
            'kota' => 'Surakarta',
            'bidang_usaha' => 'IT',
            'kuota_maksimal' => 5,
        ]);

        $guruBaru = Guru::create([
            'nip' => '199001012015011001',
            'nama' => 'Guru Revisi, S.Kom',
            'jurusan' => 'RPL',
        ]);

        $payload = [
            'dudi_id' => $dudiBaru->id,
            'guru_id' => $guruBaru->id,
            'tanggal_surat' => now()->toDateString(),
            'tgl_mulai_pkl' => now()->addDays(5)->toDateString(),
            'tgl_selesai_pkl' => now()->addMonths(3)->toDateString(),
            'siswa_ids' => [$this->siswaA->id, $this->siswaB->id],
        ];

        $response = $this->actingAs($this->bkk)
            ->put(route('pkl.surat.update', $surat), $payload)
            ->assertRedirect(route('pkl.surat.show', $surat))
            ->assertSessionHas('success');

        $suratUpdated = $surat->fresh();
        $this->assertSame($dudiBaru->id, $suratUpdated->dudi_id);
        $this->assertSame(2, $suratUpdated->penempatanPkls()->count());
        $this->assertNotNull($suratUpdated->file_pdf_path);
        Storage::disk('public')->assertExists($suratUpdated->file_pdf_path);
    }

    public function test_halaman_siswa_menampilkan_seleksi_kelas(): void
    {
        $penempatan = $this->buatPenempatan();
        $siswa = $penempatan->siswa;

        // Tanpa filter kelas -> tampilkan card kelas
        $response = $this->actingAs($this->bkk)->get(route('pkl.siswa.index'))->assertOk();
        $response->assertSee('Data Siswa PKL per Kelas');
        $response->assertSee($siswa->kelas);

        // Dengan filter kelas -> tampilkan tabel siswa kelas itu
        $responseKelas = $this->actingAs($this->bkk)->get(route('pkl.siswa.index', ['kelas' => $siswa->kelas]))->assertOk();
        $responseKelas->assertSee('Data Siswa Kelas '.$siswa->kelas);
        $responseKelas->assertSee($siswa->nama);
    }

    /**
     * Helper: satu penempatan berstatus pengajuan.
     */
    private function buatPenempatan(): PenempatanPkl
    {
        $surat = SuratPengajuan::create([
            'nomor_surat' => '421/BKK/'.now()->year.'/1',
            'dudi_id' => $this->dudi->id,
            'tanggal_surat' => now()->toDateString(),
            'tgl_mulai_pkl' => now()->toDateString(),
            'tgl_selesai_pkl' => now()->addMonths(3)->toDateString(),
        ]);

        return PenempatanPkl::create([
            'surat_pengajuan_id' => $surat->id,
            'siswa_id' => $this->siswaA->id,
            'dudi_id' => $this->dudi->id,
            'guru_id' => $this->guru->id,
            'status_penempatan' => PenempatanPkl::STATUS_PENGAJUAN,
        ]);
    }
}
