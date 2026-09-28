<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
     * Relasi ke paket peminjaman aula yang menggunakan fasilitas ini.
     */
    public function paketPeminjamans(): HasMany
    {
        return $this->hasMany(PaketPeminjaman::class, 'facility_id');
    }
}
