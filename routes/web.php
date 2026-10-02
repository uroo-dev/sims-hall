<?php

use App\Http\Controllers\AdminPeminjamanController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BkkController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CustomerPanelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataMasterDashboardController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\KepalaSekolahController;
use App\Http\Controllers\LaporanPemasukanController;
use App\Http\Controllers\PaketPeminjamanController;
use App\Http\Controllers\PaymentConfigurationController;
use App\Http\Controllers\PklController;
use App\Http\Controllers\PublicController;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
|
| Landing dan profil memakai PublicController karena view-nya butuh data
| sekolah, mitra industri, prestasi, dan produk unggulan.
*/
Route::get('/', [PublicController::class, 'landing'])->name('landing');

Route::get('/profil', function () {
    return view('Public.profil', [
        'sekolah' => Sekolah::first() ?? new Sekolah,
    ]);
})->name('profil');

Route::get('/ppdb', function () {
    return view('Public.ppdb');
})->name('ppdb');

Route::get('/kesiswaan', function () {
    return view('Public.kesiswaan');
})->name('kesiswaan');

Route::get('/produk-unggulan', function () {
    return view('Public.produk-unggulan');
})->name('produk-unggulan');

Route::get('/layanan-peminjaman', [PublicController::class, 'layananPeminjaman'])->name('layanan-peminjaman');

Route::get('/pkl', [PublicController::class, 'pkl'])->name('pkl');
Route::get('/pkl/mitra/{dudi}', [PublicController::class, 'pklDetail'])->name('pkl.detail');

Route::get('/bkk', [PublicController::class, 'bkk'])->name('bkk');
Route::get('/bkk/lowongan/{lowongan}', [PublicController::class, 'bkkDetail'])->name('bkk.detail');

Route::get('/pkl-bkk', [PublicController::class, 'pklBkk'])->name('pkl-bkk');

/*
|--------------------------------------------------------------------------
 | Chatbot "Nanya AI"
|
| Endpoint publik, dipanggil widget chatbot di seluruh halaman website.
| CSRF otomatis aktif karena berada di routes/web.php (grup middleware web).
| throttle:20,1 = maksimal 20 permintaan per menit per IP.
|--------------------------------------------------------------------------
*/
Route::post('/chatbot/send', [ChatbotController::class, 'send'])
    ->middleware('throttle:20,1')
    ->name('chatbot.send');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| Admin - Modul PKL & BKK
|
| Role yang boleh akses: bkk, admin, super_admin, super_duper_admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:bkk,admin_pklbkk,super_admin,super_duper_admin'])
    ->prefix('dashboard/pkl-bkk')
    ->name('pkl.')
    ->group(function () {

        // --- BKK: Career Center & Master DUDI ---
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

        // --- PKL: Pengajuan & Penempatan ---
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

/*
|--------------------------------------------------------------------------
| Dashboard utama (role admin & super admin)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'destroy'])->name('logout');

    // --- DASHBOARD UTAMA ---
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,admin_aula,admin_master,admin_kesiswaan,admin_produk,admin_produk_unggulan,admin_ppdb,admin_pklbkk,super_admin,super_duper_admin,pelanggan,kepala_sekolah')
        ->name('dashboard');

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

    /*
    |----------------------------------------------------------------------
    | Data Master Sekolah
    |----------------------------------------------------------------------
    */
    Route::prefix('dashboard/data-master')->group(function () {
        Route::get('/', [DataMasterDashboardController::class, 'index'])
            ->middleware('role:admin_master,super_admin,super_duper_admin')
            ->name('datamaster.index');

        Route::get('/sekolah', [DataMasterDashboardController::class, 'editSekolah'])
            ->middleware('role:admin_master,super_admin,super_duper_admin')
            ->name('datamaster.sekolah.edit');

        Route::put('/sekolah', [DataMasterDashboardController::class, 'updateSekolah'])
            ->middleware('role:admin_master,super_admin,super_duper_admin')
            ->name('datamaster.sekolah.update');

        Route::get('/users', [DataMasterDashboardController::class, 'users'])
            ->middleware('role:admin_master,super_admin,super_duper_admin')
            ->name('datamaster.users');

        Route::post('/users', [DataMasterDashboardController::class, 'storeUser'])
            ->middleware('role:admin_master,super_admin,super_duper_admin')
            ->name('datamaster.users.store');

        Route::put('/users/{id}', [DataMasterDashboardController::class, 'updateUser'])
            ->middleware('role:admin_master,super_admin,super_duper_admin')
            ->name('datamaster.users.update');

        Route::delete('/users/{id}', [DataMasterDashboardController::class, 'destroyUser'])
            ->middleware('role:admin_master,super_admin,super_duper_admin')
            ->name('datamaster.users.destroy');

        // Data Guru
        Route::get('/guru', [DataMasterDashboardController::class, 'guru'])
            ->middleware('role:admin_master,super_admin,super_duper_admin')
            ->name('datamaster.guru.index');

        Route::post('/guru', [DataMasterDashboardController::class, 'storeGuru'])
            ->middleware('role:admin_master,super_admin,super_duper_admin')
            ->name('datamaster.guru.store');

        Route::put('/guru/{id}', [DataMasterDashboardController::class, 'updateGuru'])
            ->middleware('role:admin_master,super_admin,super_duper_admin')
            ->name('datamaster.guru.update');

        Route::delete('/guru/{id}', [DataMasterDashboardController::class, 'destroyGuru'])
            ->middleware('role:admin_master,super_admin,super_duper_admin')
            ->name('datamaster.guru.destroy');

        // Data Siswa
        Route::get('/siswa', [DataMasterDashboardController::class, 'siswa'])
            ->middleware('role:admin_master,super_admin,super_duper_admin')
            ->name('datamaster.siswa.index');

        Route::post('/siswa', [DataMasterDashboardController::class, 'storeSiswa'])
            ->middleware('role:admin_master,super_admin,super_duper_admin')
            ->name('datamaster.siswa.store');

        Route::put('/siswa/{id}', [DataMasterDashboardController::class, 'updateSiswa'])
            ->middleware('role:admin_master,super_admin,super_duper_admin')
            ->name('datamaster.siswa.update');

        Route::delete('/siswa/{id}', [DataMasterDashboardController::class, 'destroySiswa'])
            ->middleware('role:admin_master,super_admin,super_duper_admin')
            ->name('datamaster.siswa.destroy');
    });
});
