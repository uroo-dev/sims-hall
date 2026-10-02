<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan role admin spesifik ke kolom enum 'role' tabel users
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

        // 2. Migrasikan data fitur yang ada ke role user langsung
        if (Schema::hasTable('fiturs')) {
            $fiturs = DB::table('fiturs')->get();
            foreach ($fiturs as $fitur) {
                $newRole = match ($fitur->nama_fitur) {
                    'aula' => 'admin_aula',
                    'master' => 'admin_master',
                    'kesiswaan' => 'admin_kesiswaan',
                    'produk_unggulan' => 'admin_produk',
                    'pklbkk' => 'bkk',
                    default => null,
                };

                if ($newRole) {
                    DB::table('users')
                        ->where('id', $fitur->user_id)
                        ->update(['role' => $newRole]);
                }
            }

            // 3. Hapus tabel fiturs
            Schema::dropIfExists('fiturs');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('fiturs')) {
            Schema::create('fiturs', function (Blueprint $table) {
                $table->id();
                $table->enum('nama_fitur', ['produk_unggulan', 'master', 'pklbkk', 'aula', 'kesiswaan']);
                $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
                $table->timestamps();
            });

            // Kembalikan role admin_aula dll ke tabel fiturs
            $users = DB::table('users')->whereIn('role', [
                'admin_aula', 'admin_master', 'admin_kesiswaan', 'admin_produk', 'admin_produk_unggulan',
            ])->get();

            foreach ($users as $user) {
                $fiturName = match ($user->role) {
                    'admin_aula' => 'aula',
                    'admin_master' => 'master',
                    'admin_kesiswaan' => 'kesiswaan',
                    'admin_produk', 'admin_produk_unggulan' => 'produk_unggulan',
                    default => null,
                };

                if ($fiturName) {
                    DB::table('fiturs')->insert([
                        'user_id' => $user->id,
                        'nama_fitur' => $fiturName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    DB::table('users')->where('id', $user->id)->update(['role' => 'admin']);
                }
            }
        }

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
};
