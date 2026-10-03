<?php

namespace Tests\Feature\Public;

use App\Models\Jurusan;
use App\Models\Ppdb_informasi;
use App\Models\Ppdb_jalur;
use App\Models\Ppdb_jurusan;
use App\Models\Ppdb_master;
use App\Models\Ppdb_persyaratan;
use App\Models\Ppdb_tanggal_penting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PpdbPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_ppdb_page_can_be_rendered_when_database_is_empty(): void
    {
        $response = $this->get('/ppdb');

        $response->assertOk();
        $response->assertViewIs('Public.ppdb.index');
        $response->assertSee('PPDB SMKN 2', false);
        $response->assertSee('Informasi Panduan PPDB');
        $response->assertSee('Daya Tampung');
        $response->assertSee('Jalur Seleksi');
        $response->assertSee('Hasil Seleksi');
    }

    public function test_public_ppdb_displays_dynamic_data_from_all_models(): void
    {
        Ppdb_master::create([
            'judul' => 'PPDB SMKN 2 HEBAT',
            'deskripsi' => 'Pendaftaran siswa baru tahun ajaran terbaik.',
            'banner_img' => 'ppdb/test-banner.jpg',
        ]);

        Ppdb_informasi::create([
            'judul' => 'Panduan Resmi PPDB',
            'keterangan' => 'Informasi lengkap alur dan mekanisme pendaftaran.',
            'path_file' => 'ppdb/persyaratan/petunjuk.pdf',
            'path_file_hasil' => 'ppdb/hasil-seleksi/hasil.pdf',
        ]);

        Ppdb_persyaratan::create(['syarat' => 'Fotokopi Ijazah dilegalisir']);
        Ppdb_persyaratan::create(['syarat' => 'Surat Keterangan Sehat dan Bebas Narkoba']);

        Ppdb_tanggal_penting::create([
            'nama_agenda' => 'Sosialisasi PPDB 2026',
            'tanggal_mulai' => '2026-05-10',
            'tanggal_selesai' => '2026-05-13',
            'keterangan' => 'Pengenalan teknis pendaftaran',
        ]);

        $rpl = Jurusan::create([
            'nama' => 'Rekayasa Perangkat Lunak',
            'deskripsi' => 'RPL',
        ]);
        $oto = Jurusan::create([
            'nama' => 'Teknik Ototronik',
            'deskripsi' => 'Oto',
        ]);

        Ppdb_jurusan::create([
            'jurusan_id' => $rpl->jurusanID,
            'daya_tampung' => 108,
            'img' => 'ppdb/jurusan/rpl.jpg',
        ]);
        Ppdb_jurusan::create([
            'jurusan_id' => $oto->jurusanID,
            'daya_tampung' => 72,
            'img' => null,
        ]);

        Ppdb_jalur::create([
            'nama_jalur' => 'Jalur Prestasi',
            'percentase' => 60,
        ]);
        Ppdb_jalur::create([
            'nama_jalur' => 'Jalur Afirmasi',
            'percentase' => 40,
        ]);

        $response = $this->get('/ppdb');

        $response->assertOk();
        // Master
        $response->assertSee('PPDB SMKN 2 HEBAT');
        $response->assertSee('Pendaftaran siswa baru tahun ajaran terbaik.');
        $response->assertSee('storage/ppdb/test-banner.jpg');

        // Informasi
        $response->assertSee('Panduan Resmi PPDB');
        $response->assertSee('Informasi lengkap alur dan mekanisme pendaftaran.');

        // Persyaratan
        $response->assertSee('Fotokopi Ijazah dilegalisir');
        $response->assertSee('Surat Keterangan Sehat dan Bebas Narkoba');
        $response->assertSee('storage/ppdb/persyaratan/petunjuk.pdf');

        // Tanggal penting
        $response->assertSee('Sosialisasi PPDB 2026');
        $response->assertSee('10 - 13 Mei 2026');
        $response->assertSee('Pengenalan teknis pendaftaran');

        // Jurusan
        $response->assertSee('Rekayasa Perangkat Lunak');
        $response->assertSee(': 108 Siswa');
        $response->assertSee('Teknik Ototronik');
        $response->assertSee(': 72 Siswa');
        $response->assertSee(': 180 Siswa'); // Total daya tampung
        $response->assertSee('storage/ppdb/jurusan/rpl.jpg');

        // Jalur
        $response->assertSee('Jalur Prestasi');
        $response->assertSee(': 60%');
        $response->assertSee('Jalur Afirmasi');
        $response->assertSee(': 40%');

        // Hasil seleksi
        $response->assertSee('storage/ppdb/hasil-seleksi/hasil.pdf');
    }

    public function test_public_ppdb_is_accessible_by_authenticated_user(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/ppdb');

        $response->assertOk();
        $response->assertViewIs('Public.ppdb.index');
    }
}
