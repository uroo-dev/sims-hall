<?php

namespace App\Models;

use Database\Factories\PenempatanPklFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'surat_pengajuan_id',
    'siswa_id',
    'dudi_id',
    'guru_id',
    'status_penempatan',
    'catatan',
])]
class PenempatanPkl extends Model
{
    /** @use HasFactory<PenempatanPklFactory> */
    use HasFactory;

    public const STATUS_PENGAJUAN = 'pengajuan';

    public const STATUS_FIX = 'FIX';

    public const STATUS_DITOLAK = 'ditolak';

    /**
     * Label status untuk ditampilkan di UI.
     *
     * @var array<string, string>
     */
    public const STATUS_LABEL = [
        self::STATUS_PENGAJUAN => 'Menunggu Balasan DUDI',
        self::STATUS_FIX => 'Diterima (FIX)',
        self::STATUS_DITOLAK => 'Ditolak DUDI',
    ];

    /**
     * Surat pengajuan asal penempatan ini.
     */
    public function suratPengajuan(): BelongsTo
    {
        return $this->belongsTo(SuratPengajuan::class, 'surat_pengajuan_id');
    }

    /**
     * Siswa yang ditempatkan.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * DUDI penempatan.
     */
    public function dudi(): BelongsTo
    {
        return $this->belongsTo(Dudi::class, 'dudi_id');
    }

    /**
     * Guru pembimbing.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    /**
     * Penempatan yang sudah dikonfirmasi DUDI (status FIX).
     */
    #[Scope]
    protected function fixPkl(Builder $query): Builder
    {
        return $query->where('status_penempatan', self::STATUS_FIX);
    }

    /**
     * Penempatan yang masih menunggu balasan DUDI.
     */
    #[Scope]
    protected function menunggu(Builder $query): Builder
    {
        return $query->where('status_penempatan', self::STATUS_PENGAJUAN);
    }

    /**
     * Penempatan yang ditolak DUDI.
     */
    #[Scope]
    protected function ditolak(Builder $query): Builder
    {
        return $query->where('status_penempatan', self::STATUS_DITOLAK);
    }

    /**
     * Filter per status.
     */
    #[Scope]
    protected function status(Builder $query, string $status): Builder
    {
        return $query->where('status_penempatan', $status);
    }

    /**
     * Penempatan pada DUDI yang sudah tayang di Landing Page.
     */
    #[Scope]
    protected function forLandingPage(Builder $query): Builder
    {
        return $query->whereHas('dudi', fn ($q) => $q->where('tampil_di_landing', true));
    }

    /**
     * Eager load relasi yang sering dipakai bersama (ringkas & aman).
     */
    public function scopeWithRelasi(Builder $query): Builder
    {
        return $query->with(['siswa', 'dudi', 'guru', 'suratPengajuan']);
    }

    /**
     * Label status ready-to-display.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABEL[$this->status_penempatan] ?? $this->status_penempatan;
    }
}
