<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['judul', 'deskripsi'])]
class Facility extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'facilities';

    /**
     * Relasi ke paket peminjaman aula yang menggunakan fasilitas ini melalui tabel detail.
     */
    public function paketPeminjamans(): BelongsToMany
    {
        return $this->belongsToMany(PaketPeminjaman::class, 'detail_paket_peminjamans', 'facility_id', 'paket_peminjaman_id')
            ->withTimestamps();
    }

    /**
     * Relasi ke baris tabel detail paket.
     */
    public function details(): HasMany
    {
        return $this->hasMany(DetailPaketPeminjaman::class, 'facility_id');
    }
}
