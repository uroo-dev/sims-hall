<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah role 'bkk' (Bursa Kerja Khusus / Career Center) ke enum users.
     * Terpisah dari migration create_users_table agar aman untuk DB yang
     * sudah berisi data.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'admin',
                'user',
                'guru',
                'kepala_sekolah',
                'super_admin',
                'super_duper_admin',
                'pelanggan',
                'bkk',
            ])->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'admin',
                'user',
                'guru',
                'kepala_sekolah',
                'super_admin',
                'super_duper_admin',
                'pelanggan',
            ])->change();
        });
    }
};
