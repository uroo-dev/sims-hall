<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformasiPpdb extends Model
{
    use HasFactory;

    protected $fillable = [
        'ppdb_id', 'nama_agenda', 'tanggal_mulai', 'tanggal_akhir',
        'keterangan', 'persyaratan', 'daya_tampung', 'dokumentasi',
    ];

    public function ppdb()
    {
        return $this->belongsTo(Ppdb::class);
    }
}
