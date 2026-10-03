<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DataMasterSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SekolahSeeder::class,
            JurusanSeeder::class,
            GuruSeeder::class,
            SiswaSeeder::class,
            DudiSeeder::class,
            LowonganSeeder::class,
            SuratPengajuanSeeder::class,
            PenempatanPklSeeder::class,
            EkstrakurikulerSeeder::class,
            PrestasiSeeder::class,
            TataTertibSeeder::class,
            KategoriArtikelSeeder::class,
            ArtikelSeeder::class,
            ProdukSeeder::class,
            ProdukUnggulanSeeder::class,
            FasilitasSeeder::class,
            PaketPeminjamanSeeder::class,
            PaymentConfigurationSeeder::class,
            PpdbSeeder::class,
            AulaSeeder::class,
        ]);
    }
}
