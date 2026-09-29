<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_paket', 'kategori', 'harga', 'harga_dp', 'deskripsi'])]
class PaketPeminjaman extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'paket_peminjamans';

    /**
     * Relasi many-to-many ke Facility melalui detail_paket_peminjamans.
     */
    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'detail_paket_peminjamans', 'paket_peminjaman_id', 'facility_id')
            ->withTimestamps();
    }

    /**
     * Relasi has-many ke tabel detail pivot.
     */
    public function details(): HasMany
    {
        return $this->hasMany(DetailPaketPeminjaman::class, 'paket_peminjaman_id');
    }

    /**
     * Nama tampilan paket peminjaman.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->nama_paket ?: 'Paket '.ucwords($this->kategori);
    }
}
