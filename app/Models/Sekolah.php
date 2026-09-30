<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 
        'dokumentasi', 
        'sejarah',
        'profil_judul', 
        'profil_deskripsi', 
        'profil_dokumentasi',
        'visi', 
        'misi', 
        'sambutan_kepsek', 
        'nama_kepsek', 
        'foto_kepsek', 
        'yel_yel'
    ];
}