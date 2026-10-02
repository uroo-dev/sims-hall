<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'pembayaran_id',
    'kode_transaksi',
    'tipe_pembayaran',
    'jumlah_bayar',
    'metode',
    'bank_tujuan',
    'norek_tujuan',
    'bank_pengirim',
    'norek_pengirim',
    'atas_nama_pengirim',
    'bukti_pembayaran',
    'tanggal_bayar',
    'status',
    'diverifikasi_oleh',
    'diverifikasi_pada',
    'catatan',
])]
class DetailPembayaran extends Model
{
    use HasFactory;

    /**
     * Nama tabel database.
     *
     * @var string
     */
    protected $table = 'detail_pembayarans';

    /**
     * Tipe casting atribut model.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jumlah_bayar' => 'decimal:2',
            'tanggal_bayar' => 'datetime',
            'diverifikasi_pada' => 'datetime',
        ];
    }

    /**
     * Relasi ke ringkasan tagihan utama.
     */
    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id');
    }

    /**
     * Relasi ke admin/user yang memverifikasi transaksi cicilan ini.
     */
    public function diverifikasiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    /**
     * Label tampilan tipe pembayaran (DP / Pelunasan).
     */
    public function getLabelTipeAttribute(): string
    {
        return match ($this->tipe_pembayaran) {
            'dp' => 'Cicilan 1 (Uang Muka / DP)',
            'pelunasan' => 'Cicilan 2 (Pelunasan)',
            'lunas_langsung' => 'Pembayaran Penuh (100%)',
            'refund' => 'Pengembalian Dana (Refund)',
            default => ucwords(str_replace('_', ' ', (string) $this->tipe_pembayaran)),
        };
    }

    /**
     * URL publik untuk bukti transfer pembayaran.
     */
    public function getBuktiPembayaranUrlAttribute(): ?string
    {
        if (! $this->bukti_pembayaran) {
            return null;
        }

        if (str_starts_with($this->bukti_pembayaran, 'http://') || str_starts_with($this->bukti_pembayaran, 'https://')) {
            return $this->bukti_pembayaran;
        }

        return Storage::disk('public')->url($this->bukti_pembayaran);
    }
}
