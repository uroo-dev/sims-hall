<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Produk;
use Database\Seeders\Concerns\MenyalinAset;
use Illuminate\Database\Seeder;

/**
 * Dua produk unggulan siswa. Dokumentasi memakai aset lokal, bukan diunduh dari
 * jaringan, sehingga hasil seeding selalu sama.
 */
class ProdukSeeder extends Seeder
{
    use MenyalinAset;

    public function run(): void
    {
        $jurusan = Jurusan::pluck('jurusanID', 'nama');

        $produk = [
            [
                'nama' => 'Aplikasi Absensi Siswa',
                'kode_produk' => 'PU-001',
                'deskripsi' => 'Aplikasi web untuk mencatat kehadiran siswa.',
                'jurusan' => 'Rekayasa Perangkat Lunak',
                'dokumentasi' => 'assets/produk rpl.png',
            ],
            [
                'nama' => 'Mesin Penghitung Otomatis',
                'kode_produk' => 'PU-002',
                'deskripsi' => 'Mesin pembanding dimensi hasil pemesinan.',
                'jurusan' => 'Teknik Pemesinan',
                'dokumentasi' => 'assets/produk mesin.png',
            ],
        ];

        foreach ($produk as $data) {
            $namaJurusan = $data['jurusan'];
            unset($data['jurusan']);

            $data['jurusanID'] = $jurusan->get($namaJurusan);
            $data['dokumentasi'] = $this->salinKeStoragePublik($data['dokumentasi']);

            Produk::updateOrCreate(['kode_produk' => $data['kode_produk']], $data);
        }
    }
}
