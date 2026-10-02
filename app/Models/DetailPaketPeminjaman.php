<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['paket_peminjaman_id', 'facility_id'])]
class DetailPaketPeminjaman extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'detail_paket_peminjamans';

    /**
     * Relasi ke paket peminjaman.
     */
    public function paketPeminjaman(): BelongsTo
    {
        return $this->belongsTo(PaketPeminjaman::class, 'paket_peminjaman_id');
    }

    /**
     * Relasi ke fasilitas aula.
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }
}
