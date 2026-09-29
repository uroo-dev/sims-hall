<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'peminjaman_id',
    'approver_id',
    'level',
    'status',
    'catatan_approval',
    'tanggal_proses',
])]
class Persetujuan extends Model
{
    use HasFactory;

    /**
     * Nama tabel database.
     *
     * @var string
     */
    protected $table = 'persetujuans';

    /**
     * Tipe casting atribut model.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_proses' => 'datetime',
        ];
    }

    /**
     * Relasi ke data peminjaman.
     */
    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    /**
     * Relasi ke approver (User / Pimpinan / Admin).
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
