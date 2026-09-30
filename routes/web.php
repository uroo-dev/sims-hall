<?php

use App\Http\Controllers\AdminPeminjamanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerPanelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\KepalaSekolahController;
use App\Http\Controllers\PaketPeminjamanController;
use App\Http\Controllers\PaymentConfigurationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:6,1');
});

Route::middleware('auth')->group(function () {
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,super_admin,super_duper_admin,pelanggan,kepala_sekolah')
        ->name('dashboard');

    // Kepala Sekolah: Dashboard & Persetujuan Final Peminjaman Aula
    Route::middleware('role:kepala_sekolah,super_admin,super_duper_admin')->prefix('kepala-sekolah')->name('kepala-sekolah.')->group(function () {
        Route::get('/dashboard', [KepalaSekolahController::class, 'dashboard'])->name('dashboard');
        Route::get('/peminjaman', [KepalaSekolahController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/{peminjaman}', [KepalaSekolahController::class, 'show'])->name('peminjaman.show');
        Route::post('/peminjaman/{peminjaman}/approve', [KepalaSekolahController::class, 'approve'])->name('peminjaman.approve');
        Route::post('/peminjaman/{peminjaman}/reject', [KepalaSekolahController::class, 'reject'])->name('peminjaman.reject');
    });

    // Admin Aula: CRUD Fasilitas, Paket Peminjaman, & Manajemen Peminjaman
    Route::middleware('adminFitur:aula')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('fasilitas', FasilitasController::class)
            ->parameters(['fasilitas' => 'facility'])
            ->except(['create', 'edit', 'show']);

        Route::resource('paket', PaketPeminjamanController::class)
            ->parameters(['paket' => 'paket'])
            ->except(['create', 'edit', 'show']);

        // Manajemen Peminjaman Aula
        Route::get('/peminjaman', [AdminPeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/{peminjaman}', [AdminPeminjamanController::class, 'show'])->name('peminjaman.show');
        Route::post('/peminjaman/{peminjaman}/approve', [AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::post('/peminjaman/{peminjaman}/reject', [AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject');
        Route::post('/peminjaman/{peminjaman}/verifikasi-pembayaran/{detail?}', [AdminPeminjamanController::class, 'verifikasiPembayaran'])->name('peminjaman.verifikasi-pembayaran');
        Route::post('/peminjaman/{peminjaman}/reject-pembayaran', [AdminPeminjamanController::class, 'rejectPembayaran'])->name('peminjaman.reject-pembayaran');
        Route::post('/peminjaman/{peminjaman}/upload-refund', [AdminPeminjamanController::class, 'uploadRefund'])->name('peminjaman.upload-refund');
    });

    // Super Admin: Konfigurasi Pembayaran Sekolah
    Route::middleware('role:super_admin,super_duper_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/payment-configuration', [PaymentConfigurationController::class, 'index'])->name('payment-configuration.index');
        Route::put('/payment-configuration', [PaymentConfigurationController::class, 'update'])->name('payment-configuration.update');
    });

    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('customer.dashboard');
        });
        Route::get('/dashboard', [CustomerPanelController::class, 'dashboard'])->name('dashboard');
        Route::get('/paket', [CustomerPanelController::class, 'paket'])->name('paket');

        // Pengajuan Peminjaman Aula
        Route::get('/peminjaman/buat', [CustomerPanelController::class, 'peminjamanCreate'])->name('peminjaman.create');
        Route::post('/peminjaman', [CustomerPanelController::class, 'peminjamanStore'])->name('peminjaman.store');

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
});

// contoh route
// Keterangan ROUTE (Route Users)
// Route::get('/namaroute', [namacontroller::class, 'index'])->name('/namaroute/index');

// contoh route dengan middleware role
// Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
//     Route::get('/admin', [namacontroller::class, 'index'])->name('admin.index');
// });

// contoh route dengan middleware adminFitur
// Route::middleware(['auth', 'adminFitur:ppdb'])->group(function () {
//     Route::get('/ppdb', [namacontroller::class, 'index'])->name('ppdb.index');
// });
