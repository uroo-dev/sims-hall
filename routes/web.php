<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProdukUnggulanController;
use App\Http\Controllers\Public\ProdukUnggulanController as PublicProdukUnggulanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing Page Publik
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicProdukUnggulanController::class, 'index'])->name('home');
Route::get('/produk-unggulan-publik', [PublicProdukUnggulanController::class, 'index'])
    ->name('public.produk-unggulan');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:6,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::get('/produk', [DashboardController::class, 'index'])
        ->middleware('role:admin_produk,super_admin,super_duper_admin')
        ->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Produk Unggulan
|--------------------------------------------------------------------------
| Catatan: tambahkan middleware 'adminFitur:produk_unggulan' pada group di
| bawah bila setiap admin hanya boleh mengakses fitur yang terdaftar di
| tabel `fiturs` (admin super perlu baris fitur agar tidak 403).
*/

Route::middleware(['auth', 'role:admin_produk,super_admin,super_duper_admin'])->prefix('produk-unggulan')->group(function () {
    Route::get('/', [ProdukUnggulanController::class, 'index'])->name('produk-unggulan.index');
    Route::put('/', [ProdukUnggulanController::class, 'update'])->name('produk-unggulan.update');
    Route::delete('/', [ProdukUnggulanController::class, 'destroy'])->name('produk-unggulan.destroy');

    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
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
