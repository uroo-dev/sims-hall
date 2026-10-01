<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama', 'deskripsi', 'dokumentasi'])]
class Jurusan extends Model
{
    protected $table = 'jurusan';

    protected $primaryKey = 'jurusanID';

    public $incrementing = true;

    protected $keyType = 'int';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jurusanID' => 'integer',
        ];
    }

    /**
     * Produk unggulan yang dibuat oleh jurusan ini.
     *
     * @return HasMany<Produk, $this>
     */
    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class, 'jurusanID', 'jurusanID');
    }
}
