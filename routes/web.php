<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataMasterDashboardController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Halaman Publik
Route::get('/', [PublicController::class, 'landing'])->name('landing');

Route::get('/profil', function () {
    $sekolah = \App\Models\Sekolah::first() ?? new \App\Models\Sekolah();
    return view('Public.profil', compact('sekolah'));
})->name('profil');

// Autentikasi (Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:6,1');
});

// Area Terproteksi (Harus Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    // --- DASHBOARD UTAMA ---
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,super_admin,super_duper_admin')
        ->name('dashboard');

    // --- DASHBOARD DATA MASTER ---
    Route::prefix('dashboard/data-master')->group(function () {
        Route::get('/', [DataMasterDashboardController::class, 'index'])
            ->middleware('role:admin,super_admin,super_duper_admin')
            ->name('datamaster.index');

        // Data Sekolah (Update)
        Route::get('/sekolah', [DataMasterDashboardController::class, 'editSekolah'])
            ->middleware('role:admin,super_admin,super_duper_admin')
            ->name('datamaster.sekolah.edit');

        Route::put('/sekolah', [DataMasterDashboardController::class, 'updateSekolah'])
            ->middleware('role:admin,super_admin,super_duper_admin')
            ->name('datamaster.sekolah.update');

        // Users (CRUD)
        Route::get('/users', [DataMasterDashboardController::class, 'users'])
            ->middleware('role:admin,super_admin,super_duper_admin')
            ->name('datamaster.users');
        Route::post('/users', [DataMasterDashboardController::class, 'storeUser'])
            ->middleware('role:admin,super_admin,super_duper_admin')
            ->name('datamaster.users.store');
        Route::put('/users/{id}', [DataMasterDashboardController::class, 'updateUser'])
            ->middleware('role:admin,super_admin,super_duper_admin')
            ->name('datamaster.users.update');
        Route::delete('/users/{id}', [DataMasterDashboardController::class, 'destroyUser'])
            ->middleware('role:admin,super_admin,super_duper_admin')
            ->name('datamaster.users.destroy');
    });


});