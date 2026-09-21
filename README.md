# sims-hall — Sistem Informasi Sarana Prasarana SMK N 2 Kra

Sistem Informasi Manajemen Sarana Prasarana (SIMS) untuk **SMK N 2 Kra** yang mencakup **website profil sekolah**, **sistem peminjaman aula** dengan persetujuan dua tahap, pemantauan pemakaian gedung, serta manajemen data sekolah (kesiswaan, PPDB, PKL & BKK, produk unggulan) oleh masing-masing peran pengguna.

## Fitur Utama

### Halaman Publik (Landing Page)
- **Profil Sekolah** — sejarah sekolah, kepala sekolah, identitas, visi & misi
- **Peminjaman Aula** — informasi peminjaman, katalog jenis peminjaman, login/registrasi akun
- **PPDB** — informasi PPDB
- **PKL & Carier Center (BKK)** — kerja sama industri, jurusan, lowongan pekerjaan untuk alumni
- **Kesiswaan** — prestasi, ekstrakurikuler, peraturan / tata tertib
- **Produk Unggulan** — katalog produk unggulan sekolah

### Dashboard per Peran
| Peran | Fitur Akses |
| --- | --- |
| **Super Admin** | Data kepala sekolah, Data Master Sekolah (data sekolah, manajemen users), Data PKL & BKK (Dudi, Lowongan Kerja, PKL, Jurusan), Data PPDB (Informasi PPDB), Data Kesiswaan (Prestasi, Ekstrakurikuler, Tata Tertib), Data Produk Unggulan, Data Peminjaman Sarpras |
| **Admin Sarpras** | Data Peminjaman Sarpras, CRUD Fasilitas, CRUD Paket Peminjaman, Persetujuan Tahap 1 & Pembayaran, Persetujuan Tahap 2, Laporan Operasional |
| **Guru / Staff Sekolah** | Melihat kalender aula, mengajukan peminjaman aula, melihat riwayat & status peminjaman, download bukti peminjaman, profile |
| **Organisasi Sekolah** | Melihat kalender aula, mengajukan peminjaman aula, melihat riwayat & status peminjaman, download bukti peminjaman, profile |
| **Instansi Luar / Umum** | Kalender aula, ajukan peminjaman + upload surat permohonan, lihat biaya & lakukan pembayaran, riwayat & status, download bukti, profile |
| **Instansi Luar Terhubung** | Kalender aula, ajukan peminjaman + upload surat permohonan, riwayat & status, download bukti, profile |

### Alur Peminjaman Aula
1. Pengguna mengajukan peminjaman aula (kalender + katalog paket peminjaman)
2. **Persetujuan Tahap 1** oleh Admin Sarpras
3. **Pembayaran** berdasarkan paket peminjaman terpilih (gratis / transfer / cash)
4. **Persetujuan Tahap 2** oleh pihak terkait
5. Download **bukti peminjaman**

## Teknologi

- **Backend** — Laravel 13 (PHP 8.x)
- **Frontend** — Blade + Tailwind CSS 4 (Vite)
- **Database** — MySQL
- **Autentikasi** — Laravel Breeze (Blade)

## Struktur Folder

```
app/
├── Http/Controllers/
│   ├── Admin/          # Controller area admin
│   └── Public/         # Controller halaman publik
├── Http/Requests/      # Validasi form per area
└── Models/             # Model Eloquent
resources/views/
├── Admin/              # Panel admin (layout, bkk, dll.)
├── Public/             # Halaman publik (layout, bkk, dll.)
└── Auth/               # Halaman autentikasi
routes/
├── web.php             # Route utama
├── admin.php           # Route area admin
└── public.php          # Route publik
database/
├── migrations/         # Migrasi tabel
└── seeders/            # Data awal
```

## Database

### Skema Utama

| Tabel | Deskripsi |
| --- | --- |
| `users` | Pengguna (role: organisasi, guru, kepala sekolah, instansi luar terikat, instansi luar) |
| `sekolah` | Profil sekolah (judul, deskripsi, dokumentasi, identitas, yel-yel, visi-misi) |
| `aula` | Data aula (judul, deskripsi, dokumentasi) |
| `fasilitas` | Fasilitas aula (judul, deskripsi) |
| `paket_peminjaman` | Paket sewa aula (harga, kategori: unggulan, terjangkau, standar 1-3, fasilitas) |
| `peminjaman` | Pengajuan peminjaman (nama, email instansi, tanggal, catatan, surat pengantar) |
| `persetujuan_1` | Persetujuan Tahap 1 (status: verifikasi, tolak, terverifikasi, tertolak) |
| `persetujuan_2` | Persetujuan Tahap 2 (status: verifikasi, tolak, terverifikasi, tertolak) |
| `pembayaran` | Pembayaran sewa (tahap_1, tahap_2, status: gratis, terverifikasi, pending, tertolak) |
| `pembayaran_pending` | Pembayaran menunggu konfirmasi (metode: transfer, cash) |
| `laporan_operasional` | Laporan operasional peminjaman |
| `kesiswaan` | Data kesiswaan |
| `prestasi` | Prestasi (kategori: akademik, non akademik) |
| `ektrakulikuler` | Ekstrakurikuler sekolah |
| `tata_tertib` | Peraturan / tata tertib |
| `produk_unggulan` | Produk unggulan sekolah |
| `produk` | Detail produk (per jurusan) |
| `PKL_BKK` | Informasi PKL & Career Center |
| `dudi` | Dunia Usaha / Dunia Industri (kerjasama) |
| `lowongan_kerja` | Lowongan pekerjaan untuk alumni |
| `siswa_pkl` | Data siswa PKL |
| `jurusan` | Data jurusan |
| `ppdb` | Info PPDB |
| `informasi_ppdb` | Agenda & persyaratan PPDB |

### Relasi Utama
- `paket_peminjaman` → `fasilitas` (setiap paket memiliki fasilitas)
- `peminjaman` → `paket_peminjaman`
- `peminjaman` → `pembayaran_pending` dan `laporan_operasional`
- `produk`, `dudi`, `lowongan_kerja`, `siswa_pkl` → `jurusan`
- `siswa_pkl` → `dudi`

## Instalasi

```bash
# 1. Install dependency
composer install
npm install

# 2. Konfigurasi environment
cp .env.example .env
php artisan key:generate
# atur koneksi database MySQL di .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 3. Migrasi & seed database
php artisan migrate --seed

# 4. Build asset frontend
npm run build

# 5. Jalankan server
php artisan serve
```

## Akun Default

> *(segera ditambahkan setelah seeder dibuat)*

## Lisensi

MIT License