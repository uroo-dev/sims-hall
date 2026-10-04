<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TartibSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tataTertib = [
            // 1. PAKAIAN SERAGAM
            [
                'judul' => 'PAKAIAN SERAGAM',
                'deskripsi' => '1.1. Tidak mengenakan seragam sekolah yang telah ditentukan (pakaian, atribut, sepatu, dasi, kaos kaki, sabuk, topi, tali sepatu hitam/putih)'
            ],
            [
                'judul' => 'PAKAIAN SERAGAM',
                'deskripsi' => '1.2. Tidak mengenakan pakaian praktik maupun olahraga yang telah ditentukan'
            ],

            // 2. RAMBUT, KUKU, TATO, MAKE-UP
            [
                'judul' => 'RAMBUT, KUKU, TATO, MAKE-UP',
                'deskripsi' => '2.1. Rambut tidak sesuai ketentuan sekolah 1, 2'
            ],
            [
                'judul' => 'RAMBUT, KUKU, TATO, MAKE-UP',
                'deskripsi' => '2.2. Memakai aksesori yang tidak mencerminkan pribadi siswa (memakai gelang/kalung/anting-anting/cincin,yang berlebihan)'
            ],
            [
                'judul' => 'RAMBUT, KUKU, TATO, MAKE-UP',
                'deskripsi' => '2.3. Mengecat rambut'
            ],
            [
                'judul' => 'RAMBUT, KUKU, TATO, MAKE-UP',
                'deskripsi' => '2.4. Bertato'
            ],
            [
                'judul' => 'RAMBUT, KUKU, TATO, MAKE-UP',
                'deskripsi' => '2.5. Memakai make up/dandan berlebihan'
            ],
            [
                'judul' => 'RAMBUT, KUKU, TATO, MAKE-UP',
                'deskripsi' => '2.6. Berkuku panjang, mengecat kuku'
            ],

            // 3. MASUK DAN PULANG SAAT DI SEKOLAH MAUPUN SAAT MAGANG/PKL
            [
                'judul' => 'MASUK DAN PULANG SAAT DI SEKOLAH MAUPUN SAAT MAGANG/PKL',
                'deskripsi' => '3.1. Datang terlambat tanpa alasan yang bisa dipertanggungjawabkan'
            ],
            [
                'judul' => 'MASUK DAN PULANG SAAT DI SEKOLAH MAUPUN SAAT MAGANG/PKL',
                'deskripsi' => '3.2. Tidak masuk tanpa keterangan/dinyatakan alpha'
            ],
            [
                'judul' => 'MASUK DAN PULANG SAAT DI SEKOLAH MAUPUN SAAT MAGANG/PKL',
                'deskripsi' => '3.3. Tidak masuk dengan membuat surat keterangan palsu'
            ],
            [
                'judul' => 'MASUK DAN PULANG SAAT DI SEKOLAH MAUPUN SAAT MAGANG/PKL',
                'deskripsi' => '3.4. Meninggalkan pelajaran tertentu tanpa izin (bolos sekolah) / Berada di luar lingkungan sekolah/ tanpa izin pada saat jam pelajaran'
            ],

            // 4. KEBERSIHAN, KEDISIPLINAN, KETERTIBAN, KEHADIRAN
            [
                'judul' => 'KEBERSIHAN, KEDISIPLINAN, KETERTIBAN, KEHADIRAN',
                'deskripsi' => '4.1. Tidak melaksanakan piket kebersihan, ketertiban dan keindahan kelas'
            ],
            [
                'judul' => 'KEBERSIHAN, KEDISIPLINAN, KETERTIBAN, KEHADIRAN',
                'deskripsi' => '4.2. Makan/minum di dalam kelas saat pelajaran berlangsung tanpa seizin guru'
            ],
            [
                'judul' => 'KEBERSIHAN, KEDISIPLINAN, KETERTIBAN, KEHADIRAN',
                'deskripsi' => '4.3. Membuang sampah tidak pada tempatnya'
            ],
            [
                'judul' => 'KEBERSIHAN, KEDISIPLINAN, KETERTIBAN, KEHADIRAN',
                'deskripsi' => '4.4. Mencuri di lingkungan sekolah atau diluar lingkungan sekolah'
            ],
            [
                'judul' => 'KEBERSIHAN, KEDISIPLINAN, KETERTIBAN, KEHADIRAN',
                'deskripsi' => '4.5. Menggelapkan, memanipulasi, menyalah gunakan uang kas kelas/organisasi'
            ],
            [
                'judul' => 'KEBERSIHAN, KEDISIPLINAN, KETERTIBAN, KEHADIRAN',
                'deskripsi' => '4.6. Memalak teman atau siswa lain'
            ],
            [
                'judul' => 'KEBERSIHAN, KEDISIPLINAN, KETERTIBAN, KEHADIRAN',
                'deskripsi' => '4.7. Mengikuti organisasi terlarang (ajaran sesat)'
            ],
            [
                'judul' => 'KEBERSIHAN, KEDISIPLINAN, KETERTIBAN, KEHADIRAN',
                'deskripsi' => '4.8. Membuat kegaduhan/keributan selama proses belajar mengajar'
            ],

            // 5. SOPAN SANTUN/PERGAULAN
            [
                'judul' => 'SOPAN SANTUN/PERGAULAN',
                'deskripsi' => '5.2. Terbukti memfitnah atau mencemarkan nama baik warga sekolah'
            ],
            [
                'judul' => 'SOPAN SANTUN/PERGAULAN',
                'deskripsi' => '5.3. Bertingkah laku tidak sopan, melecehkan Kepala sekolah Guru, Staf Tata Usaha, dan Siswa'
            ],
            [
                'judul' => 'SOPAN SANTUN/PERGAULAN',
                'deskripsi' => '5.4. Berkata kasar/tidak sopan, kepada Kepala Sekolah, Guru, Staf Tata Usaha'
            ],
            [
                'judul' => 'SOPAN SANTUN/PERGAULAN',
                'deskripsi' => '5.5. Memalsukan tanda tangan Kepala Sekolah, Guru, Staf Tata Usaha'
            ],
            [
                'judul' => 'SOPAN SANTUN/PERGAULAN',
                'deskripsi' => '5.6. Memalsukan tanda tangan orangtua'
            ],

            // 6. UPACARA BENDERA DAN PERINGATAN HARI BESAR
            [
                'judul' => 'UPACARA BENDERA DAN PERINGATAN HARI BESAR',
                'deskripsi' => '6.1. Tidak mengikuti upacara Bendera (hari Senin) sesuai ketentuan sekolah'
            ],
            [
                'judul' => 'UPACARA BENDERA DAN PERINGATAN HARI BESAR',
                'deskripsi' => '6.2. Tidak mengikuti upacara hari besar NASIONAL (Hari Kemerdekaan, Hardiknas, Hari Pahlawan,Hari Sumpah Pemuda dll)'
            ],

            // 7. KEGIATAN KEAGAMAAN
            [
                'judul' => 'KEGIATAN KEAGAMAAN',
                'deskripsi' => '7.1. Mempermainkan, melecehkan agama, baik agama sendiri/orang lain'
            ],
            [
                'judul' => 'KEGIATAN KEAGAMAAN',
                'deskripsi' => '7.2. Tidak menjalankan sholat dhuhur, ashar maupun sholat Jum\'at di sekolah(bagi siswa muslim)'
            ],
            [
                'judul' => 'KEGIATAN KEAGAMAAN',
                'deskripsi' => '7.3. Tidak mengikuti pengajian dan pesantren Ramadhan yang diadakan oleh sekolah(bagi siswa muslim) / Bagi siswa muslim tidak mengikuti kegiatan keagamaan yang diatur oleh Wali kelas, Guru BK, Waka Kesiswaan / Bagi siswa muslim tidak mengikuti Sholat Berjamaah di masjid sekolah'
            ],

            // 8. LARANGAN - LARANGAN
            [
                'judul' => 'LARANGAN - LARANGAN',
                'deskripsi' => '8.1. Membawa rokok, merokok di sekolah / lingkungan sekolah'
            ],
            [
                'judul' => 'LARANGAN - LARANGAN',
                'deskripsi' => '8.2. Membawa /meminum minuman keras/ Sajam'
            ],
            [
                'judul' => 'LARANGAN - LARANGAN',
                'deskripsi' => '8.3. Mengedarkan dan mengkonsumsi narkotika, psikotropika atau obat terlarang lainnya'
            ],
            [
                'judul' => 'LARANGAN - LARANGAN',
                'deskripsi' => '8.4. Berperilaku tidak senonoh di lingkungan sekolah'
            ],
            [
                'judul' => 'LARANGAN - LARANGAN',
                'deskripsi' => '8.5. Berkelahi baik perorangan/kelompok di dalam sekolah/luar sekolah'
            ],
            [
                'judul' => 'LARANGAN - LARANGAN',
                'deskripsi' => '8.6. Mengotori dan merusak fasilitas sekolah'
            ],
            [
                'judul' => 'LARANGAN - LARANGAN',
                'deskripsi' => '8.7. Berbicara kotor, mengumpat, menggunjing, menghina, menyapa antar sesama/warga sekolah dengan kata-kata sapaan tidak sopan'
            ],
            [
                'judul' => 'LARANGAN - LARANGAN',
                'deskripsi' => '8.8. Membawa barang yang tidak ada hubungan dengan kepentingan sekolah seperti senjata tajam atau alat-alat yang membahayakan oranglain'
            ],
            [
                'judul' => 'LARANGAN - LARANGAN',
                'deskripsi' => '8.9. Membawa, membaca/mengedarkan bacaan, gambar, sketsa, video porno'
            ],
            [
                'judul' => 'LARANGAN - LARANGAN',
                'deskripsi' => '8.10. Menikah, hamil atau menghamili'
            ],
            [
                'judul' => 'LARANGAN - LARANGAN',
                'deskripsi' => '8.11. Membawa kartu dan bermain judi dilingkungan sekolah'
            ],
            [
                'judul' => 'LARANGAN - LARANGAN',
                'deskripsi' => '8.12. Terlibat tindak kriminal/ pidana dan berkekuatan hukum dan sudah mendapatkan vonis yang tetap'
            ],
            [
                'judul' => 'LARANGAN - LARANGAN',
                'deskripsi' => '8.13. Pacaran di lingkungan sekolah dengan memakai atribut sekolah'
            ],
        ];

        foreach ($tataTertib as $item) {
            DB::table('tata_tertib')->insert([
                'judul'   => $item['judul'],
                'deskripsi'  => $item['deskripsi'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
