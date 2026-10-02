<?php

namespace App\Models;

use Database\Factories\SiswaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nis', 'nama', 'kelas', 'jurusan', 'no_hp'])]
class Siswa extends Model
{
    /** @use HasFactory<SiswaFactory> */
    use HasFactory;

    /**
     * Seluruh riwayat penempatan PKL siswa ini.
     */
    public function penempatanPkls(): HasMany
    {
        return $this->hasMany(PenempatanPkl::class, 'siswa_id');
    }

    /**
     * Penempatan PKL aktif (belum ditolak) - pivot terakhir.
     */
    public function penempatanAktif(): HasMany
    {
        return $this->hasMany(PenempatanPkl::class, 'siswa_id')
            ->whereIn('status_penempatan', [PenempatanPkl::STATUS_PENGAJUAN, PenempatanPkl::STATUS_FIX])
            ->latestOfMany();
    }

    /**
     * Penempatan PKL yang sudah FIX / confirmed.
     */
    public function penempatanFix(): HasMany
    {
        return $this->hasMany(PenempatanPkl::class, 'siswa_id')
            ->where('status_penempatan', PenempatanPkl::STATUS_FIX);
    }

    /**
     * Siswa sudah punya tempat PKL FIX?
     */
    public function scopeSudahPkl($query)
    {
        return $query->whereHas('penempatanFix');
    }

    /**
     * Siswa belum punya tempat PKL FIX.
     */
    public function scopeBelumPkl($query)
    {
        return $query->whereDoesntHave('penempatanFix');
    }

    /**
     * Filter berdasarkan kelas.
     */
    public function scopeOfKelas($query, string $kelas)
    {
        return $query->where('kelas', $kelas);
    }

    /**
     * Filter berdasarkan jurusan.
     */
    public function scopeOfJurusan($query, string $jurusan)
    {
        return $query->where('jurusan', $jurusan);
    }

    /**
     * Label kelas untuk tampilan, mis. "XII RPL 1".
     */
    public function getLabelKelasAttribute(): string
    {
        return trim("{$this->kelas} {$this->jurusan}");
    }
}
