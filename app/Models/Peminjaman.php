<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'paket_peminjaman_id',
    'nama',
    'email_instansi',
    'tanggal_mulai',
    'tanggal_selesai',
    'catatan',
    'surat_pengantar',
    'status',
])]
class Peminjaman extends Model
{
    use HasFactory;

    /**
     * Nama tabel database.
     *
     * @var string
     */
    protected $table = 'peminjamans';

    /**
     * Tipe casting atribut model.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'datetime',
            'tanggal_selesai' => 'datetime',
        ];
    }

    /**
     * Relasi ke paket peminjaman.
     */
    public function paketPeminjaman(): BelongsTo
    {
        return $this->belongsTo(PaketPeminjaman::class, 'paket_peminjaman_id');
    }

    /**
     * Relasi ke ringkasan tagihan pembayaran.
     */
    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'peminjaman_id');
    }

    /**
     * Relasi ke data persetujuan (approval flow).
     */
    public function persetujuans(): HasMany
    {
        return $this->hasMany(Persetujuan::class, 'peminjaman_id');
    }
}
