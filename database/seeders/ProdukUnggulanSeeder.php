<?php

namespace Database\Seeders;

use App\Models\ProdukUnggulan;
use Database\Seeders\Concerns\MenyalinAset;
use Illuminate\Database\Seeder;

/**
 * Baris tunggal untuk pengaturan halaman Produk Unggulan. Dokumentasi disimpan
 * sebagai daftar path dipisahkan koma pada disk `public`.
 */
class ProdukUnggulanSeeder extends Seeder
{
    use MenyalinAset;

    public function run(): void
    {
        $dokumentasi = collect([
            'assets/produk rpl.png',
            'assets/produk mesin.png',
        ])
            ->map(fn (string $sumber) => $this->salinKeStoragePublik($sumber))
            ->filter()
            ->implode(',');

        ProdukUnggulan::query()->delete();
        ProdukUnggulan::create([
            'judul' => 'Produk Unggulan',
            'deskripsi' => 'Karya terbaik dari siswa SMKN 2 Karanganyar.',
            'dokumentasi' => $dokumentasi,
        ]);
    }
}
