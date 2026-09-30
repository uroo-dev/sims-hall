<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BkkController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PklController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('Public.landing');
})->name('landing');

Route::get('/profil', function () {
    return view('Public.profil');
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

Route::get('/layanan-peminjaman', function () {
    return view('Public.layanan-peminjaman');
})->name('layanan-peminjaman');

Route::get('/pkl-bkk', function () {
    return view('Public.pkl-bkk');
})->name('pkl-bkk');

Route::get('/registrasi', function () {
    return view('Public.registrasi');
})->name('registrasi');

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
});

/*
|--------------------------------------------------------------------------
| Admin - Modul PKL & BKK
|
| Role yang boleh akses: bkk, admin, super_admin, super_duper_admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:bkk,admin,super_admin,super_duper_admin'])
    ->prefix('dashboard/pkl-bkk')
    ->name('pkl.')
    ->group(function () {

        // --- BKK: Career Center & Master DUDI ---
        Route::get('/', [BkkController::class, 'index'])->name('dashboard');

        Route::get('/dudi', [BkkController::class, 'dudi'])->name('dudi.index');
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
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,super_admin,super_duper_admin')
        ->name('dashboard');
});
