<?php

namespace App\Models;

use Database\Factories\SuratPengajuanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'nomor_surat',
    'dudi_id',
    'tanggal_surat',
    'tgl_mulai_pkl',
    'tgl_selesai_pkl',
    'file_pdf_path',
])]
class SuratPengajuan extends Model
{
    /** @use HasFactory<SuratPengajuanFactory> */
    use HasFactory;

    /**
     * Prefix nomor surat resmi PKL sekolah.
     */
    public const NOMOR_PREFIX = '421/BKK';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'tgl_mulai_pkl' => 'date',
            'tgl_selesai_pkl' => 'date',
        ];
    }

    /**
     * DUDI tujuan surat.
     */
    public function dudi(): BelongsTo
    {
        return $this->belongsTo(Dudi::class, 'dudi_id');
    }

    /**
     * Seluruh penempatan PKL yang dihasilkan surat ini.
     */
    public function penempatanPkls(): HasMany
    {
        return $this->hasMany(PenempatanPkl::class, 'surat_pengajuan_id');
    }

    /**
     * Hanya penempatan yang FIX dari surat ini.
     */
    public function penempatanFix(): HasMany
    {
        return $this->hasMany(PenempatanPkl::class, 'surat_pengajuan_id')
            ->where('status_penempatan', PenempatanPkl::STATUS_FIX);
    }

    /**
     * Generate nomor surat berikutnya: 421/BKK/{tahun}/{index}
     * Index dimulai dari 1 dan reset setiap tahun.
     */
    public static function generateNomorSurat(?int $tahun = null): string
    {
        $tahun ??= now()->year;

        // `tanggal_surat` bertipe DATE dan berindeks, jadi pakai rentang tanggal
        // (sargable). `whereYear` akan membungkus kolom dengan fungsi year()
        // sehingga indeks tidak terpakai.
        $jumlahTahunIni = static::whereBetween('tanggal_surat', [
            $tahun.'-01-01',
            $tahun.'-12-31',
        ])->count();

        $index = $jumlahTahunIni + 1;

        // Pastikan index tidak bentrok bila ada nomor manual / soft delete
        while (static::where('nomor_surat', sprintf('%s/%d/%d', self::NOMOR_PREFIX, $tahun, $index))->exists()) {
            $index++;
        }

        return sprintf('%s/%d/%d', self::NOMOR_PREFIX, $tahun, $index);
    }

    /**
     * Surat yang PDF-nya belum digenerate.
     */
    public function scopeBelumPunyaPdf(Builder $query): Builder
    {
        return $query->whereNull('file_pdf_path');
    }

    /**
     * Durasi PKL dalam hitungan hari.
     */
    public function getDurasiPklAttribute(): int
    {
        return $this->tgl_mulai_pkl->diffInDays($this->tgl_selesai_pkl);
    }
}
