<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ppdb_informasi extends Model
{
    protected $table = 'ppdb_informasi';

    protected $fillable = [
        'judul',
        'keterangan',
        'path_file',
        'img',
    ];

    protected $casts = [
        'img' => 'array',
    ];
}
