<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered_by_guests(): void
    {
        $response = $this->get('/registrasi');

        $response->assertOk();
        $response->assertSee('Daftar Akun');
        $response->assertSee('name="name"', false);
        $response->assertSee('name="username"', false);
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('name="password_confirmation"', false);
    }

    public function test_register_alias_route_redirects_to_registrasi(): void
    {
        $response = $this->get('/register');

        $response->assertRedirect('/registrasi');
    }

    public function test_authenticated_user_cannot_access_registration_page(): void
    {
        $pelanggan = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $response = $this->actingAs($pelanggan)->get('/registrasi');

        $response->assertRedirect('/customer/dashboard');
    }

    public function test_registration_fails_with_missing_fields(): void
    {
        $response = $this->from('/registrasi')->post('/registrasi', [
            'name' => '',
            'username' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect('/registrasi');
        $response->assertSessionHasErrors(['name', 'username', 'email', 'password']);
        $this->assertGuest();
    }

    public function test_registration_fails_if_password_confirmation_does_not_match(): void
    {
        $response = $this->from('/registrasi')->post('/registrasi', [
            'name' => 'Budi Santoso',
            'username' => 'budisantoso',
            'email' => 'budi@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'mismatched123',
        ]);

        $response->assertRedirect('/registrasi');
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_registration_fails_if_username_already_taken(): void
    {
        User::factory()->create([
            'username' => 'existinguser',
            'email' => 'first@example.com',
        ]);

        $response = $this->from('/registrasi')->post('/registrasi', [
            'name' => 'Existing User',
            'username' => 'existinguser',
            'email' => 'second@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/registrasi');
        $response->assertSessionHasErrors(['username']);
        $this->assertGuest();
    }

    public function test_registration_fails_if_email_already_taken(): void
    {
        User::factory()->create([
            'username' => 'userpertama',
            'email' => 'same@example.com',
        ]);

        $response = $this->from('/registrasi')->post('/registrasi', [
            'name' => 'User Kedua',
            'username' => 'userkedua',
            'email' => 'same@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/registrasi');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_new_user_can_register_and_automatically_assigned_pelanggan_role(): void
    {
        $response = $this->post('/registrasi', [
            'name' => 'Peminjam Baru',
            'username' => 'peminjambaru',
            'email' => 'peminjam@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/customer/dashboard');
        $response->assertSessionHas('success');

        $this->assertAuthenticated();

        $user = User::where('username', 'peminjambaru')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Peminjam Baru', $user->name);
        $this->assertEquals('peminjambaru', $user->username);
        $this->assertEquals('peminjam@example.com', $user->email);
        $this->assertEquals('pelanggan', $user->role);
        $this->assertTrue(Hash::check('password123', $user->password));

        $this->assertAuthenticatedAs($user);
    }

    public function test_registration_strictly_forces_pelanggan_role_even_if_request_contains_another_role(): void
    {
        $response = $this->post('/registrasi', [
            'name' => 'Attacker User',
            'username' => 'attacker',
            'email' => 'attacker@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'super_admin', // Malicious payload attempt
        ]);

        $response->assertRedirect('/customer/dashboard');

        $user = User::where('username', 'attacker')->first();
        $this->assertNotNull($user);
        $this->assertEquals('pelanggan', $user->role, 'Role harus tetap pelanggan dan tidak dapat diubah lewat request form!');
    }
}
