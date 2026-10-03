<?php

namespace Database\Seeders;

use App\Models\PaymentConfiguration;
use Illuminate\Database\Seeder;

class PaymentConfigurationSeeder extends Seeder
{
    public function run(): void
    {
        PaymentConfiguration::updateOrCreate([], [
            'bank_utama' => 'Bank BRI',
            'norek_utama' => '0123456789',
            'atas_nama_utama' => 'SMKN 2 Karanganyar',
            'bank_alternatif_1' => 'Bank Mandiri',
            'norek_alternatif_1' => '9876543210',
            'atas_nama_alternatif_1' => 'SMKN 2 Karanganyar',
            'bank_alternatif_2' => 'Bank BNI',
            'norek_alternatif_2' => '1122334455',
            'atas_nama_alternatif_2' => 'SMKN 2 Karanganyar',
            'jatuh_tempo_dp_jam' => 24,
            'jatuh_tempo_pelunasan_jam' => 48,
            'instruksi_pembayaran' => 'Transfer lalu unggah bukti pembayaran.',
            'is_active' => true,
            'minimal_hari_booking' => 3,
            'offset_hari_pembatalan' => 1,
        ]);
    }
}
