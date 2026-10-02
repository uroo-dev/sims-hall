<?php

namespace Database\Seeders;

use App\Models\Aula;
use App\Models\Dudi;
use App\Models\Ekstrakurikuler;
use App\Models\InformasiPpdb;
use App\Models\Jurusan;
use App\Models\PaketPeminjaman;
use App\Models\Ppdb;
use App\Models\Prestasi;
use App\Models\ProdukUnggulan;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DataMasterSeeder extends Seeder
{
    public function run(): void
    {
        $this->bersihkanTabelDataMaster();

        // ============================================
        // 1. DATA SEKOLAH
        // ============================================
        Sekolah::create([
            'judul' => 'Sejarah SMKN 2 KARANGANYAR',
            'dokumentasi' => 'Sejarah.jpg',
            'sejarah' => "Sekolah Menengah Kejuruan Negeri 2 Karanganyar atau yang sering disebut dengan SMK N 2 Karanganyar adalah sebuah Sekolah Menengah Kejuruan yang berlokasi di Jl. Laksda Yos Sudarso (Telp. (0271) 494549 Fax. (0271) 6498171 Bejan, Karanganyar 57716.\n\nSMK N 2 Karanganyar berdiri sejak tahun 1997 di area tanah seluas 27.720 m2 dan diresmikan pada tanggal 18 November 1997 oleh Menteri Pendidikan Nasional yaitu Prof. Dr. Ing. Wardiman Djojonegoro dengan satu program studi Teknik Mesin.\n\nSekolah ini pertamakali dipimpin oleh Kepala Sekolah Drs. Surip Sunarmo dari Tahun Pelajaran 1997/1998 hingga Tahun Pelajaran 2005/2006. Setelah itu dijabat oleh Bapak Kepala Sekolah pada Tahun Pelajaran 2004/2005 untuk membuka satu Program Studi Teknologi Tekstil. Pada Tahun Pelajaran 2006/2007, sekolah juga membuka satu Program Studi Keahlian Rekayasa Perangkat Lunak. Kemudian pada Tahun Pelajaran 2008/2009, SMK N 2 Karanganyar membuka satu Program Studi Keahlian Teknik Ototronik.",
            'profil_judul' => 'SMKN 2 KARANGANYAR',
            'profil_deskripsi' => 'Sebagai Sekolah Pusat Keunggulan, kami berkomitmen menghadirkan siswa berkualitas dengan standar industri. Kolaborasi dengan dunia industri menjadikan siswa lebih siap menghadapi tantangan kerja dan peluang masa depan.',
            'profil_dokumentasi' => 'dokumentasi-3d.png',
            'visi' => 'Terwujudnya Lulusan yang Berkarakter, Berprestasi, Berwawasan Global dan Berbudaya Lingkungan.',
            'misi' => "1. Menanamkan keimanan dan ketaqwaan kepada Tuhan Yang Maha Esa\n2. Mewujudkan profil lulusan yang kompetitif, kolaboratif dan berintegritas\n3. Menyelenggarakan Pendidikan dan Pelatihan yang Berkualitas dan Berbudaya Lingkungan",
            'sambutan_kepsek' => "Bismillahirrohmanirrohim\nAssalamualaikum Warahmatullahi Wabarakatuh\n\nAlhamdulillahhi robbil alamin kami panjatkan kehadirat Allah SWT, bahwasannya dengan rahmat dan karunia-Nya lah akhirnya Website sekolah ini dengan alamat www.smkn2karanganyar.sch.id dapat kami perbaharui. Kami mengucapkan selamat datang di Website kami Sekolah Menengah Kejuruan Negeri (SMKN) 2 Karanganyar yang saya tujukan untuk seluruh unsur pimpinan, guru, karyawan dan siswa serta khalayak umum guna dapat mengakses seluruh informasi tentang segala profil, aktifitas/kegiatan serta fasilitas sekolah kami.\n\nKami selaku pimpinan sekolah mengucapkan terima kasih kepada tim pembuat Website ini yang telah berusaha untuk dapat lebih memperkenalkan segala perihal yang dimiliki oleh sekolah. Dan tentunya Website sekolah kami masih terdapat banyak kekurangan, oleh karena itu kepada seluruh lapisan masyarakat dapat memberikan saran dan kritik yang membangun demi kemajuan Website ini lebih lanjut.\n\nTerima kasih sekian yang dapat kami sampaikan, apabila terdapat kekurangan dan kealahan, mohon dimaafkan.\n\nWassalamualaikum Warahmatullahi Wabarakatuh",
            'nama_kepsek' => 'Bapak Sukidi S. Pd., M. Pd.',
            'foto_kepsek' => 'foto kepsek.png',
            'yel_yel' => '"SMK Bisa, SMK Hebat, SMK Bisa Hebat, SMKN 2 Karanganyar PASTI BISA"',
        ]);

        // ============================================
        // 2. DATA JURUSAN
        // ============================================
        $jurusanData = [
            [
                'nama' => 'Teknik Pemesinan',
                'deskripsi' => 'Mempelajari tentang cara memproduksi barang teknik dan menggunakan mesin.',
                'logo' => 'logo_mesin.png',
            ],
            [
                'nama' => 'Teknik Pembuatan Kain',
                'deskripsi' => 'Mempelajari tentang desain tenun, mesin pembuatan kain, pemeliharaan dan perawatan, dan pengendalian mutunya.',
                'logo' => 'logo_tekstil.png',
            ],
            [
                'nama' => 'Teknik Ototronik',
                'deskripsi' => 'Mempelajari tentang otomotif dalam penguasaan teknologi elektronik dan kontrol pada kendaraan bermotor.',
                'logo' => 'logo_oto.png',
            ],
            [
                'nama' => 'Rekayasa Perangkat Lunak',
                'deskripsi' => 'Mempelajari tentang pengembangan perangkat lunak termasuk, pembuatan, pemeliharaan, dan manajemen organisasi.',
                'logo' => 'logo_rpl.png',
            ],
        ];
        foreach ($jurusanData as $j) {
            Jurusan::create($j);
        }

        // ============================================
        // 3. DATA EKSTRAKURIKULER
        // ============================================
        $eskulData = [
            ['nama' => 'OSIS', 'deskripsi' => 'Organisasi Siswa Intra Sekolah'],
            ['nama' => 'PMR', 'deskripsi' => 'Palang Merah Remaja'],
            ['nama' => 'PKS', 'deskripsi' => 'Patroli Keamanan Sekolah'],
            ['nama' => 'Pramuka', 'deskripsi' => 'Praja Muda Karana'],
            ['nama' => 'ROHIS', 'deskripsi' => 'Rohani Islam'],
            ['nama' => 'Jurnalistik', 'deskripsi' => 'Klub jurnalistik sekolah'],
            ['nama' => 'Futsal', 'deskripsi' => 'Tim futsal sekolah'],
            ['nama' => 'Basket', 'deskripsi' => 'Tim basket sekolah'],
            ['nama' => 'Coding Club', 'deskripsi' => 'Klub pemrograman dan teknologi'],
            ['nama' => 'Robotik', 'deskripsi' => 'Klub robotik dan otomasi'],
        ];
        foreach ($eskulData as $e) {
            Ekstrakurikuler::create($e);
        }

        // ============================================
        // 4. DATA PRESTASI
        // ============================================
        $prestasiData = [
            [
                'judul' => 'Juara 1 LKS Bidang CNC Milling 2026',
                'kategori' => 'akademik',
                'deskripsi' => 'Siswa SMKN 2 Karanganyar berhasil meraih Juara 1 Lomba Kompetensi Siswa (LKS) Bidang CNC Milling tingkat Provinsi Jawa Tengah tahun 2026.',
            ],
            [
                'judul' => 'Lolos SNBT UNS 2026 - Nofal Mita Hulhaq',
                'kategori' => 'akademik',
                'deskripsi' => 'Nofal Mita Hulhaq dari kelas 12 MA berhasil lolos SNBT dan diterima di Program Studi Teknik Mesin Universitas Sebelas Maret (UNS).',
            ],
            [
                'judul' => 'Lolos SNBT UNS 2026 - Raras Putri Febriana',
                'kategori' => 'akademik',
                'deskripsi' => 'Raras Putri Febriana dari kelas 12 RB berhasil lolos SNBT dan diterima di Program Studi PTIK Universitas Sebelas Maret (UNS).',
            ],
            [
                'judul' => 'Lolos SNBT UNS 2026 - Davin Wahyu Amanta',
                'kategori' => 'akademik',
                'deskripsi' => 'Davin Wahyu Amanta dari kelas 12 RB berhasil lolos SNBT dan diterima di Program Studi PTIK Universitas Sebelas Maret (UNS).',
            ],
            [
                'judul' => 'Lolos SNBT UNS 2026 - M. Faiz Bayu Nur A.',
                'kategori' => 'akademik',
                'deskripsi' => 'M. Faiz Bayu Nur A. dari kelas 12 RA berhasil lolos SNBT dan diterima di Program Studi Manajemen Universitas Sebelas Maret (UNS).',
            ],
            [
                'judul' => 'Juara 2 LKBB-PB Tingkat Provinsi Jawa Tengah',
                'kategori' => 'non-akademik',
                'deskripsi' => 'Tim Paskibra SMKN 2 Karanganyar meraih Juara 2 Lomba Keterampilan Baris-Berbaris Pengibar Bendera tingkat Provinsi Jawa Tengah.',
            ],
            [
                'judul' => 'Juara 1 Futsal Pelajar Kabupaten Karanganyar 2026',
                'kategori' => 'non-akademik',
                'deskripsi' => 'Tim Futsal SMKN 2 Karanganyar berhasil meraih Juara 1 pada turnamen futsal pelajar tingkat Kabupaten Karanganyar.',
            ],
        ];
        foreach ($prestasiData as $p) {
            Prestasi::create($p);
        }

        // ============================================
        // 5. DATA DUDI (MITRA INDUSTRI)
        // ============================================
        // BUKAN truncate() seperti tabel lain di seeder ini. Tabel `dudis`
        // dipakai bersama modul PKL & BKK (surat pengajuan, lowongan,
        // penempatan), jadi membersihkannya di sini akan menghapus data
        // BkkSeeder. Dipakai updateOrCreate berdasarkan nama supaya seeder
        // tetap idempoten.
        //
        // Kolom wajib (nama_dudi, alamat, kota, bidang_usaha) ikut diisi karena
        // tabel final memakai skema modul PKL & BKK, bukan skema display-only.
        $dudiData = [
            ['nama' => 'PT EXP', 'program_1' => 'Teknik Pemesinan', 'program_2' => 'Teknik Ototronik', 'deskripsi' => 'Mitra industri di bidang manufaktur presisi.', 'logo' => 'EXP.png'],
            ['nama' => 'MSM Solo', 'program_1' => 'Teknik Pemesinan', 'program_2' => 'Teknik Ototronik', 'deskripsi' => 'Mitra industri otomotif dan mesin.', 'logo' => 'msm.png'],
            ['nama' => 'Nasmoco', 'program_1' => 'Teknik Ototronik', 'deskripsi' => 'Dealer resmi Toyota di wilayah Solo Raya.', 'logo' => 'Nasmoco.png'],
            ['nama' => 'Toyota', 'program_1' => 'Teknik Ototronik', 'deskripsi' => 'Mitra industri otomotif global.', 'logo' => 'Toyota.png'],
            ['nama' => 'PT Yichad Textile Indonesia', 'program_1' => 'Teknik Pembuatan Kain', 'deskripsi' => 'Mitra industri tekstil dan garmen.', 'logo' => 'textil.png'],
        ];
        foreach ($dudiData as $d) {
            Dudi::updateOrCreate(
                ['nama_dudi' => $d['nama']],
                [
                    'alamat' => $d['deskripsi'],
                    'kota' => 'Karanganyar',
                    'bidang_usaha' => implode(' / ', array_filter([$d['program_1'] ?? null, $d['program_2'] ?? null, $d['program_3'] ?? null])),
                    'is_mitra_resmi' => true,
                    'tampil_di_landing' => true,
                    'program_1' => $d['program_1'] ?? null,
                    'program_2' => $d['program_2'] ?? null,
                    'program_3' => $d['program_3'] ?? null,
                    'deskripsi' => $d['deskripsi'],
                    'logo' => $d['logo'],
                ]
            );
        }

        // ============================================
        // 6. DATA PRODUK UNGGULAN
        // ============================================
        $produkData = [
            [
                'judul' => 'Teknik Permesinan',
                'deskripsi' => 'DIPROSES MENGGUNAKAN MESIN MODERN YANG MENGHASILKAN PRODUK DENGAN KUALITAS TINGGI, PRESISI, DAN HASIL YANG KONSISTEN.',
                'dokumentasi' => 'produk mesin.png',
            ],
            [
                'judul' => 'Teknik Pembuatan Kain',
                'deskripsi' => 'DARI KAIN BATIK, TENUN, HINGGA KAIN ECOPRINT SEMUA DIPRODUKSI OLEH SISWA JURUSAN TEKSTIL.',
                'dokumentasi' => 'produk tpk.png',
            ],
            [
                'judul' => 'Rekayasa Perangkat Lunak',
                'deskripsi' => 'DARI COMPANY PROFILE, E-COMMERCE, HINGGA APLIKASI ONLINE',
                'dokumentasi' => 'produk rpl.png',
            ],
            [
                'judul' => 'Teknik Ototronik',
                'deskripsi' => 'TEKNOLOGI OTOTRONIK MODERN DALAM PERAWATAN DAN PERBAIKAN KENDARAAN UNTUK MENGHASILKAN PERFORMA YANG OPTIMAL DAN BERKUALITAS.',
                'dokumentasi' => 'produk oto.png',
            ],
        ];
        foreach ($produkData as $pr) {
            ProdukUnggulan::create($pr);
        }

        // ============================================
        // 7. DATA AULA
        // ============================================
        Aula::create([
            'nama' => 'Aula Utama SMKN 2 Karanganyar',
            'judul' => 'Aula Serbaguna',
            'deskripsi' => 'Aula utama dengan kapasitas besar untuk berbagai acara.',
            'dokumentasi' => 'aula-1.jpg',
        ]);

        // ============================================
        // 9. DATA PPDB
        // ============================================
        $ppdb = Ppdb::create([
            'judul' => 'INFORMASI PPDB SMKN 2 KARANGANYAR',
            'deskripsi' => 'Calon Murid Baru yang akan mengikuti PPDB Tahun 2026 diharapkan menyiapkan seluruh dokumen persyaratan sebelum melakukan pengajuan akun. Kelengkapan berkas yang diunggah akan memperlancar proses verifikasi data dan menghindari kendala saat pendaftaran.',
            'persyaratan' => 'Fotokopi KK, Ijazah, SKHUN, Pas Foto 3x4',
            'dokumentasi' => 'ppdb.png',
            'tahun_ajaran' => '2026/2027',
        ]);

        // ============================================
        // 10. DATA DAYA TAMPUNG (INFORMASI PPDB)
        // ============================================
        $dayaTampungData = [
            ['ppdb_id' => $ppdb->id, 'nama_agenda' => 'TEKNIK PERMESINAN', 'daya_tampung' => '108', 'keterangan' => 'Tersedia 3 rombel'],
            ['ppdb_id' => $ppdb->id, 'nama_agenda' => 'TEKNIK PEMBUATAN KAIN', 'daya_tampung' => '108', 'keterangan' => 'Tersedia 3 rombel'],
            ['ppdb_id' => $ppdb->id, 'nama_agenda' => 'TEKNIK OTOTRONIK', 'daya_tampung' => '108', 'keterangan' => 'Tersedia 3 rombel'],
            ['ppdb_id' => $ppdb->id, 'nama_agenda' => 'REKAYASA PERANGKAT LUNAK', 'daya_tampung' => '108', 'keterangan' => 'Tersedia 3 rombel'],
        ];
        foreach ($dayaTampungData as $dt) {
            InformasiPpdb::create($dt);
        }

        // ============================================
        // 11. DATA USERS (Default Admin)
        // ============================================
        // Hapus dulu semua user kecuali yang mau tetap ada, lalu buat default user
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['username' => 'root'],
            [
                'name' => 'Super Admin',
                'email' => 'super@gmail.com',
                'role' => 'super_admin',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['username' => 'uroo'],
            [
                'name' => 'Admin BKK & PKL',
                'email' => 'pklbkk@smkn2kra.sch.id',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['username' => 'dafin'],
            [
                'name' => 'Admin Data Master Sekolah',
                'email' => 'datamastersekolah@smkn2kra.sch.id',
                'role' => 'admin_master',
                'password' => Hash::make('password'),
            ]
        );

        $this->command->info('✅ Data Master berhasil di-seed!');
    }

    /**
     * Mengosongkan seluruh tabel Data Master Sekolah.
     *
     * Foreign key checks dimatikan sementara karena `produk_unggulans` dan
     * `informasi_ppdbs` mereferensikan tabel lain (`jurusans` dan `ppdbs`).
     * MySQL menolak `TRUNCATE` pada tabel yang menjadi target constraint
     * ("Cannot truncate a table referenced in a foreign key constraint"),
     * sedangkan SQLite tidak. Dulu setiap tabel di-truncate satu per satu di
     * dalam bloknya masing-masing, sehingga urutan penghapusan ikut menentukan
     * berhasil atau tidak. Sekarang semua pembersihan dipusatkan di sini dan
     * tidak lagi bergantung pada urutan.
     *
     * Tabel `dudis` SENGAJA tidak disentuh: tabel itu milik modul PKL & BKK
     * dan datanya diisi lewat `updateOrCreate` di bawah, bukan di-truncate.
     */
    private function bersihkanTabelDataMaster(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            foreach ([
                InformasiPpdb::class,
                ProdukUnggulan::class,
                Sekolah::class,
                Jurusan::class,
                Ekstrakurikuler::class,
                Prestasi::class,
                Aula::class,
                PaketPeminjaman::class,
                Ppdb::class,
            ] as $model) {
                $model::truncate();
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }
}
