<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'admin',
                'admin_aula',
                'admin_master',
                'admin_kesiswaan',
                'admin_produk',
                'admin_produk_unggulan',
                'admin_ppdb',
                'admin_pklbkk',
                'admin_sekolah',
                'super_admin',
                'super_duper_admin',
                'user',
                'guru',
                'kepala_sekolah',
                'organisasi',
                'instansi_luar_terikat',
                'instansi_luar',
                'pelanggan',
                'bkk',
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'admin',
                'admin_aula',
                'admin_master',
                'admin_kesiswaan',
                'admin_produk',
                'admin_produk_unggulan',
                'admin_ppdb',
                'admin_pklbkk',
                'super_admin',
                'super_duper_admin',
                'user',
                'guru',
                'kepala_sekolah',
                'organisasi',
                'instansi_luar_terikat',
                'instansi_luar',
                'pelanggan',
                'bkk',
            ])->change();
        });
    }
};
