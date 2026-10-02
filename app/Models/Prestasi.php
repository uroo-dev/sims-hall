<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasis';

    protected $fillable = [
        'judul',
        'kategori',
        'deskripsi',
        'dokumentasi',
    ];

    public function getJudulAttribute($value)
    {
        if (! empty($value)) {
            return $value;
        }
        if (! empty($this->deskripsi)) {
            return Str::limit($this->deskripsi, 60);
        }

        return 'Prestasi '.ucfirst($this->kategori ?? 'Sekolah');
    }

    public function dokumentasiUrl(): ?string
    {
        if (blank($this->dokumentasi)) {
            return null;
        }
        if (str_starts_with($this->dokumentasi, 'http://') || str_starts_with($this->dokumentasi, 'https://')) {
            return $this->dokumentasi;
        }

        if (str_starts_with($this->dokumentasi, 'assets/')) {
            return asset($this->dokumentasi);
        }

        return Storage::disk('public')->url($this->dokumentasi);
    }
}
