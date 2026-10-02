<?php

use App\Http\Controllers\Admin\Ppdb\PpdbDashboardController;
use App\Http\Controllers\Admin\Ppdb\PpdbInformasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Public\Ppdb\PpdbController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ppdb', [PpdbController::class, 'index'])->name('public.ppdb');

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
