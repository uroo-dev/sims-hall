<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ppdb_master extends Model
{
    protected $table = 'ppdb_master';

    protected $fillable = [
        'judul',
        'deskripsi',
        'banner_img',
    ];
}
