<?php

namespace App\Models;

use Database\Factories\GuruFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'nip', 'nama', 'jurusan', 'no_hp'])]
class Guru extends Model
{
    /** @use HasFactory<GuruFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nip' => 'string',
        ];
    }

    /**
     * Akun login guru (opsional - guru bisa dibuat tanpa akun).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Seluruh penempatan PKL yang dibimbing guru ini.
     */
    public function penempatanPkls(): HasMany
    {
        return $this->hasMany(PenempatanPkl::class, 'guru_id');
    }

    /**
     * Hanya penempatan yang sudah dikonfirmasi DUDI (status FIX).
     */
    public function penempatanFix(): HasMany
    {
        return $this->hasMany(PenempatanPkl::class, 'guru_id')
            ->where('status_penempatan', PenempatanPkl::STATUS_FIX);
    }

    /**
     * Guru aktif pada suatu jurusan.
     */
    public function scopeOfJurusan($query, string $jurusan)
    {
        return $query->where('jurusan', $jurusan);
    }
}
