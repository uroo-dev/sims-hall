<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'bank_utama',
    'norek_utama',
    'atas_nama_utama',
    'bank_alternatif_1',
    'norek_alternatif_1',
    'atas_nama_alternatif_1',
    'bank_alternatif_2',
    'norek_alternatif_2',
    'atas_nama_alternatif_2',
    'qris_image',
    'qris_merchant',
    'jatuh_tempo_dp_jam',
    'jatuh_tempo_pelunasan_jam',
    'minimal_hari_booking',
    'instruksi_pembayaran',
    'is_active',
])]
class PaymentConfiguration extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'payment_configurations';

    /**
     * Tipe casting atribut.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jatuh_tempo_dp_jam' => 'integer',
        'jatuh_tempo_pelunasan_jam' => 'integer',
        'minimal_hari_booking' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Dapatkan konfigurasi pembayaran aktif / pertama (Singleton pattern).
     */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'bank_utama' => 'Bank Jateng',
            'norek_utama' => '1023000012',
            'atas_nama_utama' => 'SMKN 2 KARANGANYAR',
            'bank_alternatif_1' => 'Bank BRI',
            'norek_alternatif_1' => '012301000001503',
            'atas_nama_alternatif_1' => 'SMK NEGERI 2 KARANGANYAR',
            'bank_alternatif_2' => null,
            'norek_alternatif_2' => null,
            'atas_nama_alternatif_2' => null,
            'qris_merchant' => 'SMKN 2 KRA AULA',
            'jatuh_tempo_dp_jam' => 24,
            'jatuh_tempo_pelunasan_jam' => 48,
            'minimal_hari_booking' => 3,
            'instruksi_pembayaran' => 'Silakan lakukan transfer sesuai nominal tagihan sebelum batas waktu jatuh tempo berakhir. Simpan bukti transfer untuk diunggah.',
            'is_active' => true,
        ]);
    }

    /**
     * URL publik untuk gambar QRIS.
     */
    public function getQrisUrlAttribute(): ?string
    {
        if (! $this->qris_image) {
            return null;
        }

        if (str_starts_with($this->qris_image, 'http://') || str_starts_with($this->qris_image, 'https://')) {
            return $this->qris_image;
        }

        return Storage::disk('public')->url($this->qris_image);
    }
}
