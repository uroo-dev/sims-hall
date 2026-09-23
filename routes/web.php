<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PklBkkDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Public.landing');
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

    Route::get('/dashboard/pkl-bkk', [PklBkkDashboardController::class, 'index'])
        ->middleware('role:admin,super_admin,super_duper_admin')
        ->name('dashboard.pkl');

    Route::get('/dashboard/pkl-bkk/lowongan', [PklBkkDashboardController::class, 'loker'])
        ->middleware('role:admin,super_admin,super_duper_admin')
        ->name('pklbkk.loker');

    Route::get('/dashboard/pkl-bkk/lowongan/tambah', [PklBkkDashboardController::class, 'lokerForm'])
        ->middleware('role:admin,super_admin,super_duper_admin')
        ->name('pklbkk.loker.create');

    Route::get('/dashboard/pkl-bkk/lowongan/edit', [PklBkkDashboardController::class, 'lokerForm'])
        ->middleware('role:admin,super_admin,super_duper_admin')
        ->name('pklbkk.loker.edit');

    Route::get('/dashboard/pkl-bkk/pelamar', [PklBkkDashboardController::class, 'pelamar'])
        ->middleware('role:admin,super_admin,super_duper_admin')
        ->name('pklbkk.pelamar');

    Route::get('/dashboard/pkl-bkk/tempat-pkl', [PklBkkDashboardController::class, 'tempat'])
        ->middleware('role:admin,super_admin,super_duper_admin')
        ->name('pklbkk.tempat');

    Route::get('/dashboard/pkl-bkk/jurnal', [PklBkkDashboardController::class, 'jurnal'])
        ->middleware('role:admin,super_admin,super_duper_admin')
        ->name('pklbkk.jurnal');

    Route::get('/dashboard/pkl-bkk/nilai', [PklBkkDashboardController::class, 'nilai'])
        ->middleware('role:admin,super_admin,super_duper_admin')
        ->name('pklbkk.nilai');
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
