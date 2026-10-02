<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppdb extends Model
{
    use HasFactory;

    protected $fillable = ['judul', 'deskripsi', 'persyaratan', 'dokumentasi', 'tahun_ajaran'];

    public function informasiPpdb()
    {
        return $this->hasMany(InformasiPpdb::class);
    }
}
