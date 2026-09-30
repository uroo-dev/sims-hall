<?php

use App\Http\Controllers\Admin\Ppdb\PpdbDashboardController;
use App\Http\Controllers\Admin\Ppdb\PpdbInformasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:6,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,super_admin,super_duper_admin')
        ->name('dashboard');

    Route::prefix('ppdb')->group(function () {
        Route::get('/dashboard', [PpdbDashboardController::class, 'index'])->name('index.dashboard.ppdb');
        Route::get('/informasi', [PpdbInformasiController::class, 'index'])->name('index.informasi.ppdb');
        Route::post('/master', [PpdbDashboardController::class, 'masterUpdate'])->name('update.master.ppdb');
        Route::post('/informasi', [PpdbInformasiController::class, 'informasiUpdate'])->name('update.informasi.ppdb');
        Route::post('/tanggal-penting', [PpdbInformasiController::class, 'tanggalPentingPost'])->name('post.tanggal-penting.ppdb');
        Route::put('/tanggal-penting/{agenda}', [PpdbInformasiController::class, 'tanggalPentingUpdate'])->name('update.tanggal-penting.ppdb');
        Route::delete('/tanggal-penting/{agenda}', [PpdbInformasiController::class, 'tanggalPentingDelete'])->name('delete.tanggal-penting.ppdb');
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
