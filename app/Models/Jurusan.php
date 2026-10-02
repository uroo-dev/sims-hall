<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

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

    /**
     * URL publik dari file dokumentasi jurusan (jika ada).
     */
    public function dokumentasiUrl(): ?string
    {
        if (blank($this->dokumentasi)) {
            return null;
        }

        return Storage::disk('public')->url($this->dokumentasi);
    }

    /**
     * Warna aksen landing page, dipetakan dari nama jurusan.
     */
    protected function warna(): Attribute
    {
        return Attribute::get(function (): string {
            $nama = str($this->nama)->lower();

            return match (true) {
                $nama->contains(['rekayasa perangkat lunak', 'rpl']) => '#28C76F',
                $nama->contains(['permesinan', 'mesin']) => '#0066C4',
                $nama->contains(['kain', 'tekstil', 'busana']) => '#D97706',
                $nama->contains(['ototronik', 'otomotif', 'oto']) => '#FF4D4D',
                default => '#0066C4',
            };
        });
    }
}
