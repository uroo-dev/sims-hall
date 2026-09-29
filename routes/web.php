<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerPanelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FasilitasController;
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
        ->middleware('role:admin,super_admin,super_duper_admin,pelanggan')
        ->name('dashboard');

    // Admin Aula: CRUD Fasilitas & Paket Peminjaman
    Route::middleware('adminFitur:aula')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('fasilitas', FasilitasController::class)
            ->parameters(['fasilitas' => 'facility'])
            ->except(['create', 'edit', 'show']);

        Route::resource('paket', PaketPeminjamanController::class)
            ->parameters(['paket' => 'paket'])
            ->except(['create', 'edit', 'show']);
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
