<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'judul', 'deskripsi', 'dokumentasi', 'dokumentasi_2'];
}