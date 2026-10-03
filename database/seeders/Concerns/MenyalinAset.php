<?php

namespace Database\Seeders\Concerns;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Membantu seeder menempatkan gambar ke disk `public` tanpa bergantung pada
 * jaringan. Sumber gambar selalu file yang sudah ada di `public/assets`, lalu
 * disalin ke `storage/app/public` dan dikembalikan sebagai path relatif yang
 * bisa disimpan di kolom gambar pada database.
 */
trait MenyalinAset
{
    /**
     * Salin gambar dari `public/assets/...` ke disk `public`.
     *
     * Path `dummy/dummy.jpg` yang dikembalikan bisa langsung dipakai sebagai
     * nilai kolom `gambar`, karena `Storage::disk('public')->url()` akan
     * menghasilkan `/storage/dummy/dummy.jpg`.
     *
     * Mengembalikan null bila file sumber tidak ada, sehingga seeder tetap
     * aman dijalankan pada repo yang tidak menyertakan aset tersebut.
     */
    protected function salinKeStoragePublik(string $sumberRelatif, ?string $namaTujuan = null): ?string
    {
        $sumber = public_path(ltrim($sumberRelatif, '/'));

        if (! is_file($sumber) || ! is_readable($sumber)) {
            return null;
        }

        $namaTujuan ??= trim(Str::after($sumberRelatif, 'assets/'), '/');

        if ($namaTujuan === '') {
            $namaTujuan = basename($sumber);
        }

        Storage::disk('public')->putFileAs(
            dirname($namaTujuan),
            new File($sumber),
            basename($namaTujuan)
        );

        return $namaTujuan;
    }
}
