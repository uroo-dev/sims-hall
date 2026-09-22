<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// contoh route
// Keterangan ROUTE (Route Users)
// Route::get('/namaroute', [namacontroller::class, 'index'])->name('/namaroute/index');

// contoh route dengan middleware role
// Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
//     Route::get('/admin', [namacontroller::class, 'index'])->name('admin.index');
// });
