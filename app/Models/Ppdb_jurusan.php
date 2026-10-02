<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ppdb_jurusan extends Model
{
    protected $table = 'ppdb_jurusan';

    protected $fillable = [
        'nama_jurusan',
        'daya_tampung',
        'img',
    ];
}
