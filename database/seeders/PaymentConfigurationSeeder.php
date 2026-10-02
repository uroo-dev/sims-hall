<?php

namespace Database\Seeders;

use App\Models\PaymentConfiguration;
use Illuminate\Database\Seeder;

class PaymentConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentConfiguration::firstOrCreate([], [
            'bank_utama' => 'Bank Jateng',
            'norek_utama' => '1023000012',
            'atas_nama_utama' => 'SMKN 2 KARANGANYAR',
            'bank_alternatif_1' => 'Bank BRI',
            'norek_alternatif_1' => '012301000001503',
            'atas_nama_alternatif_1' => 'SMK NEGERI 2 KARANGANYAR',
            'bank_alternatif_2' => 'Bank BNI',
            'norek_alternatif_2' => '9876543210',
            'atas_nama_alternatif_2' => 'SMKN 2 KARANGANYAR',
            'qris_merchant' => 'SMKN 2 KRA AULA',
            'jatuh_tempo_dp_jam' => 24,
            'jatuh_tempo_pelunasan_jam' => 48,
            'minimal_hari_booking' => 3,
            'instruksi_pembayaran' => 'Silakan lakukan transfer sesuai nominal tagihan sebelum batas waktu jatuh tempo berakhir. Simpan bukti transfer untuk diunggah pada sistem.',
            'is_active' => true,
        ]);
    }
}
