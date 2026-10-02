<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama', 'slug', 'deskripsi'])]
class KategoriArtikel extends Model
{
    use HasFactory;

    /**
     * Relasi ke artikel di dalam kategori ini.
     */
    public function artikels(): HasMany
    {
        return $this->hasMany(Artikel::class, 'kategori_artikel_id');
    }
}
