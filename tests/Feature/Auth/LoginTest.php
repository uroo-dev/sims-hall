<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered_by_guests(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('username');
    }

    public function test_users_can_authenticate_with_valid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_pelanggan_is_redirected_to_customer_dashboard_after_login(): void
    {
        $pelanggan = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $response = $this->post('/login', [
            'username' => $pelanggan->username,
            'password' => 'password',
        ]);

        $response->assertRedirect('/customer/dashboard');
        $this->assertAuthenticatedAs($pelanggan);
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->from('/login')->post('/login', [
            'username' => $user->username,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_users_with_role_bkk_are_redirected_to_pkl_bkk_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'bkk']);

        $this->post('/login', [
            'username' => $user->username,
            'password' => 'password',
        ])->assertRedirect('/dashboard/pkl-bkk');
    }

    public function test_admin_role_can_access_pkl_bkk_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'admin_pklbkk']);

        $this->actingAs($user)
            ->get('/dashboard/pkl-bkk')
            ->assertOk()
            ->assertSee('BKK');
    }

    public function test_admin_can_access_all_pkl_bkk_pages(): void
    {
        $user = User::factory()->create(['role' => 'admin_pklbkk']);

        $pages = [
            '/dashboard/pkl-bkk/dudi' => 'Data DUDI',
            '/dashboard/pkl-bkk/lowongan' => 'Lowongan Kerja',
            '/dashboard/pkl-bkk/lowongan/tambah' => 'Tambah Lowongan Kerja',
            '/dashboard/pkl-bkk/siswa' => 'Data Siswa PKL',
            '/dashboard/pkl-bkk/penempatan' => 'Penempatan PKL',
            '/dashboard/pkl-bkk/pengajuan/create' => 'Buat Pengajuan PKL',
        ];

        foreach ($pages as $url => $label) {
            $this->actingAs($user)
                ->get($url)
                ->assertOk()
                ->assertSee($label);
        }
    }

    public function test_non_admin_cannot_access_pkl_bkk_pages(): void
    {
        $user = User::factory()->create(['role' => 'guru']);

        $this->actingAs($user)
            ->get('/dashboard/pkl-bkk/penempatan')
            ->assertForbidden();

        $adminAula = User::factory()->create(['role' => 'admin_aula']);
        $this->actingAs($adminAula)
            ->get('/dashboard/pkl-bkk/penempatan')
            ->assertForbidden();
    }

    public function test_guests_cannot_access_pkl_bkk_dashboard(): void
    {
        $this->get('/dashboard/pkl-bkk')->assertRedirect('/login');
    }

    public function test_landing_page_is_served_at_root(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('SMKN 2')
            ->assertSee('Peminjaman Aula');
    }

    public function test_login_requires_username_and_password(): void
    {
        $response = $this->from('/login')->post('/login', [
            'username' => '',
            'password' => '',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['username', 'password']);
        $this->assertGuest();
    }

    public function test_authenticated_users_are_redirected_away_from_login(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/login')
            ->assertRedirect('/dashboard');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/login');

        $this->assertGuest();
    }

    public function test_guests_are_redirected_to_login_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_admin_role_can_access_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('SMK NEGERI 2')
            ->assertSee($user->name);
    }

    public function test_super_admin_role_can_access_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($user)->get('/dashboard')->assertOk();
    }

    public function test_non_admin_roles_cannot_access_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'guru']);

        $this->actingAs($user)->get('/dashboard')->assertForbidden();
    }

    public function test_login_attempts_are_rate_limited(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 6) as $i) {
            $this->from('/login')->post('/login', [
                'username' => $user->username,
                'password' => 'wrong-password',
            ]);
        }

        $this->from('/login')->post('/login', [
            'username' => $user->username,
            'password' => 'wrong-password',
        ])->assertStatus(429);
    }
}
