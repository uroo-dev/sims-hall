<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusanIds = Jurusan::query()->pluck('jurusanID')->all();

        $produk = [
            ['nama' => 'Aplikasi Sistem Informasi Sekolah', 'deskripsi' => 'Aplikasi berbasis web untuk mengelola data siswa, guru, dan jadwal pelajaran.'],
            ['nama' => 'Robot Line Follower', 'deskripsi' => 'Robot otomatis yang mampu mengikuti garis hitam di lantai menggunakan sensor.'],
            ['nama' => 'Mesin CNC Mini', 'deskripsi' => 'Mesin bubut CNC skala kecil untuk presisi komponen logam.'],
            ['nama' => 'Kain Batik Digital', 'deskripsi' => 'Kain batik dengan motif digital yang diproduksi menggunakan teknologi modern.'],
            ['nama' => 'Aplikasi E-Commerce UMKM', 'deskripsi' => 'Platform jual beli online untuk usaha mikro kecil menengah lokal.'],
            ['nama' => 'Drone Pemantau Pertanian', 'deskripsi' => 'Drone untuk memantau kesehatan tanaman dan luas lahan pertanian.'],
            ['nama' => 'Mesin Presisi Aluminium', 'deskripsi' => 'Hasil pemesinan aluminium dengan toleransi tinggi untuk industri otomotif.'],
            ['nama' => 'Kemeja Seragam Sekolah', 'deskripsi' => 'Kemeja seragam dengan jahitan rapi dan bahan nyaman.'],
            ['nama' => 'Aplikasi Manajemen Inventaris', 'deskripsi' => 'Sistem pencatatan stok barang dan peminjaman aset sekolah.'],
            ['nama' => 'Smart Home Berbasis IoT', 'deskripsi' => 'Sistem otomasi rumah menggunakan sensor dan kendali smartphone.'],
            ['nama' => 'Komponen Mesin Bubut', 'deskripsi' => 'Komponen hasil bubut presisi untuk kebutuhan industri manufaktur.'],
            ['nama' => 'Kain Tenun Motif Lereng', 'deskripsi' => 'Kain tenun tradisional dengan motif khas lereng Gunung Lawu.'],
            ['nama' => 'Aplikasi PPDB Online', 'deskripsi' => 'Sistem pendaftaran peserta didik baru secara daring.'],
            ['nama' => 'Robot Pemadam Api', 'deskripsi' => 'Robot kecil yang mampu mendeteksi dan memadamkan api secara otomatis.'],
            ['nama' => 'Produk Olahan Hasil Pertanian', 'deskripsi' => 'Olahan makanan ringan dari bahan baku lokal berkualitas.'],
        ];

        foreach ($produk as $index => $item) {
            Produk::query()->create([
                'kode_produk' => 'PU-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                'nama' => $item['nama'],
                'deskripsi' => $item['deskripsi'],
                'jurusanID' => $jurusanIds[array_rand($jurusanIds)] ?? null,
            ]);
        }
    }
}
