<?php

namespace Database\Seeders;

use App\Models\Dudi;
use App\Models\Guru;
use App\Models\Lowongan;
use App\Models\PenempatanPkl;
use App\Models\Siswa;
use App\Models\SuratPengajuan;
use Illuminate\Database\Seeder;

class BkkSeeder extends Seeder
{
    /**
     * Data dummy realistis untuk pengujian modul PKL & Career Center.
     *
     * Menghasilkan:
     * - 2 DUDI Mitra Resmi
     * - 1 DUDI Usulan Baru
     * - 5 Siswa
     * - 2 Guru Pembimbing
     * - 2 Lowongan Kerja
     * - 1 Transaksi Penempatan PKL berstatus FIX
     *
     * Idempotent: aman dijalankan berkali-kali.
     */
    public function run(): void
    {
        $gurus = $this->seedGuru();
        $siswas = $this->seedSiswa();
        $dudis = $this->seedDudi();
        $lowongans = $this->seedLowongan($dudis);

        $this->seedPenempatanFix($dudis, $siswas, $gurus);
    }

    /**
     * 2 Guru Pembimbing (keter Wali Kelas).
     *
     * @return array<string, Guru>
     */
    private function seedGuru(): array
    {
        $data = [
            [
                'key' => 'budi',
                'nip' => '198705122011011002',
                'nama' => 'Budi Santoso, S.Pd.',
                'jurusan' => 'Rekayasa Perangkat Lunak (RPL)',
                'no_hp' => '081234567891',
            ],
            [
                'key' => 'siti',
                'nip' => '198203152006042004',
                'nama' => 'Siti Aminah, S.Pd.',
                'jurusan' => 'Teknik Komputer dan Jaringan (TKJ)',
                'no_hp' => '081298765432',
            ],
        ];

        $result = [];

        foreach ($data as $row) {
            $result[$row['key']] = Guru::updateOrCreate(
                ['nip' => $row['nip']],
                [
                    'nama' => $row['nama'],
                    'jurusan' => $row['jurusan'],
                    'no_hp' => $row['no_hp'],
                ]
            );
        }

        return $result;
    }

    /**
     * 5 Siswa kelas XII.
     *
     * @return array<string, Siswa>
     */
    private function seedSiswa(): array
    {
        $data = [
            [
                'key' => 'rizky',
                'nis' => '2401001',
                'nama' => 'Rizky Pratama',
                'kelas' => 'XII',
                'jurusan' => 'Rekayasa Perangkat Lunak (RPL)',
                'no_hp' => '081211112201',
            ],
            [
                'key' => 'putri',
                'nis' => '2401002',
                'nama' => 'Putri Anggraini',
                'kelas' => 'XII',
                'jurusan' => 'Rekayasa Perangkat Lunak (RPL)',
                'no_hp' => '081211112202',
            ],
            [
                'key' => 'dimas',
                'nis' => '2402001',
                'nama' => 'Dimas Wijaya',
                'kelas' => 'XII',
                'jurusan' => 'Teknik Komputer dan Jaringan (TKJ)',
                'no_hp' => '081211112203',
            ],
            [
                'key' => 'ayu',
                'nis' => '2403001',
                'nama' => 'Ayu Lestari',
                'kelas' => 'XII',
                'jurusan' => 'Teknik Perancangan dan Pembuatan Program Komputer (TKPK)',
                'no_hp' => '081211112204',
            ],
            [
                'key' => 'fajar',
                'nis' => '2403002',
                'nama' => 'Fajar Nugroho',
                'kelas' => 'XII',
                'jurusan' => 'Teknik Perancangan dan Pembuatan Program Komputer (TKPK)',
                'no_hp' => '081211112205',
            ],
        ];

        $result = [];

        foreach ($data as $row) {
            $result[$row['key']] = Siswa::updateOrCreate(
                ['nis' => $row['nis']],
                [
                    'nama' => $row['nama'],
                    'kelas' => $row['kelas'],
                    'jurusan' => $row['jurusan'],
                    'no_hp' => $row['no_hp'],
                ]
            );
        }

        return $result;
    }

    /**
     * 3 DUDI: 2 Mitra Resmi (sudah ACC & tayang) + 1 Usulan Baru (belum ACC).
     *
     * @return array<string, Dudi>
     */
    private function seedDudi(): array
    {
        $data = [
            [
                'key' => 'sekaruna',
                'nama_dudi' => 'PT Sekaruna Prima Teknologi',
                'alamat' => 'Jl. Slamet Riyadi No. 145, Laweyan',
                'kota' => 'Surakarta',
                'bidang_usaha' => 'Pengembangan Perangkat Lunak & IT',
                'kontak_person' => 'Bapak Sutrisno (HRD)',
                'no_hp' => '0271712345',
                'kuota_maksimal' => 10,
                'is_mitra_resmi' => true,
                'tampil_di_landing' => true,
            ],
            [
                'key' => 'jaya',
                'nama_dudi' => 'CV Jaya Mandiri Elektronika',
                'alamat' => 'Jl. Adi Sucipto No. 88, Palur',
                'kota' => 'Karanganyar',
                'bidang_usaha' => 'Elektronika & Perakitan Komponen',
                'kontak_person' => 'Ibu Ratna (HRD)',
                'no_hp' => '0271834567',
                'kuota_maksimal' => 6,
                'is_mitra_resmi' => true,
                'tampil_di_landing' => true,
            ],
            [
                'key' => 'berkah',
                'nama_dudi' => 'Toko Berkah Computer',
                'alamat' => 'Jl. Ahmad Yani No. 12, Karanganyar',
                'kota' => 'Karanganyar',
                'bidang_usaha' => 'Jasa Service Komputer & Jaringan',
                'kontak_person' => 'Bapak Heri',
                'no_hp' => '0271956789',
                'kuota_maksimal' => 3,
                'is_mitra_resmi' => false,
                'tampil_di_landing' => false,
            ],
        ];

        $result = [];

        foreach ($data as $row) {
            $result[$row['key']] = Dudi::updateOrCreate(
                ['nama_dudi' => $row['nama_dudi']],
                [
                    'alamat' => $row['alamat'],
                    'kota' => $row['kota'],
                    'bidang_usaha' => $row['bidang_usaha'],
                    'kontak_person' => $row['kontak_person'],
                    'no_hp' => $row['no_hp'],
                    'kuota_maksimal' => $row['kuota_maksimal'],
                    'is_mitra_resmi' => $row['is_mitra_resmi'],
                    'tampil_di_landing' => $row['tampil_di_landing'],
                ]
            );
        }

        return $result;
    }

    /**
     * 2 Lowongan Kerja (1 Pekerjaan, 1 Magang).
     *
     * @param  array<string, Dudi>  $dudis
     * @return array<int, Lowongan>
     */
    private function seedLowongan(array $dudis): array
    {
        $result = [];

        $result['frontend'] = Lowongan::updateOrCreate(
            ['nama_perusahaan' => 'PT Sekaruna Prima Teknologi', 'posisi' => 'Junior Frontend Developer'],
            [
                'dudi_id' => $dudis['sekaruna']->id,
                'tipe' => Lowongan::TIPE_PEKERJAAN,
                'jurusan_sesuai' => 'RPL, TKJ',
                'deskripsi' => 'Kami mencari tenaga Junior Frontend Developer untuk placement offline. Kandidat akan mengerjakan aplikasi web menggunakan React dan Tailwind CSS. Fresh graduate atau lulusan SMK lebih diutamakan.',
                'link_daftar' => 'https://forms.gle/contoh-sekaruna-frontend',
                'deadline' => now()->addDays(30)->toDateString(),
                'is_active' => true,
            ]
        );

        $result['magang'] = Lowongan::updateOrCreate(
            ['nama_perusahaan' => 'CV Jaya Mandiri Elektronika', 'posisi' => 'Magang Teknik Elektronika'],
            [
                'dudi_id' => $dudis['jaya']->id,
                'tipe' => Lowongan::TIPE_MAGANG,
                'jurusan_sesuai' => Lowongan::SEMUA_JURUSAN,
                'deskripsi' => 'Program magang 3 bulan di bidang perakitan dan quality control komponen elektronik. Peserta akan dibimbing oleh teknisi berpengalaman.',
                'link_daftar' => 'https://wa.me/6281234567890',
                'deadline' => now()->addDays(21)->toDateString(),
                'is_active' => true,
            ]
        );

        return $result;
    }

    /**
     * 1 Transaksi Penempatan PKL status FIX.
     *
     * @param  array<string, Dudi>  $dudis
     * @param  array<string, Siswa>  $siswas
     * @param  array<string, Guru>  $gurus
     */
    private function seedPenempatanFix(array $dudis, array $siswas, array $gurus): void
    {
        $dudi = $dudis['sekaruna'];
        $siswa = $siswas['rizky'];
        $guru = $gurus['budi'];

        $tanggalSurat = now()->subDays(60)->startOfDay();
        $tglMulai = now()->startOfMonth()->toDateString();
        $tglSelesai = now()->addMonths(3)->endOfMonth()->toDateString();

        // Dicari berdasarkan DUDI + tanggal surat (keduanya deterministik),
        // BUKAN `firstOrCreate` dengan nomor surat hasil generate: nomor itu
        // selalu berubah tiap eksekusi sehingga seeder tidak idempotent.
        // `whereDate` dipakai karena `tanggal_surat` di-cast jadi date, sehingga
        // tersimpan sebagai "Y-m-d 00:00:00" di database.
        $surat = SuratPengajuan::where('dudi_id', $dudi->id)
            ->whereDate('tanggal_surat', $tanggalSurat->toDateString())
            ->first();

        if (! $surat) {
            $surat = new SuratPengajuan;
            $surat->nomor_surat = SuratPengajuan::generateNomorSurat($tanggalSurat->year);
            $surat->file_pdf_path = null;
        }

        $surat->dudi_id = $dudi->id;
        $surat->tanggal_surat = $tanggalSurat->toDateString();
        $surat->tgl_mulai_pkl = $tglMulai;
        $surat->tgl_selesai_pkl = $tglSelesai;
        $surat->save();

        PenempatanPkl::updateOrCreate(
            [
                'surat_pengajuan_id' => $surat->id,
                'siswa_id' => $siswa->id,
            ],
            [
                'dudi_id' => $dudi->id,
                'guru_id' => $guru->id,
                'status_penempatan' => PenempatanPkl::STATUS_FIX,
                'catatan' => 'DUDI menyetujui, siswa sudah mulai PKL. Menunggu ACC tayang di landing page.',
            ]
        );
    }
}
