<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TataTertib extends Model
{
    use HasFactory;

    protected $table = 'tata_tertib';

    protected $primaryKey = 'tata_tertibID';

    protected $fillable = [
        'judul',
        'aturan',
        'deskripsi',
        'file_pdf',
        'demerit_pok',
        'skor',
    ];

    public function getJudulAttribute($value)
    {
        return $value ?: $this->aturan;
    }

    public function filePdfUrl(): ?string
    {
        if (blank($this->file_pdf)) {
            return null;
        }
        if (str_starts_with($this->file_pdf, 'http://') || str_starts_with($this->file_pdf, 'https://')) {
            return $this->file_pdf;
        }

        return Storage::disk('public')->url($this->file_pdf);
    }
}
