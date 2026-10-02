<?php

namespace App\Models;

use App\Observers\ProdukUnggulanObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['judul', 'deskripsi', 'dokumentasi', 'jurusan_id'])]
#[ObservedBy(ProdukUnggulanObserver::class)]
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
     * Judul dipecah dua baris: baris pertama gelap, baris kedua aksen biru.
     *
     * @return array{atas: string, bawah: string}
     */
    protected function judulBaris(): Attribute
    {
        return Attribute::get(function (): array {
            $judul = trim((string) $this->judul);
            $sekolah = trim((string) config('sekolah.nama_pendek'));

            $posisi = $sekolah !== '' ? mb_strpos($judul, $sekolah) : false;

            if ($posisi === false) {
                return ['atas' => $judul, 'bawah' => ''];
            }

            return [
                'atas' => trim(mb_substr($judul, 0, $posisi)),
                'bawah' => trim(mb_substr($judul, $posisi)),
            ];
        });
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
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    protected function dokumentasiUrls(): Attribute
    {
        return Attribute::get(fn (): array => array_map(
            fn (string $path): string => Storage::disk('public')->url($path),
            $this->dokumentasi_list,
        ));
    }
}
