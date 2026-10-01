<?php

use App\Http\Controllers\Api\PklPublicApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API - Modul PKL & BKK
|--------------------------------------------------------------------------
|
| Dikonsumsi oleh Modul 5:
| - Landing Page  : daftar DUDI mitra + info lowongan kerja
| - Chatbot AI    : rekap angka PKL, status PKL per siswa
|
| Semua endpoint bersifat publik (tanpa auth) karena hanya mengekspos data
| yang sudah di-ACC BKK. Rate limiting dipakai agar tidak bisa di-abuse.
|
*/
Route::prefix('pkl')->name('api.pkl.')->middleware('throttle:60,1')->group(function () {
    // Daftar DUDI yang sudah di-ACC tayang di Landing Page (+ siswa FIX)
    Route::get('/dudi', [PklPublicApiController::class, 'dudi'])->name('dudi');

    // Career Center: lowongan aktif, bisa filter jurusan & tipe
    Route::get('/lowongan', [PklPublicApiController::class, 'lowongan'])->name('lowongan');

    // Ringkasan angka PKL (untuk jawaban chatbot)
    Route::get('/rekap', [PklPublicApiController::class, 'rekap'])->name('rekap');

    // Status PKL satu siswa berdasarkan NIS
    Route::get('/siswa/{nis}', [PklPublicApiController::class, 'siswa'])->name('siswa');
});
