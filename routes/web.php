<?php

use App\Http\Controllers\Admin\Ppdb\PpdbDashboardController;
use App\Http\Controllers\Admin\Ppdb\PpdbInformasiController;
use App\Http\Controllers\AdminPeminjamanController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BkkController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CustomerPanelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataMasterDashboardController;
use App\Http\Controllers\EkstrakurikulerController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\KategoriArtikelController;
use App\Http\Controllers\KepalaSekolahController;
use App\Http\Controllers\KesiswaanController;
use App\Http\Controllers\LaporanPemasukanController;
use App\Http\Controllers\PaketPeminjamanController;
use App\Http\Controllers\PaymentConfigurationController;
use App\Http\Controllers\PklController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProdukUnggulanController;
use App\Http\Controllers\Public\Ppdb\PpdbController;
use App\Http\Controllers\Public\ProdukUnggulanController as PublicProdukUnggulanController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicKesiswaanController;
use App\Http\Controllers\TataTertibController;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Route;

// Halaman Publik
Route::get('/', [PublicController::class, 'landing'])->name('landing');

Route::get('/profil', function () {
    return view('Public.profil', [
        'sekolah' => Sekolah::first() ?? new Sekolah,
    ]);
})->name('profil');

Route::get('/kesiswaan', [PublicKesiswaanController::class, 'index'])->name('kesiswaan');
Route::get('/kesiswaan/buku-saku/pdf', [PublicKesiswaanController::class, 'downloadBukuSakuPdf'])->name('kesiswaan.buku-saku.pdf');
Route::get('/kesiswaan/tata-tertib/{id}/pdf', [PublicKesiswaanController::class, 'downloadTataTertibPdf'])->name('kesiswaan.tata-tertib.pdf');

Route::get('/produk-unggulan', [PublicProdukUnggulanController::class, 'index'])->name('produk-unggulan');

Route::get('/layanan-peminjaman', [PublicController::class, 'layananPeminjaman'])->name('layanan-peminjaman');

Route::get('/pkl', [PublicController::class, 'pkl'])->name('pkl');
Route::get('/pkl/mitra/{dudi}', [PublicController::class, 'pklDetail'])->name('pkl.detail');

Route::get('/bkk', [PublicController::class, 'bkk'])->name('bkk');
Route::get('/bkk/lowongan/{lowongan}', [PublicController::class, 'bkkDetail'])->name('bkk.detail');

Route::get('/pkl-bkk', [PublicController::class, 'pklBkk'])->name('pkl-bkk');

Route::get('/informasi', [PublicController::class, 'informasi'])->name('informasi');
Route::get('/informasi/{slug}', [PublicController::class, 'informasiDetail'])->name('informasi.show');

// Chatbot Nanya AI
Route::post('/chatbot/send', [ChatbotController::class, 'send'])
    ->middleware('throttle:20,1')
    ->name('chatbot.send');

// Auth & PPDB
Route::get('/ppdb', [PpdbController::class, 'index'])->name('ppdb');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:6,1');

    Route::get('/login/admin', function () {
        return view('Auth.login-admin');
    })->name('login.admin');

    Route::get('/registrasi', [AuthController::class, 'register'])->name('registrasi');
    Route::post('/registrasi', [AuthController::class, 'registerStore'])->name('registrasi.store')->middleware('throttle:6,1');
    Route::get('/register', fn () => redirect()->route('registrasi'))->name('register');
});

// Admin - Modul PKL & BKK
Route::middleware(['auth', 'role:bkk,admin_pklbkk,super_admin,super_duper_admin'])
    ->prefix('dashboard/pkl-bkk')
    ->name('pkl.')
    ->group(function () {

        // BKK: Career Center & Master DUDI
        Route::get('/', [BkkController::class, 'index'])->name('dashboard');

        Route::get('/dudi', [BkkController::class, 'dudi'])->name('dudi.index');
        Route::post('/dudi', [BkkController::class, 'storeDudi'])->name('dudi.store');
        Route::patch('/dudi/{dudi}', [BkkController::class, 'updateDudi'])->name('dudi.update');

        Route::get('/lowongan', [BkkController::class, 'lowongan'])->name('lowongan.index');
        Route::get('/lowongan/tambah', [BkkController::class, 'lowonganForm'])->name('lowongan.create');
        Route::post('/lowongan', [BkkController::class, 'storeLowongan'])->name('lowongan.store');
        Route::get('/lowongan/{lowongan}/edit', [BkkController::class, 'lowonganForm'])->name('lowongan.edit');
        Route::put('/lowongan/{lowongan}', [BkkController::class, 'updateLowongan'])->name('lowongan.update');
        Route::patch('/lowongan/{lowongan}/toggle', [BkkController::class, 'toggleLowongan'])->name('lowongan.toggle');
        Route::delete('/lowongan/{lowongan}', [BkkController::class, 'destroyLowongan'])->name('lowongan.destroy');

        Route::get('/siswa', [BkkController::class, 'siswa'])->name('siswa.index');

        // PKL: Pengajuan & Penempatan
        Route::get('/penempatan', [PklController::class, 'index'])->name('index');
        Route::get('/pengajuan/create', [PklController::class, 'create'])->name('create');
        Route::post('/pengajuan', [PklController::class, 'store'])->name('store');

        Route::get('/surat/{surat}', [PklController::class, 'showSurat'])->name('surat.show');
        Route::put('/surat/{surat}', [PklController::class, 'updateSurat'])->name('surat.update');
        Route::get('/surat/{surat}/download', [PklController::class, 'downloadSurat'])->name('surat.download');
        Route::post('/surat/{surat}/regenerate', [PklController::class, 'regeneratePdf'])->name('surat.regenerate');

        Route::patch('/penempatan/{penempatan}/status', [PklController::class, 'updateStatus'])->name('penempatan.status');
        Route::patch('/penempatan/bulk-status', [PklController::class, 'bulkUpdateStatus'])->name('penempatan.bulk-status');

        Route::patch('/dudi/{dudi}/acc-landing', [PklController::class, 'accLanding'])->name('dudi.acc-landing');
    });

// Dashboard utama & modul admin
Route::middleware('auth')->group(function () {
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'destroy'])->name('logout');

    // Dashboard Dispatcher
    Route::get('/dashboard', [DashboardController::class, 'dispatch'])
        ->name('dashboard');

    // Admin Aula: Dashboard Peminjaman Aula (hanya admin aula, admin, dan super admin via middleware)
    Route::middleware('role:admin_aula,admin,super_admin,super_duper_admin')
        ->prefix('admin/peminjaman')
        ->name('admin.peminjaman.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        });

    // Admin Sekolah / Super Admin: CRUD Kategori Artikel & Artikel
    Route::middleware('role:admin_sekolah,super_admin,super_duper_admin')->prefix('admin')->name('admin.')->group(function () {
        // Kategori Artikel
        Route::get('/kategori-artikel', [KategoriArtikelController::class, 'index'])->name('kategori-artikel.index');
        Route::post('/kategori-artikel', [KategoriArtikelController::class, 'store'])->name('kategori-artikel.store');
        Route::put('/kategori-artikel/{id}', [KategoriArtikelController::class, 'update'])->name('kategori-artikel.update');
        Route::delete('/kategori-artikel/{id}', [KategoriArtikelController::class, 'destroy'])->name('kategori-artikel.destroy');

        // Artikel
        Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel.index');
        Route::get('/artikel/create', [ArtikelController::class, 'create'])->name('artikel.create');
        Route::post('/artikel', [ArtikelController::class, 'store'])->name('artikel.store');
        Route::post('/artikel/upload-image', [ArtikelController::class, 'uploadImage'])->name('artikel.upload-image');
        Route::get('/artikel/{id}', [ArtikelController::class, 'show'])->name('artikel.show');
        Route::get('/artikel/{id}/edit', [ArtikelController::class, 'edit'])->name('artikel.edit');
        Route::put('/artikel/{id}', [ArtikelController::class, 'update'])->name('artikel.update');
        Route::delete('/artikel/{id}', [ArtikelController::class, 'destroy'])->name('artikel.destroy');
    });

    // Admin Kesiswaan / Super Admin: Dashboard, Ekstrakurikuler, Tata Tertib
    Route::middleware('role:admin_kesiswaan,super_admin,super_duper_admin')->prefix('admin/kesiswaan')->name('admin.kesiswaan.')->group(function () {
        Route::get('/', [KesiswaanController::class, 'index'])->name('index');
        Route::put('/', [KesiswaanController::class, 'update'])->name('update');

        // Ekstrakurikuler
        Route::get('/ekstrakurikuler', [EkstrakurikulerController::class, 'index'])->name('ekstrakurikuler.index');
        Route::post('/ekstrakurikuler', [EkstrakurikulerController::class, 'store'])->name('ekstrakurikuler.store');
        Route::put('/ekstrakurikuler/{id}', [EkstrakurikulerController::class, 'update'])->name('ekstrakurikuler.update');
        Route::delete('/ekstrakurikuler/{id}', [EkstrakurikulerController::class, 'destroy'])->name('ekstrakurikuler.destroy');

        // Tata Tertib
        Route::get('/tata-tertib', [TataTertibController::class, 'index'])->name('tata-tertib.index');
        Route::get('/tata-tertib/export-all-pdf', [TataTertibController::class, 'exportAllPdf'])->name('tata-tertib.export-all-pdf');
        Route::get('/tata-tertib/{id}/pdf', [TataTertibController::class, 'exportPdf'])->name('tata-tertib.pdf');
        Route::post('/tata-tertib', [TataTertibController::class, 'store'])->name('tata-tertib.store');
        Route::put('/tata-tertib/{id}', [TataTertibController::class, 'update'])->name('tata-tertib.update');
        Route::delete('/tata-tertib/{id}', [TataTertibController::class, 'destroy'])->name('tata-tertib.destroy');
    });

    // Kepala Sekolah: Dashboard & Persetujuan Final Peminjaman Aula
    Route::middleware('role:kepala_sekolah,super_admin,super_duper_admin')->prefix('kepala-sekolah')->name('kepala-sekolah.')->group(function () {
        Route::get('/dashboard', [KepalaSekolahController::class, 'dashboard'])->name('dashboard');
        Route::get('/peminjaman', [KepalaSekolahController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/export/pdf', [KepalaSekolahController::class, 'exportPdf'])->name('peminjaman.export-pdf');
        Route::get('/peminjaman/{peminjaman}', [KepalaSekolahController::class, 'show'])->name('peminjaman.show');
        Route::post('/peminjaman/{peminjaman}/approve', [KepalaSekolahController::class, 'approve'])
            ->middleware('role:kepala_sekolah')
            ->name('peminjaman.approve');
        Route::post('/peminjaman/{peminjaman}/reject', [KepalaSekolahController::class, 'reject'])
            ->middleware('role:kepala_sekolah')
            ->name('peminjaman.reject');

        // Laporan Rekapitulasi Pemasukan Aula (Kepala Sekolah)
        Route::get('/laporan-pemasukan', [LaporanPemasukanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan-pemasukan/pdf', [LaporanPemasukanController::class, 'exportPdf'])->name('laporan.pdf');
    });

    // Admin Aula: Konfigurasi Aula, CRUD Fasilitas, Paket Peminjaman, & Manajemen Peminjaman
    Route::middleware('adminFitur:aula')->prefix('admin')->name('admin.')->group(function () {
        // Konfigurasi Profil & Informasi Aula
        Route::get('/aula', [AulaController::class, 'index'])->name('aula.index');
        Route::put('/aula', [AulaController::class, 'update'])->name('aula.update');

        Route::resource('fasilitas', FasilitasController::class)
            ->parameters(['fasilitas' => 'facility'])
            ->except(['create', 'edit', 'show']);

        Route::resource('paket', PaketPeminjamanController::class)
            ->parameters(['paket' => 'paket'])
            ->except(['create', 'edit', 'show']);

        // Manajemen Peminjaman Aula
        Route::get('/peminjaman', [AdminPeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/export/pdf', [AdminPeminjamanController::class, 'exportPdf'])->name('peminjaman.export-pdf');
        Route::get('/peminjaman/{peminjaman}', [AdminPeminjamanController::class, 'show'])->name('peminjaman.show');
        Route::post('/peminjaman/{peminjaman}/approve', [AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::post('/peminjaman/{peminjaman}/reject', [AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject');
        Route::post('/peminjaman/{peminjaman}/cancel', [AdminPeminjamanController::class, 'cancel'])->name('peminjaman.cancel');
        Route::post('/peminjaman/{peminjaman}/verifikasi-pembayaran/{detail?}', [AdminPeminjamanController::class, 'verifikasiPembayaran'])->name('peminjaman.verifikasi-pembayaran');
        Route::post('/peminjaman/{peminjaman}/reject-pembayaran', [AdminPeminjamanController::class, 'rejectPembayaran'])->name('peminjaman.reject-pembayaran');
        Route::post('/peminjaman/{peminjaman}/upload-refund', [AdminPeminjamanController::class, 'uploadRefund'])->name('peminjaman.upload-refund');
        Route::post('/peminjaman/{peminjaman}/set-harga-custom', [AdminPeminjamanController::class, 'setHargaCustom'])->name('peminjaman.set-harga-custom');

        // Laporan Rekapitulasi Pemasukan Aula (Admin Aula)
        Route::get('/laporan-pemasukan', [LaporanPemasukanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan-pemasukan/pdf', [LaporanPemasukanController::class, 'exportPdf'])->name('laporan.pdf');
    });

    // Super Admin: Konfigurasi Pembayaran Sekolah
    Route::middleware('role:super_admin,super_duper_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/payment-configuration', [PaymentConfigurationController::class, 'index'])->name('payment-configuration.index');
        Route::put('/payment-configuration', [PaymentConfigurationController::class, 'update'])->name('payment-configuration.update');
    });

    // Customer Panel: Hanya role pelanggan yang diizinkan mengajukan peminjaman & mengakses panel pelanggan
    Route::middleware('role:pelanggan')->prefix('customer')->name('customer.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('customer.dashboard');
        });
        Route::get('/dashboard', [CustomerPanelController::class, 'dashboard'])->name('dashboard');
        Route::get('/paket', [CustomerPanelController::class, 'paket'])->name('paket');

        // Pengajuan Peminjaman Aula
        Route::get('/peminjaman/buat', [CustomerPanelController::class, 'peminjamanCreate'])->name('peminjaman.create');
        Route::post('/peminjaman', [CustomerPanelController::class, 'peminjamanStore'])->name('peminjaman.store');
        Route::post('/peminjaman/{peminjaman}/cancel', [CustomerPanelController::class, 'peminjamanCancel'])->name('peminjaman.cancel');

        // Pembayaran Aula
        Route::get('/pembayaran/{pembayaran}', [CustomerPanelController::class, 'pembayaranShow'])->name('pembayaran.show');
        Route::post('/pembayaran/{pembayaran}', [CustomerPanelController::class, 'pembayaranBayar'])->name('pembayaran.bayar');

        // Alur Pengembalian Dana (Refund)
        Route::post('/pembayaran/{pembayaran}/rekening-refund', [CustomerPanelController::class, 'simpanRekeningRefund'])->name('pembayaran.rekening-refund');
        Route::post('/pembayaran/{pembayaran}/konfirmasi-refund', [CustomerPanelController::class, 'konfirmasiRefund'])->name('pembayaran.konfirmasi-refund');

        Route::get('/cek-peminjaman', [CustomerPanelController::class, 'riwayat'])->name('cek-peminjaman');
        Route::get('/riwayat', [CustomerPanelController::class, 'riwayat'])->name('riwayat');
        Route::get('/profil', [CustomerPanelController::class, 'profil'])->name('profil');
    });

    // Data Master Sekolah
    Route::prefix('dashboard/data-master')->group(function () {
        // Akses penuh: Data Sekolah & Users — hanya admin_master ke atas
        Route::middleware('role:admin_master,super_admin,super_duper_admin')->group(function () {
            Route::get('/sekolah', [DataMasterDashboardController::class, 'editSekolah'])->name('datamaster.sekolah.edit');
            Route::put('/sekolah', [DataMasterDashboardController::class, 'updateSekolah'])->name('datamaster.sekolah.update');

            Route::get('/users', [DataMasterDashboardController::class, 'users'])->name('datamaster.users');
            Route::post('/users', [DataMasterDashboardController::class, 'storeUser'])->name('datamaster.users.store');
            Route::put('/users/{id}', [DataMasterDashboardController::class, 'updateUser'])->name('datamaster.users.update');
            Route::delete('/users/{id}', [DataMasterDashboardController::class, 'destroyUser'])->name('datamaster.users.destroy');
        });

        // Akses shared: Dashboard, Data Guru, Data Siswa — admin_master & admin_sekolah
        Route::middleware('role:admin_master,admin_sekolah,super_admin,super_duper_admin')->group(function () {
            Route::get('/', [DataMasterDashboardController::class, 'index'])->name('datamaster.index');

            // Data Guru
            Route::get('/guru', [DataMasterDashboardController::class, 'guru'])->name('datamaster.guru.index');
            Route::post('/guru', [DataMasterDashboardController::class, 'storeGuru'])->name('datamaster.guru.store');
            Route::put('/guru/{id}', [DataMasterDashboardController::class, 'updateGuru'])->name('datamaster.guru.update');
            Route::delete('/guru/{id}', [DataMasterDashboardController::class, 'destroyGuru'])->name('datamaster.guru.destroy');

            // Data Siswa
            Route::get('/siswa', [DataMasterDashboardController::class, 'siswa'])->name('datamaster.siswa.index');
            Route::post('/siswa', [DataMasterDashboardController::class, 'storeSiswa'])->name('datamaster.siswa.store');
            Route::put('/siswa/{id}', [DataMasterDashboardController::class, 'updateSiswa'])->name('datamaster.siswa.update');
            Route::delete('/siswa/{id}', [DataMasterDashboardController::class, 'destroySiswa'])->name('datamaster.siswa.destroy');
        });
    });
    Route::middleware('role:admin_ppdb,super_admin,super_duper_admin')->prefix('admin/ppdb')->group(function () {
        Route::get('/dashboard', [PpdbDashboardController::class, 'index'])->name('index.dashboard.ppdb');
        Route::get('/informasi', [PpdbInformasiController::class, 'index'])->name('index.informasi.ppdb');
        Route::post('/master', [PpdbDashboardController::class, 'masterUpdate'])->name('update.master.ppdb');
        Route::post('/informasi', [PpdbInformasiController::class, 'informasiUpdate'])->name('update.informasi.ppdb');
        Route::post('/tanggal-penting', [PpdbInformasiController::class, 'tanggalPentingPost'])->name('post.tanggal-penting.ppdb');
        Route::post('/persyaratan', [PpdbInformasiController::class, 'persyaratanPost'])->name('post.persyaratan.ppdb');
        Route::post('/persyaratan-file', [PpdbInformasiController::class, 'persyaratanFileUpload'])->name('upload.persyaratan.file.ppdb');
        Route::put('/tanggal-penting/{agenda}', [PpdbInformasiController::class, 'tanggalPentingUpdate'])->name('update.tanggal-penting.ppdb');
        Route::delete('/tanggal-penting/{agenda}', [PpdbInformasiController::class, 'tanggalPentingDelete'])->name('delete.tanggal-penting.ppdb');
        Route::delete('/persyaratan-file', [PpdbInformasiController::class, 'persyaratanFileDelete'])->name('delete.persyaratan.file.ppdb');
        Route::post('/hasil-seleksi-file', [PpdbInformasiController::class, 'hasilSeleksiFileUpload'])->name('upload.hasil-seleksi.file.ppdb');
        Route::delete('/hasil-seleksi-file', [PpdbInformasiController::class, 'hasilSeleksiFileDelete'])->name('delete.hasil-seleksi.file.ppdb');
        Route::get('/persyaratan/{id}', [PpdbInformasiController::class, 'persyaratanDelete'])->name('delete.persyaratan.ppdb');

        // Jurusan
        Route::post('/jurusan', [PpdbInformasiController::class, 'jurusanStore'])->name('post.jurusan.ppdb');
        Route::put('/jurusan/{jurusan}', [PpdbInformasiController::class, 'jurusanUpdate'])->name('update.jurusan.ppdb');
        Route::post('/jurusan/{jurusan}/image', [PpdbInformasiController::class, 'jurusanImageUpdate'])->name('update.jurusan.image.ppdb');
        Route::delete('/jurusan/{jurusan}/image', [PpdbInformasiController::class, 'jurusanImageDestroy'])->name('delete.jurusan.image.ppdb');
        Route::delete('/jurusan/{jurusan}', [PpdbInformasiController::class, 'jurusanDestroy'])->name('delete.jurusan.ppdb');

        // Jalur Seleksi
        Route::post('/jalur', [PpdbInformasiController::class, 'jalurStore'])->name('post.jalur.ppdb');
        Route::put('/jalur/{jalur}', [PpdbInformasiController::class, 'jalurUpdate'])->name('update.jalur.ppdb');
        Route::delete('/jalur/{jalur}', [PpdbInformasiController::class, 'jalurDestroy'])->name('delete.jalur.ppdb');
    });
});

Route::get('/produk-unggulan-publik', [PublicProdukUnggulanController::class, 'index'])
    ->name('public.produk-unggulan');

Route::middleware(['auth', 'role:admin,admin_produk,admin_produk_unggulan,super_admin,super_duper_admin'])->prefix('admin/produk-unggulan')->group(function () {
    Route::get('/', [ProdukUnggulanController::class, 'index'])->name('produk-unggulan.index');
    Route::put('/', [ProdukUnggulanController::class, 'update'])->name('produk-unggulan.update');
    Route::delete('/', [ProdukUnggulanController::class, 'destroy'])->name('produk-unggulan.destroy');

    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
});
