<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['judul', 'deskripsi', 'dokumentasi'])]
class ProdukUnggulan extends Model
{
    protected $table = 'produk_unggulan';

    protected $primaryKey = 'produk_unggulanID';

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
            'produk_unggulanID' => 'integer',
        ];
    }

    /**
     * Data single row pengaturan landing page produk unggulan.
     */
    public static function current(): self
    {
        return static::query()->first() ?? new self;
    }

    /**
     * Daftar path dokumentasi yang tersimpan (dipisah koma).
     *
     * @return list<string>
     */
    protected function dokumentasiList(): Attribute
    {
        return Attribute::get(function (): array {
            $paths = explode(',', (string) $this->dokumentasi);

            return array_values(array_filter(array_map('trim', $paths)));
        });
    }

    /**
     * URL publik dari seluruh file dokumentasi.
     *
     * @return list<string>
     */
    protected function dokumentasiUrls(): Attribute
    {
        return Attribute::get(fn (): array => array_map(
            fn (string $path): string => Storage::disk('public')->url($path),
            $this->dokumentasi_list,
        ));
    }
}
