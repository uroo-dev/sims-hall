<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'peminjaman_id',
    'kode_pembayaran',
    'total_tagihan',
    'total_terbayar',
    'total_refund',
    'sisa_tagihan',
    'status_pembayaran',
    'jatuh_tempo_dp',
    'jatuh_tempo_pelunasan',
    'catatan',
])]
class Pembayaran extends Model
{
    use HasFactory;

    /**
     * Nama tabel database.
     *
     * @var string
     */
    protected $table = 'pembayarans';

    /**
     * Tipe casting atribut model.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_tagihan' => 'decimal:2',
            'total_terbayar' => 'decimal:2',
            'total_refund' => 'decimal:2',
            'sisa_tagihan' => 'decimal:2',
            'jatuh_tempo_dp' => 'datetime',
            'jatuh_tempo_pelunasan' => 'datetime',
        ];
    }

    /**
     * Relasi ke data peminjaman aula.
     */
    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    /**
     * Relasi ke seluruh detail riwayat transfer / cicilan.
     */
    public function details(): HasMany
    {
        return $this->hasMany(DetailPembayaran::class, 'pembayaran_id')->orderBy('id');
    }

    /**
     * Shortcut relasi ke transaksi DP (Cicilan 1).
     */
    public function dp(): HasOne
    {
        return $this->hasOne(DetailPembayaran::class, 'pembayaran_id')->where('tipe_pembayaran', 'dp');
    }

    /**
     * Shortcut relasi ke transaksi Pelunasan (Cicilan 2).
     */
    public function pelunasan(): HasOne
    {
        return $this->hasOne(DetailPembayaran::class, 'pembayaran_id')->where('tipe_pembayaran', 'pelunasan');
    }

    /**
     * Cek apakah tagihan sudah lunas sepenuhnya.
     */
    public function isLunas(): bool
    {
        return $this->status_pembayaran === 'lunas' || ($this->total_tagihan > 0 && $this->total_terbayar >= $this->total_tagihan);
    }

    /**
     * Cek apakah DP sudah terverifikasi (partial).
     */
    public function isPartial(): bool
    {
        return $this->status_pembayaran === 'partial';
    }

    /**
     * Sinkronisasi akumulasi total terbayar, sisa tagihan, dan status pembayaran
     * berdasarkan seluruh detail pembayaran yang berstatus 'verified'.
     */
    public function syncAkumulasiPembayaran(): void
    {
        if ($this->status_pembayaran === 'free') {
            $this->total_terbayar = 0;
            $this->sisa_tagihan = 0;
            $this->save();

            return;
        }

        $totalVerified = (float) $this->details()->where('status', 'verified')->sum('jumlah_bayar');
        $totalTagihan = (float) $this->total_tagihan;
        $sisa = max(0, $totalTagihan - $totalVerified);

        $this->total_terbayar = $totalVerified;
        $this->sisa_tagihan = $sisa;

        if ($totalVerified >= $totalTagihan && $totalTagihan > 0) {
            $this->status_pembayaran = 'lunas';
        } elseif ($totalVerified > 0) {
            $this->status_pembayaran = 'partial';
        } else {
            $this->status_pembayaran = 'pending';
        }

        $this->save();
    }
}
