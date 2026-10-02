<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoleOrientationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_aula_can_access_aula_and_dashboard_but_forbidden_from_data_master(): void
    {
        $adminAula = User::factory()->create([
            'username' => 'admin_aula_test',
            'role' => 'admin_aula',
        ]);

        // Dashboard accessible
        $this->actingAs($adminAula)->get(route('dashboard'))->assertOk();

        // Aula routes accessible
        $this->actingAs($adminAula)->get(route('admin.fasilitas.index'))->assertOk();
        $this->actingAs($adminAula)->get(route('admin.paket.index'))->assertOk();
        $this->actingAs($adminAula)->get(route('admin.peminjaman.index'))->assertOk();
        $this->actingAs($adminAula)->get(route('admin.laporan.index'))->assertOk();

        // Data Master routes forbidden
        $this->actingAs($adminAula)->get(route('datamaster.index'))->assertForbidden();
        $this->actingAs($adminAula)->get(route('datamaster.users'))->assertForbidden();
        $this->actingAs($adminAula)->get(route('datamaster.sekolah.edit'))->assertForbidden();

        // PKL BKK route forbidden
        $this->actingAs($adminAula)->get(route('pkl.dashboard'))->assertForbidden();
    }

    private function getSidebar(string $html): string
    {
        preg_match('/<aside id="sidebar".*?<\/aside>/s', $html, $matches);

        return $matches[0] ?? '';
    }

    public function test_admin_aula_sidebar_only_shows_aula_and_dashboard(): void
    {
        $adminAula = User::factory()->create([
            'username' => 'admin_aula_test',
            'role' => 'admin_aula',
        ]);

        $html = $this->actingAs($adminAula)->get(route('dashboard'))->assertOk()->getContent();
        $sidebar = $this->getSidebar($html);

        // Shows Dashboard & Aula
        $this->assertStringContainsString('Dashboard', $sidebar);
        $this->assertStringContainsString('Fasilitas', $sidebar);
        $this->assertStringContainsString('Paket Peminjaman', $sidebar);
        $this->assertStringContainsString('Daftar Peminjaman', $sidebar);
        $this->assertStringContainsString('Laporan Pemasukan', $sidebar);

        // Does NOT show Data Master or PKL & BKK or Payment Configuration
        $this->assertStringNotContainsString('Data Master Sekolah', $sidebar);
        $this->assertStringNotContainsString(route('datamaster.index'), $sidebar);
        $this->assertStringNotContainsString(route('datamaster.users'), $sidebar);
        $this->assertStringNotContainsString('Konfigurasi Peminjaman', $sidebar);
        $this->assertStringNotContainsString(route('pkl.lowongan.index'), $sidebar);
    }

    public function test_admin_master_can_access_data_master_but_forbidden_from_aula(): void
    {
        $adminMaster = User::factory()->create([
            'username' => 'admin_master_test',
            'role' => 'admin_master',
        ]);

        // Dashboard accessible
        $this->actingAs($adminMaster)->get(route('dashboard'))->assertOk();

        // Data Master routes accessible
        $this->actingAs($adminMaster)->get(route('datamaster.index'))->assertOk();
        $this->actingAs($adminMaster)->get(route('datamaster.users'))->assertOk();
        $this->actingAs($adminMaster)->get(route('datamaster.sekolah.edit'))->assertOk();

        // Aula routes forbidden
        $this->actingAs($adminMaster)->get(route('admin.fasilitas.index'))->assertForbidden();
        $this->actingAs($adminMaster)->get(route('admin.paket.index'))->assertForbidden();
        $this->actingAs($adminMaster)->get(route('admin.peminjaman.index'))->assertForbidden();
        $this->actingAs($adminMaster)->get(route('admin.laporan.index'))->assertForbidden();

        // PKL BKK route forbidden
        $this->actingAs($adminMaster)->get(route('pkl.dashboard'))->assertForbidden();
    }

    public function test_admin_master_sidebar_only_shows_data_master_and_dashboard(): void
    {
        $adminMaster = User::factory()->create([
            'username' => 'admin_master_test',
            'role' => 'admin_master',
        ]);

        $html = $this->actingAs($adminMaster)->get(route('dashboard'))->assertOk()->getContent();
        $sidebar = $this->getSidebar($html);

        // Shows Dashboard & Data Master
        $this->assertStringContainsString('Dashboard', $sidebar);
        $this->assertStringContainsString('Data Master Sekolah', $sidebar);
        $this->assertStringContainsString(route('datamaster.index'), $sidebar);
        $this->assertStringContainsString(route('datamaster.users'), $sidebar);
        $this->assertStringContainsString(route('datamaster.sekolah.edit'), $sidebar);

        // Does NOT show Aula or PKL & BKK or Payment Configuration in sidebar
        $this->assertStringNotContainsString('Fasilitas', $sidebar);
        $this->assertStringNotContainsString('Paket Peminjaman', $sidebar);
        $this->assertStringNotContainsString('Daftar Peminjaman', $sidebar);
        $this->assertStringNotContainsString('Konfigurasi Peminjaman', $sidebar);
        $this->assertStringNotContainsString(route('pkl.lowongan.index'), $sidebar);
    }

    public function test_super_admin_can_access_all_and_sees_all_menus(): void
    {
        $superAdmin = User::factory()->create([
            'username' => 'super_admin_test',
            'role' => 'super_admin',
        ]);

        // Can access Aula, Data Master, and Payment Config
        $this->actingAs($superAdmin)->get(route('admin.fasilitas.index'))->assertOk();
        $this->actingAs($superAdmin)->get(route('datamaster.index'))->assertOk();
        $this->actingAs($superAdmin)->get(route('admin.payment-configuration.index'))->assertOk();
        $this->actingAs($superAdmin)->get(route('pkl.dashboard'))->assertOk();

        // Sidebar contains all modules
        $html = $this->actingAs($superAdmin)->get(route('dashboard'))->assertOk()->getContent();
        $sidebar = $this->getSidebar($html);

        $this->assertStringContainsString('Fasilitas', $sidebar);
        $this->assertStringContainsString('Data Master Sekolah', $sidebar);
        $this->assertStringContainsString('Konfigurasi Peminjaman', $sidebar);
        $this->assertStringContainsString('PKL &amp; BKK', $sidebar);
    }
}
