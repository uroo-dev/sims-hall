<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ppdb_jurusan extends Model
{
    protected $table = 'ppdb_jurusan';

    protected $fillable = [
        'jurusan_id',
        'daya_tampung',
        'img',
    ];

    /**
     * Relasi ke model Jurusan (tabel jurusan, PK jurusanID).
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id', 'jurusanID');
    }

    /**
     * Accessor nama_jurusan dari relasi Jurusan untuk kemudahan akses dan kompatibilitas.
     */
    public function getNamaJurusanAttribute(): ?string
    {
        return $this->jurusan?->nama;
    }
}
