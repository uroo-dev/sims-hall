<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['perusahaan', 'posisi', 'lokasi', 'batas_lamaran', 'link_pendaftaran', 'deskripsi', 'is_aktif'])]
class JobVacancy extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'batas_lamaran' => 'date',
            'is_aktif' => 'boolean',
        ];
    }

    /**
     * Lowongan yang masih aktif dan belum lewat batas lamaran.
     */
    public function scopeMasihDibuka(Builder $query): Builder
    {
        return $query->where('is_aktif', true)
            ->where(function (Builder $q) {
                $q->whereNull('batas_lamaran')
                    ->orWhereDate('batas_lamaran', '>=', now()->toDateString());
            });
    }
}
