<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Ambil nama fitur dari relasi (asumsi hasOne, jika hasMany gunakan ->first()->nama_fitur)
        $fiturName = $request->user()->fitur?->nama_fitur;

        // Tentukan tujuan redirect berdasarkan fitur
        $dashboard = match ($fiturName) {
            'master' => route('datamaster.index'),
            'pklbkk' => route('dashboard.pkl'),
            default  => route('dashboard'),
        };

        return redirect()->intended($dashboard);
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