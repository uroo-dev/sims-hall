<?php

namespace App\Models;

use Database\Factories\LowonganFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'dudi_id',
    'nama_perusahaan',
    'posisi',
    'tipe',
    'jurusan_sesuai',
    'deskripsi',
    'link_daftar',
    'deadline',
    'is_active',
])]
class Lowongan extends Model
{
    /** @use HasFactory<LowonganFactory> */
    use HasFactory;

    public const TIPE_PEKERJAAN = 'Pekerjaan';

    public const TIPE_MAGANG = 'Magang';

    /**
     * Nilai `jurusan_sesuai` yang berarti lowongan terbuka untuk semua jurusan.
     */
    public const SEMUA_JURUSAN = 'Semua Jurusan';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * DUDI penerbit lowongan (nullable - bisa lowongan umum).
     */
    public function dudi(): BelongsTo
    {
        return $this->belongsTo(Dudi::class, 'dudi_id');
    }

    /**
     * Lowongan aktif: is_active = true DAN deadline >= today.
     * Scope utama untuk Career Center publik / Modul 5.
     *
     * `deadline` bertipe DATE, jadi dibandingkan langsung (bukan `whereDate`)
     * agar indeks komposit (is_active, deadline) tetap bisa dipakai MySQL.
     */
    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where('deadline', '>=', today());
    }

    /**
     * Lowongan yang sudah lewat deadline (masih aktif di flag).
     */
    #[Scope]
    protected function expired(Builder $query): Builder
    {
        return $query->where('deadline', '<', today());
    }

    /**
     * Filter per tipe: Pekerjaan / Magang.
     */
    #[Scope]
    protected function tipe(Builder $query, string $tipe): Builder
    {
        return $query->where('tipe', $tipe);
    }

    /**
     * Filter lowongan yang cocok untuk jurusan tertentu.
     * Lowongan "Semua Jurusan" otomatis ikut.
     */
    #[Scope]
    protected function untukJurusan(Builder $query, string $jurusan): Builder
    {
        return $query->where(function (Builder $q) use ($jurusan) {
            $q->where('jurusan_sesuai', self::SEMUA_JURUSAN)
                ->orWhere('jurusan_sesuai', 'like', "%{$jurusan}%");
        });
    }

    /**
     * Sisa hari sebelum deadline (0 pada hari terakhir).
     */
    public function getSisaHariAttribute(): int
    {
        return (int) today()->diffInDays($this->deadline, false);
    }

    /**
     * Deadline sudah lewat?
     */
    public function getSudahLewatAttribute(): bool
    {
        return $this->deadline->isPast();
    }
}
