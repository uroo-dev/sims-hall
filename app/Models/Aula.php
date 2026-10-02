<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'judul', 'deskripsi', 'dokumentasi', 'dokumentasi_2'];

    /**
     * Resolusi URL foto dokumentasi utama (pertama) aula dengan fallback ke logo sekolah.
     */
    public function getFotoDokumentasiUrlAttribute(): string
    {
        return $this->resolveImageUrl($this->dokumentasi);
    }

    /**
     * Resolusi URL foto dokumentasi pendukung (kedua) aula dengan fallback ke logo sekolah.
     */
    public function getFotoDokumentasi2UrlAttribute(): string
    {
        return $this->resolveImageUrl($this->dokumentasi_2);
    }

    /**
     * Cek apakah dokumentasi pertama tersedia berupa foto kustom aula.
     */
    public function getHasCustomDokumentasiAttribute(): bool
    {
        return ! empty($this->dokumentasi) && $this->foto_dokumentasi_url !== asset('assets/logosmkk.png');
    }

    /**
     * Cek apakah dokumentasi kedua tersedia berupa foto kustom aula.
     */
    public function getHasCustomDokumentasi2Attribute(): bool
    {
        return ! empty($this->dokumentasi_2) && $this->foto_dokumentasi_2_url !== asset('assets/logosmkk.png');
    }

    /**
     * Helper resolver path gambar yang fleksibel (mendukung storage symlink, assets, URL, dan disk).
     */
    public function resolveImageUrl(?string $path, string $fallback = 'assets/logosmkk.png'): string
    {
        if (empty($path)) {
            return asset($fallback);
        }

        // URL eksternal atau data URI
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
            return $path;
        }

        // Path di public/storage/{path} (ungggahan disk public via store('aula', 'public'))
        if (file_exists(public_path('storage/'.$path))) {
            return asset('storage/'.$path);
        }

        // Path yang sudah diawali 'storage/'
        if (str_starts_with($path, 'storage/') && file_exists(public_path($path))) {
            return asset($path);
        }

        // Path di public/assets/{path}
        if (file_exists(public_path('assets/'.$path))) {
            return asset('assets/'.$path);
        }

        // Path langsung di public/{path}
        if (file_exists(public_path($path))) {
            return asset($path);
        }

        // Fallback default: logo resmi sekolah jika file tidak ditemukan
        return asset($fallback);
    }
}
