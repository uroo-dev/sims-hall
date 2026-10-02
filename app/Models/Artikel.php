<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'kategori_artikel_id',
    'user_id',
    'judul',
    'slug',
    'ringkasan',
    'konten',
    'gambar',
    'status',
    'published_at',
    'views',
])]
class Artikel extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'views' => 'integer',
        ];
    }

    /**
     * Relasi ke kategori artikel.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriArtikel::class, 'kategori_artikel_id');
    }

    /**
     * Relasi ke user / penulis artikel.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope untuk artikel yang sudah terbit / published.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Slug kategori yang menampung data prestasi.
     *
     * Prestasi tidak lagi memakai tabel `prestasis`; sebuah prestasi adalah artikel
     * berstatus published yang kategorinya kategori ini.
     */
    public const KATEGORI_PRESTASI = 'prestasi';

    /**
     * Scope artikel prestasi — kategori "Prestasi" dan sudah terbit.
     *
     * Dicocokkan lewat slug supaya bebas dari masalah kapitalisasi ("Prestasi",
     * "PRESTASI") yang biasa terjadi saat kategori dibuat dari dashboard admin.
     */
    public function scopePrestasi($query)
    {
        return $query->published()
            ->whereHas('kategori', fn ($q) => $q->where('slug', self::KATEGORI_PRESTASI));
    }

    /**
     * URL publik dari gambar utama artikel.
     *
     * Menangani tiga sumber: path di disk `public` (default), path relatif di
     * `public/assets`, dan URL absolut. Mengembalikan null bila tidak ada gambar
     * supaya pemanggil bisa menentukan sendiri fallback-nya.
     */
    public function gambarUrl(): ?string
    {
        if (blank($this->gambar)) {
            return null;
        }

        if (str_starts_with($this->gambar, 'http://') || str_starts_with($this->gambar, 'https://')) {
            return $this->gambar;
        }

        if (str_starts_with($this->gambar, 'assets/')) {
            return asset($this->gambar);
        }

        return Storage::disk('public')->url($this->gambar);
    }
}
