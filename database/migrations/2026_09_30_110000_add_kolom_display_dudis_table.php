<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Rekonsiliasi tabel `dudis` dari dua cabang.
 *
 * Dua developer membuat tabel dengan nama sama untuk konsep yang sama
 * (mitra industri / DUDI):
 *
 *   -_branch `uroo`_ : `2026_09_29_100300_create_dudis_table.php`
 *     Kolom operasional PKL: nama_dudi, alamat, kota, bidang_usaha,
 *     kontak_person, no_hp, kuota_maksimal, is_mitra_resmi,
 *     tampil_di_landing. Dipakai 16 file (seluruh modul PKL & BKK).
 *
 *   -_branch `dapin`_ : `2026_09_27_112512_create_dudis_table.php`
 *     Kolom display saja: nama, jurusan_id, program_1..3, deskripsi, logo.
 *     Dipakai 6 file (landing page + DataMasterSeeder).
 *
 * Kedua migration memakai timestamp berbeda sehingga keduanya akan dijalankan
 * dan yang kedua gagal dengan "Table 'dudis' already exists". Karena tabel
 * `uroo` jauh lebih dipakai, tabel itu yang dipertahankan, dan migration
 * `dapin` dihapus. Kolom display miliknya ditambahkan lewat migration ini
 * supaya landing page-nya tetap bisa dibaca.
 *
 * Kolom `nama` sengaja TIDAK dibuat: accessor `nama` pada model Dudi memetakan
 * ke `nama_dudi`, sehingga tidak ada data yang terduplikasi.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('dudis')) {
            return;
        }

        Schema::table('dudis', function (Blueprint $table) {
            // Nama file aset logo mitra di public/assets.
            $table->string('logo')->nullable()->after('nama_dudi');

            // Proficiency / program studi yang relevan untuk tiap mitra.
            $table->string('program_1')->nullable()->after('logo');
            $table->string('program_2')->nullable()->after('program_1');
            $table->string('program_3')->nullable()->after('program_2');

            // Keterangan singkat untuk kartu di landing page.
            $table->text('deskripsi')->nullable()->after('program_3');

            // Jurusan asal mitra (opsional, tabel jurusans dibuat di 09_27).
            //
            // Sengaja TIDAK memakai foreignId()->constrained(): SQLite tidak
            // bisa drop kolom yang masih direferensikan di definisi foreign key,
            // sehingga migrate:rollback / migrate:refresh akan gagal dengan
            // "unknown column in foreign key definition". Karena nilai kolom
            // ini kosong di seluruh seeder dan tidak dipakai view mana pun,
            // relasi/logika tetap condemning ke tabel `jurusans` lewat kolom
            // nullable tanpa constraint.
            $table->unsignedBigInteger('jurusan_id')->nullable()->after('deskripsi');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('dudis')) {
            return;
        }

        Schema::table('dudis', function (Blueprint $table) {
            $columns = array_values(array_filter(
                ['jurusan_id', 'deskripsi', 'program_3', 'program_2', 'program_1', 'logo'],
                fn (string $column): bool => Schema::hasColumn('dudis', $column)
            ));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
