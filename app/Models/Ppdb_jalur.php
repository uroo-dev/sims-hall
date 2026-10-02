<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ppdb_jalur extends Model
{
    protected $table = 'ppdb_jalur';

    protected $fillable = [
        'nama_jalur',
        'percentase',
    ];
}
