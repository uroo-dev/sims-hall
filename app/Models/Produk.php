<?php

namespace App\Models;

use App\Observers\ProdukObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['kode_produk', 'nama', 'deskripsi', 'dokumentasi', 'jurusanID'])]
#[ObservedBy(ProdukObserver::class)]
class Produk extends Model
{
    protected $table = 'produk';

    protected $primaryKey = 'produkID';

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
            'produkID' => 'integer',
            'jurusanID' => 'integer',
        ];
    }

    /**
     * Jurusan pemilik produk unggulan ini.
     *
     * @return BelongsTo<Jurusan, $this>
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusanID', 'jurusanID');
    }

    /**
     * URL publik dari file dokumentasi produk (jika ada).
     */
    public function dokumentasiUrl(): ?string
    {
        if (blank($this->dokumentasi)) {
            return null;
        }

        return Storage::disk('public')->url($this->dokumentasi);
    }
}
