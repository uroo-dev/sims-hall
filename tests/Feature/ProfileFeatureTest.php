<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile(): void
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_of_any_role_can_access_profile_page(): void
    {
        $roles = ['super_admin', 'admin_aula', 'admin_sekolah', 'admin_master', 'bkk', 'pelanggan', 'guru'];

        foreach ($roles as $role) {
            $user = User::factory()->create([
                'role' => $role,
            ]);

            $response = $this->actingAs($user)->get(route('profile.edit'));

            $response->assertOk();
            $response->assertViewIs('Admin.profile.edit');
            $response->assertSee($user->name);
            $response->assertSee($user->username);
            $response->assertSee($user->email);
        }
    }

    public function test_user_can_update_name_username_and_email(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'username' => 'user_lama',
            'email' => 'lama@example.com',
            'role' => 'admin_aula',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Nama Baru',
            'username' => 'user_baru',
            'email' => 'baru@example.com',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertSame('Nama Baru', $user->name);
        $this->assertSame('user_baru', $user->username);
        $this->assertSame('baru@example.com', $user->email);
    }

    public function test_user_cannot_use_existing_username_or_email_from_another_user(): void
    {
        User::factory()->create([
            'username' => 'existing_user',
            'email' => 'existing@example.com',
        ]);

        $user = User::factory()->create([
            'username' => 'my_user',
            'email' => 'my@example.com',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'My Name',
            'username' => 'existing_user',
            'email' => 'my@example.com',
        ]);

        $response->assertSessionHasErrors(['username']);

        $response2 = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'My Name',
            'username' => 'my_user',
            'email' => 'existing@example.com',
        ]);

        $response2->assertSessionHasErrors(['email']);
    }

    public function test_user_can_update_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function test_password_remains_unchanged_when_left_blank(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('original_password'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Updated Name',
            'username' => $user->username,
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect(route('profile.edit'));

        $user->refresh();
        $this->assertSame('Updated Name', $user->name);
        $this->assertTrue(Hash::check('original_password', $user->password));
    }

    public function test_user_can_upload_profile_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'foto_profil' => null,
        ]);

        $photo = UploadedFile::fake()->image('avatar.jpg', 200, 200);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'foto_profil' => $photo,
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNotNull($user->foto_profil);
        Storage::disk('public')->assertExists($user->foto_profil);
    }

    public function test_user_can_delete_existing_profile_photo(): void
    {
        Storage::fake('public');

        $path = 'profile-photos/sample.jpg';
        Storage::disk('public')->put($path, 'dummy image content');

        $user = User::factory()->create([
            'foto_profil' => $path,
        ]);

        $response = $this->actingAs($user)->delete(route('profile.destroy-photo'));

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNull($user->foto_profil);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_navbar_header_renders_profile_dropdown_link(): void
    {
        $user = User::factory()->create([
            'name' => 'Super Administrator',
            'role' => 'super_admin',
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertOk();
        $response->assertSee(route('profile.edit'));
        $response->assertSee('Profil Saya');
    }
}
