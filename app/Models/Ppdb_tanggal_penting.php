<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ppdb_tanggal_penting extends Model
{
    protected $table = 'ppdb_tanggal_penting';

    protected $fillable = [
        'nama_agenda',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date:Y-m-d',
        'tanggal_selesai' => 'date:Y-m-d',
    ];

    /**
     * Format rentang tanggal dalam bahasa Indonesia.
     */
    public function getRentangTanggalAttribute(): string
    {
        if (! $this->tanggal_mulai) {
            return '';
        }

        $mulai = Carbon::parse($this->tanggal_mulai)->locale('id');

        if (! $this->tanggal_selesai) {
            return $mulai->translatedFormat('d F Y');
        }

        $selesai = Carbon::parse($this->tanggal_selesai)->locale('id');

        if ($mulai->isSameDay($selesai)) {
            return $mulai->translatedFormat('d F Y');
        }

        if ($mulai->format('Y-m') === $selesai->format('Y-m')) {
            return $mulai->format('d').' - '.$selesai->translatedFormat('d F Y');
        }

        if ($mulai->isSameYear($selesai)) {
            return $mulai->translatedFormat('d F').' - '.$selesai->translatedFormat('d F Y');
        }

        return $mulai->translatedFormat('d F Y').' - '.$selesai->translatedFormat('d F Y');
    }
}
