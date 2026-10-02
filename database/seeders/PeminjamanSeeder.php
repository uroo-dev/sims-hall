<?php

namespace Database\Seeders;

use App\Models\DetailPembayaran;
use App\Models\Facility;
use App\Models\PaketPeminjaman;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Models\Persetujuan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat User Contoh sesuai screenshot (Ilham / Organisasi)
        $userCustomer = User::firstOrCreate(
            ['username' => 'ilham'],
            [
                'name' => 'Ilham',
                'email' => 'PBB@smk2nkra.sch.id',
                'password' => 'password',
                'role' => 'user',
            ]
        );

        $adminUser = User::firstOrCreate(
            ['username' => 'admin_sarpras'],
            [
                'name' => 'Admin Sarpras',
                'email' => 'sarpras@smk2nkra.sch.id',
                'password' => 'password',
                'role' => 'admin',
            ]
        );

        $kepalaSekolah = User::firstOrCreate(
            ['username' => 'kepsek'],
            [
                'name' => 'Kepala Sekolah',
                'email' => 'kepsek@smk2nkra.sch.id',
                'password' => 'password',
                'role' => 'kepala_sekolah',
            ]
        );

        // 2. Buat Daftar Fasilitas Aula
        $facilities = [
            'Sound System Standar' => 'Sound system standar 2 speaker aktif',
            'Sound System Medium' => 'Sound system profesional dengan subwoofer',
            'Mic 2' => '2 buah wireless microphone UHF',
            'Mic 4' => '4 buah wireless microphone UHF + clip on',
            '100 Kursi' => '100 unit kursi susun',
            '500 Kursi + Cover' => '500 unit kursi susun berbalut cover kain putih rapi',
            'Proyektor 1' => '1 unit proyektor 3500 ANSI Lumens + screen',
            'Proyektor 2' => '2 unit proyektor laser high resolution + wide screen',
            'Wifi 1080mbps' => 'Akses internet high speed fiber dedicated',
            'Podium' => 'Podium kayu jati eksklusif dengan mikrofon gooseneck',
            'Parkir Luas (Gratis)' => 'Area parkir beraspal kapasitas 50 mobil & 200 motor',
            'Staff Keamanan' => 'Petugas keamanan & kebersihan standby selama acara',
        ];

        $facilityModels = [];
        foreach ($facilities as $judul => $deskripsi) {
            $facilityModels[$judul] = Facility::firstOrCreate(
                ['judul' => $judul],
                ['deskripsi' => $deskripsi]
            );
        }

        // 3. Buat 5 Paket Peminjaman sesuai screenshot
        $paketData = [
            [
                'kategori' => 'unggulan',
                'nama_paket' => 'Unggulan',
                'harga' => 6000000,
                'deskripsi' => 'Paket terlengkap aula untuk resepsi, wisuda, atau gathering instansi skala besar hingga 12 jam.',
                'durasi' => '12 Jam',
                'fasilitas' => [
                    'Sound System Medium', 'Mic 4', '500 Kursi + Cover', 'Proyektor 2',
                    'Wifi 1080mbps', 'Podium', 'Parkir Luas (Gratis)', 'Staff Keamanan',
                ],
            ],
            [
                'kategori' => 'terjangkau',
                'nama_paket' => 'Terjangkau',
                'harga' => 1500000,
                'deskripsi' => 'Paket hemat untuk seminar singkat, rapat pleno, atau workshop berdurasi 4 jam.',
                'durasi' => '4 Jam',
                'fasilitas' => [
                    'Sound System Standar', 'Mic 2', '100 Kursi', 'Proyektor 1',
                ],
            ],
            [
                'kategori' => 'standar 1',
                'nama_paket' => 'Standar 1',
                'harga' => 3500000,
                'deskripsi' => 'Pilihan ideal untuk kegiatan seminar umum, pentas seni sekolah, dan pelatihan.',
                'durasi' => '12 Jam',
                'fasilitas' => [
                    'Sound System Medium', 'Mic 4', '500 Kursi + Cover', 'Proyektor 2',
                ],
            ],
            [
                'kategori' => 'standar 2',
                'nama_paket' => 'Standar 2',
                'harga' => 4400000,
                'deskripsi' => 'Fasilitas medium dengan kapasitas kursi penuh dan dukungan audiovisual prima.',
                'durasi' => '12 Jam',
                'fasilitas' => [
                    'Sound System Medium', 'Mic 4', '500 Kursi + Cover', 'Proyektor 2',
                ],
            ],
            [
                'kategori' => 'standar 3',
                'nama_paket' => 'Standar 3',
                'harga' => 5500000,
                'deskripsi' => 'Fasilitas premium dengan kenyamanan maksimal untuk berbagai perhelatan formal.',
                'durasi' => '12 Jam',
                'fasilitas' => [
                    'Sound System Medium', 'Mic 4', '500 Kursi + Cover', 'Proyektor 2',
                ],
            ],
        ];

        $paketModels = [];
        foreach ($paketData as $item) {
            $paket = PaketPeminjaman::firstOrCreate(
                ['kategori' => $item['kategori']],
                [
                    'nama_paket' => $item['nama_paket'],
                    'harga' => $item['harga'],
                    'deskripsi' => $item['deskripsi'],
                ]
            );
            $paketModels[$item['kategori']] = $paket;

            // Sync fasilitas
            $ids = [];
            foreach ($item['fasilitas'] as $fJudul) {
                if (isset($facilityModels[$fJudul])) {
                    $ids[] = $facilityModels[$fJudul]->id;
                }
            }
            $paket->facilities()->sync($ids);
        }

        // 4. Buat Contoh Peminjaman untuk Kalender & Riwayat User Ilham
        $peminjaman1 = Peminjaman::firstOrCreate(
            [
                'nama' => 'Ilham',
                'email_instansi' => 'PBB@smk2nkra.sch.id',
                'tanggal_mulai' => now()->startOfMonth()->addDays(24)->setTime(8, 0),
            ],
            [
                'paket_peminjaman_id' => $paketModels['standar 2']->id,
                'tanggal_selesai' => now()->startOfMonth()->addDays(24)->setTime(20, 0),
                'catatan' => 'Peminjaman aula untuk kegiatan perayaan tahunan organisasi',
                'status' => 'approved_final',
            ]
        );

        // Approval Tahap 1 & 2
        Persetujuan::firstOrCreate(
            ['peminjaman_id' => $peminjaman1->id, 'level' => 'admin'],
            [
                'approver_id' => $adminUser->id,
                'status' => 'approved',
                'catatan_approval' => 'Berkas dan surat pengantar lengkap serta terverifikasi.',
                'tanggal_proses' => now()->subDays(2),
            ]
        );

        Persetujuan::firstOrCreate(
            ['peminjaman_id' => $peminjaman1->id, 'level' => 'pimpinan'],
            [
                'approver_id' => $kepalaSekolah->id,
                'status' => 'approved',
                'catatan_approval' => 'Disetujui untuk pemakaian aula sekolah.',
                'tanggal_proses' => now()->subDay(),
            ]
        );

        // Tagihan Pembayaran & Detail Lunas
        $pembayaran1 = Pembayaran::firstOrCreate(
            ['peminjaman_id' => $peminjaman1->id],
            [
                'kode_pembayaran' => 'ORD-002',
                'total_tagihan' => $paketModels['standar 2']->harga,
                'total_terbayar' => $paketModels['standar 2']->harga,
                'sisa_tagihan' => 0,
                'status_pembayaran' => 'lunas',
                'jatuh_tempo_pelunasan' => now()->addDays(5),
                'catatan' => 'Pembayaran lunas via transfer bank.',
            ]
        );

        DetailPembayaran::firstOrCreate(
            ['pembayaran_id' => $pembayaran1->id, 'kode_transaksi' => 'TRX-002-LNS'],
            [
                'tipe_pembayaran' => 'lunas_langsung',
                'jumlah_bayar' => $paketModels['standar 2']->harga,
                'metode' => 'transfer',
                'bank_tujuan' => 'Bank Jateng',
                'norek_tujuan' => '1029384756',
                'bank_pengirim' => 'BCA',
                'norek_pengirim' => '9876543210',
                'atas_nama_pengirim' => 'Ilham Organisasi',
                'tanggal_bayar' => now()->subDay(),
                'status' => 'verified',
                'diverifikasi_oleh' => $adminUser->id,
                'diverifikasi_pada' => now()->subDay(),
                'catatan' => 'Dana telah masuk di rekening sekolah.',
            ]
        );

        // Tambah peminjaman kedua (sebagai agenda lain di kalender ketersediaan)
        Peminjaman::firstOrCreate(
            [
                'nama' => 'SMK Bina Karya',
                'email_instansi' => 'info@smkbinakarya.sch.id',
                'tanggal_mulai' => now()->startOfMonth()->addDays(11)->setTime(7, 30),
            ],
            [
                'paket_peminjaman_id' => $paketModels['unggulan']->id,
                'tanggal_selesai' => now()->startOfMonth()->addDays(11)->setTime(18, 0),
                'catatan' => 'Acara Wisuda Gabungan',
                'status' => 'approved_final',
            ]
        );
    }
}
