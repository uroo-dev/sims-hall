<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataMasterGuruSiswaTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_guru_crud(): void
    {
        $superAdmin = User::factory()->create([
            'username' => 'root_test',
            'role' => 'super_admin',
        ]);

        // 1. Index
        $response = $this->actingAs($superAdmin)->get(route('datamaster.guru.index'));
        $response->assertOk();
        $response->assertSee('Daftar Guru');

        // 2. Store
        $storeResponse = $this->actingAs($superAdmin)->post(route('datamaster.guru.store'), [
            'nama' => 'Anton Wijaya, S.Kom.',
            'nip' => '198501012010011005',
            'jurusan' => 'Rekayasa Perangkat Lunak (RPL)',
            'no_hp' => '081234567890',
        ]);
        $storeResponse->assertRedirect();
        $this->assertDatabaseHas('gurus', [
            'nama' => 'Anton Wijaya, S.Kom.',
            'nip' => '198501012010011005',
            'jurusan' => 'Rekayasa Perangkat Lunak (RPL)',
            'no_hp' => '081234567890',
        ]);

        $guru = Guru::where('nip', '198501012010011005')->first();

        // 3. Update
        $updateResponse = $this->actingAs($superAdmin)->put(route('datamaster.guru.update', $guru->id), [
            'nama' => 'Anton Wijaya, M.Kom.',
            'nip' => '198501012010011005',
            'jurusan' => 'Rekayasa Perangkat Lunak (RPL)',
            'no_hp' => '081234567899',
        ]);
        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('gurus', [
            'id' => $guru->id,
            'nama' => 'Anton Wijaya, M.Kom.',
            'no_hp' => '081234567899',
        ]);

        // 4. Destroy
        $destroyResponse = $this->actingAs($superAdmin)->delete(route('datamaster.guru.destroy', $guru->id));
        $destroyResponse->assertRedirect();
        $this->assertDatabaseMissing('gurus', [
            'id' => $guru->id,
        ]);
    }

    public function test_super_admin_can_access_siswa_crud(): void
    {
        $superAdmin = User::factory()->create([
            'username' => 'root_test',
            'role' => 'super_admin',
        ]);

        // 1. Index
        $response = $this->actingAs($superAdmin)->get(route('datamaster.siswa.index'));
        $response->assertOk();
        $response->assertSee('Daftar Siswa');

        // 2. Store
        $storeResponse = $this->actingAs($superAdmin)->post(route('datamaster.siswa.store'), [
            'nama' => 'Budi Santoso',
            'nis' => '20241001',
            'kelas' => 'XII RPL 1',
            'jurusan' => 'Rekayasa Perangkat Lunak (RPL)',
            'no_hp' => '089876543210',
        ]);
        $storeResponse->assertRedirect();
        $this->assertDatabaseHas('siswas', [
            'nama' => 'Budi Santoso',
            'nis' => '20241001',
            'kelas' => 'XII RPL 1',
            'jurusan' => 'Rekayasa Perangkat Lunak (RPL)',
            'no_hp' => '089876543210',
        ]);

        $siswa = Siswa::where('nis', '20241001')->first();

        // 3. Update
        $updateResponse = $this->actingAs($superAdmin)->put(route('datamaster.siswa.update', $siswa->id), [
            'nama' => 'Budi Santoso Pratama',
            'nis' => '20241001',
            'kelas' => 'XII RPL 2',
            'jurusan' => 'Rekayasa Perangkat Lunak (RPL)',
            'no_hp' => '089876543211',
        ]);
        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('siswas', [
            'id' => $siswa->id,
            'nama' => 'Budi Santoso Pratama',
            'kelas' => 'XII RPL 2',
            'no_hp' => '089876543211',
        ]);

        // 4. Destroy
        $destroyResponse = $this->actingAs($superAdmin)->delete(route('datamaster.siswa.destroy', $siswa->id));
        $destroyResponse->assertRedirect();
        $this->assertDatabaseMissing('siswas', [
            'id' => $siswa->id,
        ]);
    }

    public function test_admin_master_can_access_guru_and_siswa(): void
    {
        $adminMaster = User::factory()->create([
            'username' => 'admin_master_test',
            'role' => 'admin_master',
        ]);

        $this->actingAs($adminMaster)->get(route('datamaster.guru.index'))->assertOk();
        $this->actingAs($adminMaster)->get(route('datamaster.siswa.index'))->assertOk();
    }

    public function test_unauthorized_roles_are_forbidden(): void
    {
        $adminAula = User::factory()->create([
            'username' => 'admin_aula_test',
            'role' => 'admin_aula',
        ]);

        $pelanggan = User::factory()->create([
            'username' => 'pelanggan_test',
            'role' => 'pelanggan',
        ]);

        $this->actingAs($adminAula)->get(route('datamaster.guru.index'))->assertForbidden();
        $this->actingAs($adminAula)->get(route('datamaster.siswa.index'))->assertForbidden();

        $this->actingAs($pelanggan)->get(route('datamaster.guru.index'))->assertForbidden();
        $this->actingAs($pelanggan)->get(route('datamaster.siswa.index'))->assertForbidden();
    }

    public function test_duplicate_nip_or_nis_fails_validation(): void
    {
        $superAdmin = User::factory()->create([
            'username' => 'root_test',
            'role' => 'super_admin',
        ]);

        Guru::create([
            'nama' => 'Guru A',
            'nip' => '12345678',
            'jurusan' => 'RPL',
        ]);

        Siswa::create([
            'nama' => 'Siswa A',
            'nis' => '87654321',
            'kelas' => 'X RPL 1',
            'jurusan' => 'RPL',
        ]);

        // Duplicate NIP
        $responseGuru = $this->actingAs($superAdmin)->post(route('datamaster.guru.store'), [
            'nama' => 'Guru B',
            'nip' => '12345678',
            'jurusan' => 'TKJ',
        ]);
        $responseGuru->assertSessionHasErrors('nip');

        // Duplicate NIS
        $responseSiswa = $this->actingAs($superAdmin)->post(route('datamaster.siswa.store'), [
            'nama' => 'Siswa B',
            'nis' => '87654321',
            'kelas' => 'X TKJ 1',
            'jurusan' => 'TKJ',
        ]);
        $responseSiswa->assertSessionHasErrors('nis');
    }

    public function test_datamaster_index_displays_guru_and_siswa_stat_cards(): void
    {
        $superAdmin = User::factory()->create([
            'username' => 'root_test',
            'role' => 'super_admin',
        ]);

        Guru::create(['nama' => 'Guru 1', 'jurusan' => 'RPL']);
        Guru::create(['nama' => 'Guru 2', 'jurusan' => 'TKJ']);

        Siswa::create(['nama' => 'Siswa 1', 'nis' => '001', 'kelas' => 'X', 'jurusan' => 'RPL']);
        Siswa::create(['nama' => 'Siswa 2', 'nis' => '002', 'kelas' => 'XI', 'jurusan' => 'TKJ']);
        Siswa::create(['nama' => 'Siswa 3', 'nis' => '003', 'kelas' => 'XII', 'jurusan' => 'TP']);

        $response = $this->actingAs($superAdmin)->get(route('datamaster.index'));
        $response->assertOk();
        $response->assertSee('Total Guru');
        $response->assertSee('Total Siswa');
        $response->assertSee(route('datamaster.guru.index'));
        $response->assertSee(route('datamaster.siswa.index'));
    }
}
