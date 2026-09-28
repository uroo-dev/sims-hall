<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dudi extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'jurusan_id', 'program_1', 'program_2', 'program_3', 'deskripsi', 'logo'];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}