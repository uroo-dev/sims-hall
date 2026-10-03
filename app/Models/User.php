<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'email', 'password', 'username', 'role', 'foto_profil'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * URL Foto Profil atau Fallback Avatar.
     */
    public function getFotoProfilUrlAttribute(): string
    {
        if ($this->foto_profil && Storage::disk('public')->exists($this->foto_profil)) {
            return asset('storage/'.$this->foto_profil);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name ?? 'User').'&background=0073c6&color=fff&size=128';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Data guru terkait (jika ada).
     */
    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class);
    }

    /**
     * Data siswa terkait (jika ada).
     */
    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class);
    }

    /**
     * Cek apakah user adalah super admin.
     */
    public function isSuperAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'super_duper_admin'], true);
    }

    /**
     * Kembalikan URL dashboard yang sesuai dengan role user.
     * Dipakai oleh AuthController (setelah login) dan DashboardController (dispatcher /dashboard).
     */
    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'pelanggan' => route('customer.dashboard'),
            'kepala_sekolah' => route('kepala-sekolah.dashboard'),
            'bkk', 'admin_pklbkk' => route('pkl.dashboard'),
            'admin_produk', 'admin_produk_unggulan' => route('produk-unggulan.index'),
            'admin_ppdb' => route('index.dashboard.ppdb'),
            'admin_kesiswaan' => route('admin.kesiswaan.index'),
            'admin_master' => route('datamaster.index'),
            'admin_sekolah' => route('admin.artikel.index'),
            default => route('admin.peminjaman.dashboard'),
        };
    }

    /**
     * Cek apakah user memiliki akses ke fitur/modul tertentu berdasarkan role langsung.
     */
    public function hasFitur(string $fiturName): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->role === 'admin_'.$fiturName) {
            return true;
        }

        if ($fiturName === 'aula' && $this->role === 'admin_aula') {
            return true;
        }

        if ($fiturName === 'master' && $this->role === 'admin_master') {
            return true;
        }

        if ($fiturName === 'kesiswaan' && $this->role === 'admin_kesiswaan') {
            return true;
        }

        if (in_array($fiturName, ['produk', 'produk_unggulan'], true) && in_array($this->role, ['admin_produk', 'admin_produk_unggulan'], true)) {
            return true;
        }

        if (in_array($fiturName, ['pklbkk', 'bkk'], true) && in_array($this->role, ['bkk', 'admin_pklbkk'], true)) {
            return true;
        }

        if ($fiturName === 'ppdb' && $this->role === 'admin_ppdb') {
            return true;
        }

        if (in_array($fiturName, ['sekolah', 'artikel'], true) && $this->role === 'admin_sekolah') {
            return true;
        }

        return false;
    }
}
