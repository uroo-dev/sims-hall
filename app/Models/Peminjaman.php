<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'paket_peminjaman_id',
    'nama',
    'email_instansi',
    'tanggal_mulai',
    'tanggal_selesai',
    'catatan',
    'surat_pengantar',
    'status',
])]
class Peminjaman extends Model
{
    use HasFactory;

    /**
     * Nama tabel database.
     *
     * @var string
     */
    protected $table = 'peminjamans';

    /**
     * Tipe casting atribut model.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'datetime',
            'tanggal_selesai' => 'datetime',
        ];
    }

    /**
     * Relasi ke paket peminjaman.
     */
    public function paketPeminjaman(): BelongsTo
    {
        return $this->belongsTo(PaketPeminjaman::class, 'paket_peminjaman_id');
    }

    /**
     * Relasi ke ringkasan tagihan pembayaran.
     */
    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'peminjaman_id');
    }

    /**
     * Relasi ke data persetujuan (approval flow).
     */
    public function persetujuans(): HasMany
    {
        return $this->hasMany(Persetujuan::class, 'peminjaman_id');
    }

    /**
     * URL publik untuk berkas surat pengantar peminjaman.
     */
    public function getSuratPengantarUrlAttribute(): ?string
    {
        if (! $this->surat_pengantar) {
            return null;
        }

        if (str_starts_with($this->surat_pengantar, 'http://') || str_starts_with($this->surat_pengantar, 'https://')) {
            return $this->surat_pengantar;
        }

        return Storage::disk('public')->url($this->surat_pengantar);
    }

    /**
     * Memeriksa dan membatalkan otomatis peminjaman yang melewati batas waktu jatuh tempo
     * (misalnya transfer ulang pembayaran yang ditolak atau tagihan yang kedaluwarsa).
     */
    public static function syncExpiredDeadlines(): void
    {
        $expiredPembayarans = Pembayaran::with('peminjaman')
            ->whereIn('status_pembayaran', ['rejected', 'pending'])
            ->whereNotNull('jatuh_tempo_dp')
            ->where('jatuh_tempo_dp', '<', now())
            ->whereHas('peminjaman', function ($q) {
                $q->where('status', '!=', 'rejected');
            })
            ->get();

        foreach ($expiredPembayarans as $pembayaran) {
            if ($pembayaran->peminjaman) {
                $pembayaran->peminjaman->update(['status' => 'rejected']);
            }
            $pembayaran->update([
                'status_pembayaran' => 'hangus',
                'catatan' => trim(($pembayaran->catatan ? $pembayaran->catatan.' | ' : '').'Peminjaman otomatis ditolak sistem karena melewati tenggat waktu pembayaran.'),
            ]);
        }
    }
}
