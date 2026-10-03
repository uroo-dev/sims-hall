<?php

namespace App\Support;

/**
 * Master data kelas SMK Negeri 2 Karanganyar.
 *
 * Kelas disusun dari tiga bagian: tingkat, kode jurusan, dan rombel.
 * Tingkat yang dipakai adalah XII, kode jurusan diambil dari
 * {@see self::JURUSAN}, dan rombel memakai huruf A, B, atau C. Gabungan
 * ketiganya membentuk nama kelas, misalnya `XIIR A` untuk rombel A jurusan
 * Rekayasa Perangkat Lunak.
 *
 * Data ini adalah sumber tunggal nama kelas. Seeder siswa memakai daftar ini,
 * sehingga `siswas.kelas` selalu berisi nilai yang valid dan konsisten.
 */
final class Kelas
{
    public const TINGKAT = 'XII';

    /**
     * Kode jurusan -> nama jurusan. Kode ini adalah huruf yang muncul di nama
     * kelas dan harus sama persis dengan kolom `nama` pada tabel `jurusan`.
     *
     * @var array<string, string>
     */
    public const JURUSAN = [
        'R' => 'Rekayasa Perangkat Lunak',
        'T' => 'Teknik Pembuatan Kain',
        'O' => 'Teknik Ototronik',
        'M' => 'Teknik Pemesinan',
    ];

    /**
     * Rombel yang tersedia untuk setiap jurusan.
     *
     * @var list<string>
     */
    public const ROMBEL = ['A', 'B', 'C'];

    /**
     * Seluruh nama kelas yang valid, misal `['XIIR A', 'XIIR B', ...]`.
     *
     * @return list<string>
     */
    public static function nama(): array
    {
        $nama = [];

        foreach (array_keys(self::JURUSAN) as $kode) {
            foreach (self::ROMBEL as $rombel) {
                $nama[] = self::namaKelas($kode, $rombel);
            }
        }

        return $nama;
    }

    /**
     * Nama kelas dari kode jurusan dan rombel, misal `namaKelas('R', 'A')`
     * menghasilkan `XIIR A`.
     */
    public static function namaKelas(string $kodeJurusan, string $rombel): string
    {
        return self::TINGKAT.$kodeJurusan.' '.$rombel;
    }

    /**
     * Nama jurusan dari kode pada nama kelas. Mengembalikan null bila kelas
     * tidak memakai kode jurusan yang dikenal.
     */
    public static function jurusanDariKelas(string $namaKelas): ?string
    {
        foreach (self::JURUSAN as $kode => $jurusan) {
            if (str_starts_with($namaKelas, self::TINGKAT.$kode.' ')) {
                return $jurusan;
            }
        }

        return null;
    }
}
