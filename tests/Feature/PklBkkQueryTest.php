<?php

namespace Tests\Feature;

use App\Models\Dudi;
use App\Models\Guru;
use App\Models\Lowongan;
use App\Models\PenempatanPkl;
use App\Models\Siswa;
use App\Models\SuratPengajuan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Guard anti-N+1.
 *
 * Setiap test membandingkan jumlah query pada dataset KECIL vs dataset BESAR.
 * Query yang ideal bersifat konstan terhadap ukuran data. Kalau jumlah query
 * ikut bertambah, berarti ada relasi yang lazy-load di dalam loop view.
 */
class PklBkkQueryTest extends TestCase
{
    use DatabaseMigrations;

    private User $bkk;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bkk = User::factory()->create(['username' => 'bkk', 'role' => 'bkk']);
    }

    /**
     * Seed data PKL sebanyak `$skala` paket (10 siswa & 5 DUDI per paket).
     */
    private function seedData(int $skala): void
    {
        $gurus = collect();
        for ($i = 1; $i <= 2; $i++) {
            $gurus->push(Guru::create([
                'nip' => '19870512201101100'.$i,
                'nama' => 'Guru '.$i,
                'jurusan' => 'RPL',
            ]));
        }

        $siswas = collect();
        for ($i = 1; $i <= $skala * 10; $i++) {
            $siswas->push(Siswa::create([
                'nis' => (string) (2400000 + $i),
                'nama' => 'Siswa '.$i,
                'kelas' => 'XII'.(($i % 3) + 1),
                'jurusan' => ['RPL', 'TKJ', 'TPK'][$i % 3],
            ]));
        }

        $dudis = collect();
        for ($i = 1; $i <= $skala * 5; $i++) {
            $dudis->push(Dudi::create([
                'nama_dudi' => 'PT Usaha '.$i,
                'alamat' => 'Jl. Test No. '.$i,
                'kota' => 'Karanganyar',
                'bidang_usaha' => 'IT',
                'kuota_maksimal' => 20,
                'is_mitra_resmi' => $i % 2 === 0,
                'tampil_di_landing' => $i % 3 !== 0,
            ]));
        }

        $surat = SuratPengajuan::create([
            'nomor_surat' => '421/BKK/'.now()->year.'/1',
            'dudi_id' => $dudis->first()->id,
            'tanggal_surat' => now()->toDateString(),
            'tgl_mulai_pkl' => now()->toDateString(),
            'tgl_selesai_pkl' => now()->addMonths(3)->toDateString(),
        ]);

        // Setiap siswa pada 1/3 pertama punya FIX -> memicu perhitungan kuota.
        $siswas->take((int) ($skala * 10 / 3))->each(function (Siswa $s, int $i) use ($dudis, $gurus, $surat) {
            PenempatanPkl::create([
                'surat_pengajuan_id' => $surat->id,
                'siswa_id' => $s->id,
                'dudi_id' => $dudis[$i % $dudis->count()]->id,
                'guru_id' => $gurus[$i % 2]->id,
                'status_penempatan' => PenempatanPkl::STATUS_FIX,
            ]);
        });

        // Sisanya masih `pengajuan` agar scope belumPkl & count ikut terpakai.
        $siswas->skip((int) ($skala * 10 / 3))->each(function (Siswa $s, int $i) use ($dudis, $gurus, $surat) {
            PenempatanPkl::create([
                'surat_pengajuan_id' => $surat->id,
                'siswa_id' => $s->id,
                'dudi_id' => $dudis[$i % $dudis->count()]->id,
                'guru_id' => $gurus[$i % 2]->id,
                'status_penempatan' => PenempatanPkl::STATUS_PENGAJUAN,
            ]);
        });

        foreach (range(1, $skala * 3) as $i) {
            Lowongan::create([
                'nama_perusahaan' => 'Perusahaan '.$i,
                'posisi' => 'Posisi '.$i,
                'tipe' => $i % 2 ? Lowongan::TIPE_PEKERJAAN : Lowongan::TIPE_MAGANG,
                'jurusan_sesui' => $i % 2 ? 'RPL' : Lowongan::SEMUA_JURUSAN,
                'deskripsi' => 'Deskripsi lowongan '.$i,
                'link_daftar' => 'https://forms.gle/contoh'.$i,
                'deadline' => now()->addDays(10 + $i)->toDateString(),
                'is_active' => true,
                'dudi_id' => $dudis[$i % $dudis->count()]->id,
            ]);
        }
    }

    /**
     * @return array{0:int,1:int} jumlah query pada data kecil vs besar
     */
    private function bandingkanQuery(string $url, bool $json = false): array
    {
        $ukuran = function (int $skala) use ($url, $json) {
            $this->seedData($skala);

            $jumlah = 0;
            DB::listen(function () use (&$jumlah) {
                $jumlah++;
            });

            $response = $json
                ? $this->actingAs($this->bkk)->getJson($url)
                : $this->actingAs($this->bkk)->get($url);

            $response->assertOk();

            return $jumlah;
        };

        // Skala 1 = 10 siswa/5 DUDI, skala 5 = 50 siswa/25 DUDI.
        $kecil = $ukuran(1);
        $this->artisan('migrate:fresh');
        $besar = $ukuran(5);

        return [$kecil, $besar];
    }

    public function test_dashboard_bkk_tidak_n_plus_one(): void
    {
        [$kecil, $besar] = $this->bandingkanQuery(route('pkl.dashboard'));

        $this->assertSame($kecil, $besar, 'Jumlah query dashboard harus konstan terhadap ukuran data.');
    }

    public function test_halaman_dudi_tidak_n_plus_one(): void
    {
        [$kecil, $besar] = $this->bandingkanQuery(route('pkl.dudi.index'));

        $this->assertSame($kecil, $besar, 'Jumlah query halaman DUDI harus konstan terhadap ukuran data.');
    }

    public function test_halaman_lowongan_tidak_n_plus_one(): void
    {
        [$kecil, $besar] = $this->bandingkanQuery(route('pkl.lowongan.index'));

        $this->assertSame($kecil, $besar, 'Jumlah query halaman lowongan harus konstan terhadap ukuran data.');
    }

    public function test_halaman_siswa_tidak_n_plus_one(): void
    {
        [$kecil, $besar] = $this->bandingkanQuery(route('pkl.siswa.index'));

        $this->assertSame($kecil, $besar, 'Jumlah query halaman siswa harus konstan terhadap ukuran data.');
    }

    public function test_halaman_penempatan_tidak_n_plus_one(): void
    {
        [$kecil, $besar] = $this->bandingkanQuery(route('pkl.index'));

        $this->assertSame($kecil, $besar, 'Jumlah query halaman penempatan harus konstan terhadap ukuran data.');
    }

    public function test_form_pengajuan_tidak_n_plus_one(): void
    {
        [$kecil, $besar] = $this->bandingkanQuery(route('pkl.create'));

        $this->assertSame($kecil, $besar, 'Jumlah query form pengajuan harus konstan terhadap ukuran data.');
    }

    public function test_api_dudi_tidak_n_plus_one(): void
    {
        [$kecil, $besar] = $this->bandingkanQuery(route('api.pkl.dudi'), json: true);

        $this->assertSame($kecil, $besar, 'Jumlah query API DUDI harus konstan terhadap ukuran data.');
    }

    public function test_api_lowongan_tidak_n_plus_one(): void
    {
        [$kecil, $besar] = $this->bandingkanQuery(route('api.pkl.lowongan').'?per_page=50', json: true);

        $this->assertSame($kecil, $besar, 'Jumlah query API lowongan harus konstan terhadap ukuran data.');
    }

    public function test_api_siswa_tidak_n_plus_one(): void
    {
        $nis = '2400001';

        $jumlah = function () use ($nis) {
            $this->seedData(1);
            $n = 0;
            DB::listen(function () use (&$n) {
                $n++;
            });
            $this->getJson(route('api.pkl.siswa', $nis))->assertOk();

            return $n;
        };

        // Endpoint ini per-1 siswa, jadi tidakberoleh terhadap skala.
        // Tetap dijaga agar tidak 1 query per penempatan (N+1 vertikal).
        $pertama = $jumlah();
        $this->artisan('migrate:fresh');
        $kedua = $jumlah();

        $this->assertLessThanOrEqual(8, $pertama, "Endpoint siswa memakai $pertama query.");
        $this->assertSame($pertama, $kedua);
    }
}
