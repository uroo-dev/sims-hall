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

#[Fillable(['name', 'email', 'password', 'username', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The relationships that should always be loaded.
     *
     * @var array<int, string>
     */
    protected $with = ['fitur'];

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
     * Relasi ke Fitur yang ditugaskan kepada user (terutama role admin).
     */
    public function fitur(): HasOne
    {
        return $this->hasOne(Fitur::class);
    }

    /**
     * Cek apakah user adalah super admin.
     */
    public function isSuperAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'super_duper_admin'], true);
    }

    /**
     * Cek apakah user memiliki akses ke fitur tertentu.
     */
    public function hasFitur(string $fiturName): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->role !== 'admin') {
            return false;
        }

        return $this->fitur?->nama_fitur === $fiturName;
    }
}
