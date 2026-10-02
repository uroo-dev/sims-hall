<?php

namespace App\Models;

use Database\Factories\DudiFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'nama_dudi',
    'nama',
    'alamat',
    'kota',
    'bidang_usaha',
    'kontak_person',
    'no_hp',
    'kuota_maksimal',
    'is_mitra_resmi',
    'tampil_di_landing',
    'logo',
    'program_1',
    'program_2',
    'program_3',
    'deskripsi',
    'jurusan_id',
])]
class Dudi extends Model
{
    /** @use HasFactory<DudiFactory> */
    use HasFactory;

    /**
     * Kolom sumber untuk atribut `nama`.
     *
     * Kolom fisik tetap `nama_dudi` (dipakai modul PKL & BKK). Atribut `nama`
     * hanya alias baca/tulis supaya landing page yang ditulis untuk tabel
     * versi lain tidak perlu diubah, dan tidak ada data yang terduplikasi.
     */
    private const KOLOM_NAMA = 'nama_dudi';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kuota_maksimal' => 'integer',
            'is_mitra_resmi' => 'boolean',
            'tampil_di_landing' => 'boolean',
        ];
    }

    /**
     * Alias baca untuk `nama_dudi`, dipakai landing page.
     */
    protected function nama(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->attributes[self::KOLOM_NAMA] ?? null,
            set: fn (?string $value) => [self::KOLOM_NAMA => $value],
        );
    }

    /**
     * Jurusan asal mitra industri, bila terhubung.
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    /**
     * Surat pengajuan PKL yang ditujukan ke DUDI ini.
     */
    public function suratPengajuans(): HasMany
    {
        return $this->hasMany(SuratPengajuan::class, 'dudi_id');
    }

    /**
     * Seluruh penempatan PKL di DUDI ini.
     */
    public function penempatanPkls(): HasMany
    {
        return $this->hasMany(PenempatanPkl::class, 'dudi_id');
    }

    /**
     * Penempatan PKL di DUDI ini yang sudah dikonfirmasi (status FIX).
     */
    public function penempatanFix(): HasMany
    {
        return $this->hasMany(PenempatanPkl::class, 'dudi_id')
            ->where('status_penempatan', PenempatanPkl::STATUS_FIX);
    }

    /**
     * Siswa yang sudah FIX di DUDI ini - untuk Landing Page & Chatbot AI.
     */
    public function siswaFix(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'penempatan_pkls', 'dudi_id', 'siswa_id')
            ->where('penempatan_pkls.status_penempatan', PenempatanPkl::STATUS_FIX)
            ->withPivot(['status_penempatan', 'created_at'])
            ->latest('penempatan_pkls.created_at');
    }

    /**
     * Lowongan kerja yang diterbitkan DUDI ini.
     */
    public function lowongans(): HasMany
    {
        return $this->hasMany(Lowongan::class, 'dudi_id');
    }

    /**
     * DUDI mitra resmi (sudah kerja sama resmi dengan sekolah).
     */
    public function scopeMitraResmi(Builder $query): Builder
    {
        return $query->where('is_mitra_resmi', true);
    }

    /**
     * DUDI yang sudah di-ACC BKK untuk tampil di Landing Page.
     * Scope utama untuk integrasi Modul 5 (Landing + Chatbot AI).
     */
    public function scopeForLandingPage(Builder $query): Builder
    {
        return $query->where('tampil_di_landing', true);
    }

    /**
     * DUDI yang kuotanya belum penuh (dihitung dari penempatan FIX).
     *
     * Catatan: alias dari withCount tidak bisa dipakai di whereColumn, dan
     * HAVING butuh GROUP BY yang tidak didukung baik oleh MariaDB pada mode
     * ONLY_FULL_GROUP_BY. Karena itu filter memakai subquery terelasi langsung.
     */
    public function scopeKapasitasTersisa(Builder $query): Builder
    {
        $fixCount = PenempatanPkl::query()
            ->selectRaw('count(*)')
            ->whereColumn('penempatan_pkls.dudi_id', 'dudis.id')
            ->where('penempatan_pkls.status_penempatan', PenempatanPkl::STATUS_FIX);

        return $query->withCount(['penempatanFix as penempatan_fix_count'])
            ->where(function (Builder $q) use ($fixCount) {
                $q->whereRaw("({$fixCount->toSql()}) < dudis.kuota_maksimal", $fixCount->getBindings())
                    ->orWhere('dudis.kuota_maksimal', '<=', 0);
            });
    }

    /**
     * Sisa kuota DUDI (dihitung dari penempatan FIX).
     */
    public function getSisaKuotaAttribute(): int
    {
        if ((int) $this->kuota_maksimal <= 0) {
            return 0;
        }

        return max(0, (int) $this->kuota_maksimal - $this->hitungFix());
    }

    /**
     * Jumlah siswa FIX di DUDI ini.
     */
    public function getJumlahSiswaFixAttribute(): int
    {
        return $this->hitungFix();
    }

    /**
     * Jumlah penempatan FIX, memakai hasil eager load bila tersedia.
     *
     * Dipakai oleh `sisa_kuota` / `jumlah_siswa_fix` yang dipanggil di dalam
     * loop Blade. Tanpa cache ini, setiap baris akan menembak satu query
     * (N+1). Query ini juga menjadi satu-satunya sumber angka FIX, sehingga
     * scope `kapasitasTersisa` cukup `withCount` sekali.
     */
    private function hitungFix(): int
    {
        if (array_key_exists('penempatan_fix_count', $this->attributes)) {
            return (int) $this->attributes['penempatan_fix_count'];
        }

        if ($this->relationLoaded('penempatanFix')) {
            return $this->penempatanFix->count();
        }

        return $this->penempatanFix()->count();
    }

    /**
     * URL logo DUDI (mendukung aset di public/assets dan storage upload).
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (empty($this->logo)) {
            return null;
        }

        if (file_exists(public_path('assets/'.$this->logo))) {
            return asset('assets/'.$this->logo);
        }

        if (file_exists(public_path('storage/'.$this->logo))) {
            return asset('storage/'.$this->logo);
        }

        if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
            return $this->logo;
        }

        return asset('storage/'.$this->logo);
    }
}
