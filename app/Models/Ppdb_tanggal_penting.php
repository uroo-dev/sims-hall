<?php

namespace App\Models;

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
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
}
