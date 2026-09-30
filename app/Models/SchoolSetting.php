<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kunci', 'nilai'])]
class SchoolSetting extends Model
{
    /**
     * Ambil nilai pengaturan berdasarkan kunci.
     */
    public static function ambil(string $kunci, ?string $default = null): ?string
    {
        $setting = static::query()->where('kunci', $kunci)->first();

        return $setting?->nilai ?: $default;
    }
}
