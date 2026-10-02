<?php

namespace Tests\Feature;

use App\Models\Artikel;
use App\Models\Guru;
use App\Models\KategoriArtikel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSekolahArtikelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sekolah_can_access_dashboard(): void
    {
        $adminSekolah = User::factory()->create(['role' => 'admin_sekolah']);

        // /dashboard hanya dispatcher; halaman tujuan admin_sekolah adalah Daftar Artikel.
        $this->actingAs($adminSekolah)->get('/dashboard')->assertRedirect(route('admin.artikel.index'));

        $response = $this->actingAs($adminSekolah)->get(route('admin.artikel.index'));

        $response->assertOk();
        $response->assertSee('Artikel Sekolah');
        $response->assertSee(route('admin.kategori-artikel.index'));
        $response->assertSee(route('admin.artikel.index'));
        $response->assertSee(route('datamaster.guru.index'));
        $response->assertSee(route('datamaster.siswa.index'));
    }

    public function test_admin_sekolah_can_perform_kategori_artikel_crud(): void
    {
        $adminSekolah = User::factory()->create(['role' => 'admin_sekolah']);

        // 1. Index
        $response = $this->actingAs($adminSekolah)->get(route('admin.kategori-artikel.index'));
        $response->assertOk();
        $response->assertSee('Kategori Artikel');

        // 2. Store
        $storeResponse = $this->actingAs($adminSekolah)->post(route('admin.kategori-artikel.store'), [
            'nama' => 'Berita Sekolah',
            'deskripsi' => 'Kategori untuk berita seputar sekolah dan agenda.',
        ]);
        $storeResponse->assertRedirect(route('admin.kategori-artikel.index'));

        $this->assertDatabaseHas('kategori_artikels', [
            'nama' => 'Berita Sekolah',
            'slug' => 'berita-sekolah',
            'deskripsi' => 'Kategori untuk berita seputar sekolah dan agenda.',
        ]);

        $kategori = KategoriArtikel::where('slug', 'berita-sekolah')->first();

        // 3. Update
        $updateResponse = $this->actingAs($adminSekolah)->put(route('admin.kategori-artikel.update', $kategori->id), [
            'nama' => 'Warta & Berita Sekolah',
            'deskripsi' => 'Kategori yang telah diperbarui.',
        ]);
        $updateResponse->assertRedirect(route('admin.kategori-artikel.index'));

        $this->assertDatabaseHas('kategori_artikels', [
            'id' => $kategori->id,
            'nama' => 'Warta & Berita Sekolah',
            'slug' => 'warta-berita-sekolah',
            'deskripsi' => 'Kategori yang telah diperbarui.',
        ]);

        // 4. Destroy
        $deleteResponse = $this->actingAs($adminSekolah)->delete(route('admin.kategori-artikel.destroy', $kategori->id));
        $deleteResponse->assertRedirect(route('admin.kategori-artikel.index'));
        $this->assertDatabaseMissing('kategori_artikels', ['id' => $kategori->id]);
    }

    public function test_admin_sekolah_can_perform_artikel_crud_with_image_upload(): void
    {
        Storage::fake('public');
        $adminSekolah = User::factory()->create(['role' => 'admin_sekolah']);
        $kategori = KategoriArtikel::create([
            'nama' => 'Prestasi',
            'slug' => 'prestasi',
            'deskripsi' => 'Kategori prestasi siswa dan sekolah',
        ]);

        // 1. Index
        $response = $this->actingAs($adminSekolah)->get(route('admin.artikel.index'));
        $response->assertOk();
        $response->assertSee('Daftar Artikel Sekolah');

        // 2. Create Page
        $createPageResponse = $this->actingAs($adminSekolah)->get(route('admin.artikel.create'));
        $createPageResponse->assertOk();
        $createPageResponse->assertSee('Buat Artikel Baru');

        // 3. Store
        $fakeImage = UploadedFile::fake()->image('prestasi-lks.jpg', 800, 600);
        $storeResponse = $this->actingAs($adminSekolah)->post(route('admin.artikel.store'), [
            'judul' => 'Siswa SMKN 2 Karanganyar Juara 1 LKS Tingkat Provinsi',
            'kategori_artikel_id' => $kategori->id,
            'ringkasan' => 'Prestasi membanggakan kembali diraih oleh perwakilan siswa jurusan RPL.',
            'konten' => 'Isi lengkap berita prestasi siswa SMK Negeri 2 Karanganyar yang berhasil meraih medali emas.',
            'status' => 'published',
            'gambar' => $fakeImage,
        ]);

        $storeResponse->assertRedirect(route('admin.artikel.index'));
        $this->assertDatabaseHas('artikels', [
            'judul' => 'Siswa SMKN 2 Karanganyar Juara 1 LKS Tingkat Provinsi',
            'slug' => 'siswa-smkn-2-karanganyar-juara-1-lks-tingkat-provinsi',
            'status' => 'published',
            'user_id' => $adminSekolah->id,
            'kategori_artikel_id' => $kategori->id,
        ]);

        $artikel = Artikel::where('slug', 'siswa-smkn-2-karanganyar-juara-1-lks-tingkat-provinsi')->first();
        $this->assertNotNull($artikel);
        $this->assertNotNull($artikel->published_at);
        $this->assertNotNull($artikel->gambar);
        Storage::disk('public')->assertExists($artikel->gambar);

        // 4. Show / Preview
        $showResponse = $this->actingAs($adminSekolah)->get(route('admin.artikel.show', $artikel->id));
        $showResponse->assertOk();
        $showResponse->assertSee('Siswa SMKN 2 Karanganyar Juara 1 LKS Tingkat Provinsi');

        // 5. Edit Page
        $editPageResponse = $this->actingAs($adminSekolah)->get(route('admin.artikel.edit', $artikel->id));
        $editPageResponse->assertOk();
        $editPageResponse->assertSee('Edit Artikel');

        // 6. Update with new image
        $newFakeImage = UploadedFile::fake()->image('prestasi-update.png', 1000, 700);
        $oldImagePath = $artikel->gambar;

        $updateResponse = $this->actingAs($adminSekolah)->put(route('admin.artikel.update', $artikel->id), [
            'judul' => 'Siswa SMKN 2 Karanganyar Raih Juara 1 LKS Nasional',
            'kategori_artikel_id' => $kategori->id,
            'ringkasan' => 'Update ringkasan berita prestasi tingkat nasional.',
            'konten' => 'Konten yang sudah diperbarui dengan data pemenang lengkap.',
            'status' => 'draft',
            'gambar' => $newFakeImage,
        ]);

        $updateResponse->assertRedirect(route('admin.artikel.index'));
        $artikel->refresh();

        $this->assertEquals('Siswa SMKN 2 Karanganyar Raih Juara 1 LKS Nasional', $artikel->judul);
        $this->assertEquals('siswa-smkn-2-karanganyar-raih-juara-1-lks-nasional', $artikel->slug);
        $this->assertEquals('draft', $artikel->status);
        $this->assertNotEquals($oldImagePath, $artikel->gambar);
        Storage::disk('public')->assertExists($artikel->gambar);
        Storage::disk('public')->assertMissing($oldImagePath);

        // 7. Destroy
        $destroyResponse = $this->actingAs($adminSekolah)->delete(route('admin.artikel.destroy', $artikel->id));
        $destroyResponse->assertRedirect(route('admin.artikel.index'));
        $this->assertDatabaseMissing('artikels', ['id' => $artikel->id]);
        Storage::disk('public')->assertMissing($artikel->gambar);
    }

    public function test_admin_sekolah_can_crud_guru_and_siswa(): void
    {
        $adminSekolah = User::factory()->create(['role' => 'admin_sekolah']);

        // 1. Data Guru: Index & Store
        $resGuru = $this->actingAs($adminSekolah)->get(route('datamaster.guru.index'));
        $resGuru->assertOk();

        $storeGuru = $this->actingAs($adminSekolah)->post(route('datamaster.guru.store'), [
            'nama' => 'Budi Sudarsono, S.Pd.',
            'nip' => '198703152012011002',
            'jurusan' => 'Teknik Komputer & Jaringan',
            'no_hp' => '081299988877',
        ]);
        $storeGuru->assertRedirect();
        $this->assertDatabaseHas('gurus', ['nip' => '198703152012011002']);

        $guru = Guru::where('nip', '198703152012011002')->first();

        // 2. Data Guru: Update & Delete
        $updateGuru = $this->actingAs($adminSekolah)->put(route('datamaster.guru.update', $guru->id), [
            'nama' => 'Budi Sudarsono, M.Pd.',
            'nip' => '198703152012011002',
            'jurusan' => 'Teknik Komputer & Jaringan',
            'no_hp' => '081299988800',
        ]);
        $updateGuru->assertRedirect();
        $this->assertDatabaseHas('gurus', ['nama' => 'Budi Sudarsono, M.Pd.']);

        $deleteGuru = $this->actingAs($adminSekolah)->delete(route('datamaster.guru.destroy', $guru->id));
        $deleteGuru->assertRedirect();
        $this->assertDatabaseMissing('gurus', ['id' => $guru->id]);

        // 3. Data Siswa: Index & Store
        $resSiswa = $this->actingAs($adminSekolah)->get(route('datamaster.siswa.index'));
        $resSiswa->assertOk();

        $storeSiswa = $this->actingAs($adminSekolah)->post(route('datamaster.siswa.store'), [
            'nama' => 'Rina Salsabila',
            'nis' => '12345678',
            'jurusan' => 'RPL',
            'kelas' => 'XII RPL 1',
            'jenis_kelamin' => 'P',
            'no_hp' => '085711223344',
        ]);
        $storeSiswa->assertRedirect();
        $this->assertDatabaseHas('siswas', ['nis' => '12345678']);

        $siswa = Siswa::where('nis', '12345678')->first();

        // 4. Data Siswa: Update & Delete
        $updateSiswa = $this->actingAs($adminSekolah)->put(route('datamaster.siswa.update', $siswa->id), [
            'nama' => 'Rina Salsabila Putri',
            'nis' => '12345678',
            'jurusan' => 'RPL',
            'kelas' => 'XII RPL 1',
            'jenis_kelamin' => 'P',
            'no_hp' => '085711223355',
        ]);
        $updateSiswa->assertRedirect();
        $this->assertDatabaseHas('siswas', ['nama' => 'Rina Salsabila Putri']);

        $deleteSiswa = $this->actingAs($adminSekolah)->delete(route('datamaster.siswa.destroy', $siswa->id));
        $deleteSiswa->assertRedirect();
        $this->assertDatabaseMissing('siswas', ['id' => $siswa->id]);
    }

    public function test_unauthorized_roles_cannot_access_artikel_crud(): void
    {
        $pelanggan = User::factory()->create(['role' => 'pelanggan']);
        $adminAula = User::factory()->create(['role' => 'admin_aula']);
        $guru = User::factory()->create(['role' => 'guru']);

        // Guest
        $this->get(route('admin.artikel.index'))->assertRedirect('/login');
        $this->get(route('admin.kategori-artikel.index'))->assertRedirect('/login');

        // Pelanggan
        $this->actingAs($pelanggan)->get(route('admin.artikel.index'))->assertForbidden();
        $this->actingAs($pelanggan)->get(route('admin.kategori-artikel.index'))->assertForbidden();

        // Admin Aula
        $this->actingAs($adminAula)->get(route('admin.artikel.index'))->assertForbidden();
        $this->actingAs($adminAula)->get(route('admin.kategori-artikel.index'))->assertForbidden();

        // Guru
        $this->actingAs($guru)->get(route('admin.artikel.index'))->assertForbidden();
        $this->actingAs($guru)->get(route('admin.kategori-artikel.index'))->assertForbidden();
    }

    public function test_super_admin_can_also_access_artikel_crud(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($superAdmin)->get(route('admin.artikel.index'))->assertOk();
        $this->actingAs($superAdmin)->get(route('admin.kategori-artikel.index'))->assertOk();
    }

    public function test_admin_sekolah_can_upload_image_for_wysiwyg(): void
    {
        Storage::fake('public');
        $adminSekolah = User::factory()->create(['role' => 'admin_sekolah']);

        $file = UploadedFile::fake()->image('wysiwyg_image.png', 600, 400);

        $response = $this->actingAs($adminSekolah)->postJson(route('admin.artikel.upload-image'), [
            'image' => $file,
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'url',
            'path',
        ]);
        $response->assertJson([
            'success' => true,
        ]);

        $path = $response->json('path');
        Storage::disk('public')->assertExists($path);
    }

    public function test_konten_with_base64_images_automatically_converts_to_storage_files(): void
    {
        Storage::fake('public');
        $adminSekolah = User::factory()->create(['role' => 'admin_sekolah']);
        $kategori = KategoriArtikel::create(['nama' => 'Prestasi', 'slug' => 'prestasi']);

        // Tiny valid 1x1 transparent png in base64
        $fakeBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
        $kontenWithBase64 = '<p>Berikut ini adalah dokumentasi kegiatan:</p><p><img src="'.$fakeBase64.'"></p><p>Selesai.</p>';

        $response = $this->actingAs($adminSekolah)->post(route('admin.artikel.store'), [
            'kategori_artikel_id' => $kategori->id,
            'judul' => 'Artikel Dokumentasi Base64 Test',
            'konten' => $kontenWithBase64,
            'status' => 'published',
        ]);

        $response->assertRedirect(route('admin.artikel.index'));

        $artikel = Artikel::where('slug', 'artikel-dokumentasi-base64-test')->first();
        $this->assertNotNull($artikel);

        // Pastikan tidak ada string base64 lagi di kolom konten pada database
        $this->assertStringNotContainsString('data:image/png;base64', $artikel->konten);
        // Pastikan src berisi path ke storage lokal
        $this->assertStringContainsString('/storage/artikel/konten/editor_', $artikel->konten);
    }
}
