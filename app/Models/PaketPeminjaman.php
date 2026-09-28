<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['facility_id', 'kategori', 'harga', 'deskripsi'])]
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
     * Relasi ke fasilitas utama paket.
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }
}
