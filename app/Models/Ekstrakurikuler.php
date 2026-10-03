<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Ekstrakurikuler extends Model
{
    use HasFactory;

    protected $table = 'ekstrakurikuler';

    protected $primaryKey = 'ekstrakurikulerID';

    protected $fillable = [
        'nama',
        'sekolah',
        'deskripsi',
        'logo',
        'dokumentasi',
    ];

    public function getSekolahAttribute($value)
    {
        return $value ?: 'SMKN 2 Karanganyar';
    }

    public function logoUrl(): ?string
    {
        if (blank($this->logo)) {
            return null;
        }
        if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
            return $this->logo;
        }
        if (str_starts_with($this->logo, 'assets/') || str_starts_with($this->logo, '/assets/')) {
            return asset(ltrim($this->logo, '/'));
        }

        return Storage::disk('public')->url($this->logo);
    }

    public function dokumentasiUrl(): ?string
    {
        if (blank($this->dokumentasi)) {
            return null;
        }
        if (str_starts_with($this->dokumentasi, 'http://') || str_starts_with($this->dokumentasi, 'https://')) {
            return $this->dokumentasi;
        }
        if (str_starts_with($this->dokumentasi, 'assets/') || str_starts_with($this->dokumentasi, '/assets/')) {
            return asset(ltrim($this->dokumentasi, '/'));
        }

        return Storage::disk('public')->url($this->dokumentasi);
    }
}
