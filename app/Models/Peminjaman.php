<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'paket_peminjaman_id',
    'is_custom',
    'harga_custom',
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
            'is_custom' => 'boolean',
            'harga_custom' => 'decimal:2',
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
     * Relasi many-to-many ke fasilitas kustom yang dipilih pemohon (peminjaman custom).
     */
    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'peminjaman_facilities', 'peminjaman_id', 'facility_id')
            ->withTimestamps();
    }

    /**
     * Nama paket peminjaman yang adaptif (custom maupun paket reguler).
     */
    public function getNamaPaketAttribute(): string
    {
        if ($this->is_custom) {
            return 'Paket Custom';
        }

        return $this->paketPeminjaman?->nama_paket ?: ($this->paketPeminjaman ? 'Paket '.ucfirst($this->paketPeminjaman->kategori) : 'Paket Aula');
    }

    /**
     * Mengambil daftar fasilitas baik dari paket terpilih maupun kustom pilihan user.
     */
    public function getDaftarFasilitasAttribute()
    {
        if ($this->is_custom) {
            return $this->facilities;
        }

        return $this->paketPeminjaman ? $this->paketPeminjaman->facilities : collect();
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
     * Selisih hari antara tanggal pengajuan dibuat dengan tanggal pelaksanaan acara (hari H).
     */
    public function getSelisihBookingHariAttribute(): int
    {
        $config = PaymentConfiguration::current();
        $minConfig = (int) ($config->minimal_hari_booking ?? 3);

        $tglDibuat = Carbon::parse($this->created_at ?? now())->startOfDay();
        $tglMulai = Carbon::parse($this->tanggal_mulai)->startOfDay();

        $diffDays = (int) $tglDibuat->diffInDays($tglMulai);

        return $diffDays > 0 ? $diffDays : $minConfig;
    }

    /**
     * Jumlah hari sebelum hari H yang menjadi batas maksimal pembatalan (H-).
     * Diambil langsung dari konfigurasi offset pembatalan yang diatur Super Admin (misal: 9 untuk H-9).
     * Nilai tidak boleh melebihi selisih booking dan minimal H-1 (kurang dari hari H).
     */
    public function getHariMaksimalCancelAttribute(): int
    {
        $config = PaymentConfiguration::current();
        $hMin = (int) ($config->offset_hari_pembatalan ?? 1);

        return max(1, min($this->selisih_booking_hari, $hMin));
    }

    /**
     * Batas waktu absolut (Carbon datetime) maksimal pembatalan pengajuan peminjaman.
     * Waktu maksimal pembatalan harus kurang dari hari H dan sesuai selisih booking.
     */
    public function getBatasPembatalanAttribute(): Carbon
    {
        return Carbon::parse($this->tanggal_mulai)
            ->subDays($this->hari_maksimal_cancel)
            ->endOfDay();
    }

    /**
     * Cek apakah peminjaman saat ini dapat dibatalkan oleh pemohon.
     * Pemohon dapat membatalkan pengajuan kapan saja selama belum dibatalkan/ditolak
     * dan waktu pelaksanaan acara belum selesai.
     */
    public function canBeCancelled(): bool
    {
        // 1. Status tidak boleh sudah ditolak atau sudah dibatalkan
        if (in_array($this->status, ['rejected', 'cancelled'])) {
            return false;
        }

        // 2. Acara yang sudah selesai dilaksanakan tidak dapat dibatalkan
        if ($this->tanggal_selesai && now()->gt(Carbon::parse($this->tanggal_selesai)->endOfDay())) {
            return false;
        }

        return true;
    }

    /**
     * Cek apakah pembatalan pengajuan berhak mendapatkan pengembalian dana (refund).
     * Sesuai ketentuan: pembatalan dapat dilakukan kapan saja, namun jika pembatalan
     * dilakukan melebihi offset cancelation (H-offset), dana tidak akan dapat direfund.
     */
    public function isEligibleForRefund(): bool
    {
        return now()->lte($this->batas_pembatalan);
    }

    /**
     * Keterangan teks mengenai batas toleransi pengembalian dana (refund) pembatalan.
     */
    public function getKeteranganBatasPembatalanAttribute(): string
    {
        $hMin = $this->hari_maksimal_cancel;
        $tglBatas = $this->batas_pembatalan->translatedFormat('d M Y, H:i');

        if ($this->isEligibleForRefund()) {
            return "Pengembalian dana (refund) berlaku jika dibatalkan maksimal H-{$hMin} peminjaman (sampai {$tglBatas} WIB).";
        }

        return "Batas toleransi refund telah berakhir pada H-{$hMin} ({$tglBatas} WIB). Pembatalan tetap dapat diproses namun pembayaran tidak dapat direfund (hangus).";
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
                $q->whereNotIn('status', ['rejected', 'cancelled']);
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

    /**
     * Cek apakah peminjaman sudah memiliki pembayaran yang terverifikasi oleh admin.
     */
    public function hasVerifiedPayment(): bool
    {
        if (! $this->pembayaran) {
            return false;
        }

        return (float) $this->pembayaran->total_terbayar > 0
            || in_array($this->pembayaran->status_pembayaran, ['partial', 'lunas'])
            || $this->pembayaran->details()->where('status', 'verified')->exists();
    }
}
