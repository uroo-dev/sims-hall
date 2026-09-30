<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketPeminjaman extends Model
{
    use HasFactory;

    // Paksa nama tabel agar tidak di-plural-kan otomatis oleh Laravel
    protected $table = 'paket_peminjamans';

    protected $fillable = ['nama_paket', 'harga', 'kategori', 'durasi', 'fasilitas', 'deskripsi'];
}