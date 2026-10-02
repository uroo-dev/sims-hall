<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Produk;
use Database\Seeders\Concerns\MengunduhFotoTemplate;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    use MengunduhFotoTemplate;

    /**
     * Produk per jurusan, diambil dari resources/views/template/produk_unggulan.html.
     *
     * @var array<string, list<array<string, string>>>
     */
    protected array $produk = [
        'Teknik Pemesinan' => [
            [
                'nama' => 'Jemuran Pakaian Stainless Steel',
                'foto' => 'photo-1584622650111-993a426fbf0a',
                'deskripsi' => 'Jemuran pakaian berbahan stainless steel anti karat, kokoh, dan cocok untuk kebutuhan rumah tangga.',
            ],
            [
                'nama' => 'Rak Sepatu Besi Minimalis',
                'foto' => 'photo-1595428774223-ef52624120d2',
                'deskripsi' => 'Rak sepatu dari besi dengan desain minimalis, finishing rapi, dan kuat menahan beban.',
            ],
            [
                'nama' => 'Meja Kerja Bengkel',
                'foto' => 'photo-1518455027359-f3f8164ba6bd',
                'deskripsi' => 'Meja kerja bengkel dengan rangka besi kokoh untuk menopang peralatan dan pekerjaan berat.',
            ],
            [
                'nama' => 'Kursi Taman Besi',
                'foto' => 'photo-1503602642458-232111445657',
                'deskripsi' => 'Kursi taman berbahan besi dengan lapisan cat tahan cuaca untuk penggunaan luar ruangan.',
            ],
        ],

        'Teknik Ototronik' => [
            [
                'nama' => 'Servis & Perawatan Sistem Elektronik Otomotif',
                'foto' => 'photo-1486262715619-67b85e0b08d3',
                'deskripsi' => 'Layanan servis dan perawatan sistem kelistrikan serta elektronik kendaraan oleh siswa Ototronik.',
            ],
            [
                'nama' => 'Diagnosa Sistem Kelistrikan Kendaraan',
                'foto' => 'photo-1625047509168-a7026f36de04',
                'deskripsi' => 'Pemeriksaan dan diagnosa kerusakan sistem kelistrikan kendaraan menggunakan alat ukur standar industri.',
            ],
            [
                'nama' => 'Perawatan Sistem AC Kendaraan',
                'foto' => 'photo-1558618666-fcd25c85cd64',
                'deskripsi' => 'Pengecekan tekanan, pengisian refrigeran, dan perawatan komponen pendingin kabin kendaraan.',
            ],
            [
                'nama' => 'Pemasangan Aksesoris Elektronik Kendaraan',
                'foto' => 'photo-1580273916550-e323be2ae537',
                'deskripsi' => 'Instalasi aksesoris elektronik kendaraan seperti audio, lampu tambahan, dan sensor parkir.',
            ],
        ],

        'Rekayasa Perangkat Lunak' => [
            [
                'nama' => 'Website Company Profile',
                'foto' => 'photo-1460925895917-afdab827c52f',
                'deskripsi' => "Website untuk memperkenalkan perusahaan, brand, atau bisnis lokal.\n\n- Desain modern\n- Responsif di semua perangkat\n- Mudah dikelola",
            ],
            [
                'nama' => 'Aplikasi Kasir (Point of Sale)',
                'foto' => 'photo-1556742049-0cfed4f6a45d',
                'deskripsi' => "Aplikasi berbasis web/desktop untuk mengelola transaksi penjualan, stok barang, dan laporan keuangan.\n\n- Laporan real-time\n- Dashboard interaktif\n- Bisa diakses online dan offline",
            ],
            [
                'nama' => 'Sistem Informasi Sekolah',
                'foto' => 'photo-1504868584819-f8e8b4b6d7e3',
                'deskripsi' => "Platform untuk manajemen data siswa, guru, mata pelajaran, nilai, dan absensi.\n\n- Dashboard interaktif\n- Cetak rapor otomatis",
            ],
            [
                'nama' => 'Aplikasi Absensi QR Code',
                'foto' => 'photo-1595079676339-1534801ad6cf',
                'deskripsi' => "Aplikasi absensi modern menggunakan scan QR untuk mencatat kehadiran siswa.\n\n- Integrasi kamera\n- Riwayat absensi real-time",
            ],
        ],

        'Teknik Pembuatan Kain' => [
            [
                'nama' => 'Kain Batik Digital',
                'foto' => 'photo-1528459801416-a9e53bbf4e17',
                'deskripsi' => 'Kain batik dengan motif digital yang diproduksi menggunakan teknologi printing modern.',
            ],
            [
                'nama' => 'Kain Tenun Motif Lereng',
                'foto' => 'photo-1596462502278-27bfdc403348',
                'deskripsi' => 'Kain tenun tradisional dengan motif khas lereng Gunung Lawu hasil karya siswa.',
            ],
            [
                'nama' => 'Kemeja Seragam Sekolah',
                'foto' => 'photo-1602810318383-e386cc2a3ccf',
                'deskripsi' => 'Kemeja seragam sekolah dengan jahitan rapi dan bahan yang nyaman dipakai.',
            ],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $urutan = 0;

        foreach ($this->produk as $namaJurusan => $daftarProduk) {
            $jurusan = Jurusan::query()->firstWhere('nama', $namaJurusan);

            if (! $jurusan) {
                $this->command?->warn("Jurusan tidak ditemukan: {$namaJurusan}");

                continue;
            }

            foreach ($daftarProduk as $item) {
                $urutan++;

                Produk::query()->updateOrCreate(
                    ['kode_produk' => 'PU-'.str_pad((string) $urutan, 3, '0', STR_PAD_LEFT)],
                    [
                        'nama' => $item['nama'],
                        'deskripsi' => $item['deskripsi'],
                        'dokumentasi' => $this->unduhFoto($item['foto'], 'produk'),
                        'jurusanID' => $jurusan->jurusanID,
                    ],
                );
            }
        }
    }
}
