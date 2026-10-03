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
        // 1. Users: index kolom role untuk mempercepat middleware auth & CheckRole
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->index('role', 'users_role_index');
            }
        });

        // 2. Artikels: composite index untuk sorting & filter artikel published
        Schema::table('artikels', function (Blueprint $table) {
            if (Schema::hasTable('artikels')) {
                $table->index(['status', 'published_at'], 'artikels_status_published_at_index');
                $table->index(['kategori_artikel_id', 'status', 'published_at'], 'artikels_kategori_status_pub_index');
            }
        });

        // 3. Dudis: composite index untuk landing page & filter mitra resmi
        Schema::table('dudis', function (Blueprint $table) {
            if (Schema::hasTable('dudis')) {
                $table->index(['tampil_di_landing', 'is_mitra_resmi'], 'dudis_landing_mitra_index');
            }
        });

        // 4. Detail Pembayarans: composite index untuk agregasi laporan keuangan & chart
        Schema::table('detail_pembayarans', function (Blueprint $table) {
            if (Schema::hasTable('detail_pembayarans')) {
                $table->index(['status', 'tipe_pembayaran', 'tanggal_bayar'], 'detail_pembayaran_stat_tipe_tgl_index');
            }
        });

        // 5. Peminjamans: composite index untuk filter status & rentang jadwal serta lookup instansi
        Schema::table('peminjamans', function (Blueprint $table) {
            if (Schema::hasTable('peminjamans')) {
                $table->index(['status', 'tanggal_mulai', 'tanggal_selesai'], 'peminjamans_status_tgl_range_index');
                $table->index(['email_instansi', 'status'], 'peminjamans_email_status_index');
            }
        });

        // 6. Produk: index kode_produk
        Schema::table('produk', function (Blueprint $table) {
            if (Schema::hasTable('produk')) {
                $table->index('kode_produk', 'produk_kode_produk_index');
            }
        });

        // 7. Siswas: index sorting & filter
        Schema::table('siswas', function (Blueprint $table) {
            if (Schema::hasTable('siswas')) {
                $table->index(['jurusan', 'kelas', 'nama'], 'siswas_jurusan_kelas_nama_index');
            }
        });

        // 8. Gurus: index pencarian nama
        Schema::table('gurus', function (Blueprint $table) {
            if (Schema::hasTable('gurus')) {
                $table->index('nama', 'gurus_nama_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_index');
        });

        Schema::table('artikels', function (Blueprint $table) {
            if (Schema::hasTable('artikels')) {
                $table->dropIndex('artikels_status_published_at_index');
                $table->dropIndex('artikels_kategori_status_pub_index');
            }
        });

        Schema::table('dudis', function (Blueprint $table) {
            if (Schema::hasTable('dudis')) {
                $table->dropIndex('dudis_landing_mitra_index');
            }
        });

        Schema::table('detail_pembayarans', function (Blueprint $table) {
            if (Schema::hasTable('detail_pembayarans')) {
                $table->dropIndex('detail_pembayaran_stat_tipe_tgl_index');
            }
        });

        Schema::table('peminjamans', function (Blueprint $table) {
            if (Schema::hasTable('peminjamans')) {
                $table->dropIndex('peminjamans_status_tgl_range_index');
                $table->dropIndex('peminjamans_email_status_index');
            }
        });

        Schema::table('produk', function (Blueprint $table) {
            if (Schema::hasTable('produk')) {
                $table->dropIndex('produk_kode_produk_index');
            }
        });

        Schema::table('siswas', function (Blueprint $table) {
            if (Schema::hasTable('siswas')) {
                $table->dropIndex('siswas_jurusan_kelas_nama_index');
            }
        });

        Schema::table('gurus', function (Blueprint $table) {
            if (Schema::hasTable('gurus')) {
                $table->dropIndex('gurus_nama_index');
            }
        });
    }
};
