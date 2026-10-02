<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('Auth.login');
    }

    /**
     * Tampilkan halaman registrasi akun pelanggan baru.
     */
    public function register(): View
    {
        return view('Auth.register');
    }

    /**
     * Proses pendaftaran user baru dengan role pelanggan.
     */
    public function registerStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap atau nama instansi wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda strip (-), atau garis bawah (_).',
            'username.unique' => 'Username sudah digunakan, silakan pilih username lain.',
            'email.required' => 'Email kontak atau instansi wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar. Silakan login atau gunakan email lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => 'pelanggan',
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('customer.dashboard')->with('success', 'Akun berhasil didaftarkan! Selamat datang di Portal SIMS SMK Negeri 2 Karanganyar.');
    }

    /**
     * Proses autentikasi user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = [
            'username' => $validated['username'],
            'password' => $validated['password'],
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'username' => 'Kredensial tidak cocok. Periksa kembali username dan password Anda.',
            ]);
        }

        $request->session()->regenerate();

        if (Auth::user()?->role === 'pelanggan') {
            return redirect()->intended(route('customer.dashboard'));
        }

        if (Auth::user()?->role === 'kepala_sekolah') {
            return redirect()->intended(route('kepala-sekolah.dashboard'));
        }
        if (in_array(Auth::user()?->role, ['bkk', 'admin_pklbkk'], true)) {
            return redirect()->intended(route('pkl.dashboard'));
        }
        if (in_array(Auth::user()?->role, ['admin_produk', 'admin_produk_unggulan'], true)) {
            return redirect()->intended(route('produk-unggulan.index'));
        }
        return redirect()->intended(route('dashboard'));

    }

    /**
     * Proses logout user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
