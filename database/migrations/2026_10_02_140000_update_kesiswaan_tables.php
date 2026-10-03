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
        // Pastikan tabel kesiswaan ada
        if (! Schema::hasTable('kesiswaan')) {
            Schema::create('kesiswaan', function (Blueprint $table) {
                $table->id('kesiswaanID');
                $table->string('judul')->nullable();
                $table->text('deskripsi')->nullable();
                $table->text('dokumentasi')->nullable();
                $table->timestamps();
            });
        }

        // Pastikan tabel ekstrakurikuler ada & kolom lengkap
        if (! Schema::hasTable('ekstrakurikuler')) {
            Schema::create('ekstrakurikuler', function (Blueprint $table) {
                $table->id('ekstrakurikulerID');
                $table->string('nama');
                $table->string('sekolah')->nullable()->default('SMKN 2 Karanganyar');
                $table->text('deskripsi')->nullable();
                $table->string('logo')->nullable();
                $table->string('dokumentasi')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('ekstrakurikuler', function (Blueprint $table) {
                if (! Schema::hasColumn('ekstrakurikuler', 'sekolah')) {
                    $table->string('sekolah')->nullable()->default('SMKN 2 Karanganyar')->after('nama');
                }
                if (! Schema::hasColumn('ekstrakurikuler', 'deskripsi')) {
                    $table->text('deskripsi')->nullable()->after('sekolah');
                }
                if (! Schema::hasColumn('ekstrakurikuler', 'dokumentasi')) {
                    $table->string('dokumentasi')->nullable()->after('logo');
                }
            });
        }

        // Pastikan tabel tata_tertib ada & kolom lengkap
        if (! Schema::hasTable('tata_tertib')) {
            Schema::create('tata_tertib', function (Blueprint $table) {
                $table->id('tata_tertibID');
                $table->string('judul');

                $table->text('deskripsi')->nullable();
                $table->string('file_pdf')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('tata_tertib', function (Blueprint $table) {
                if (! Schema::hasColumn('tata_tertib', 'judul')) {
                    $table->string('judul')->nullable()->after('tata_tertibID');
                }
                if (! Schema::hasColumn('tata_tertib', 'file_pdf')) {
                    $table->string('file_pdf')->nullable()->after('deskripsi');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe rollback
        if (Schema::hasTable('ekstrakurikuler')) {
            Schema::table('ekstrakurikuler', function (Blueprint $table) {
                if (Schema::hasColumn('ekstrakurikuler', 'sekolah')) {
                    $table->dropColumn('sekolah');
                }
                if (Schema::hasColumn('ekstrakurikuler', 'deskripsi')) {
                    $table->dropColumn('deskripsi');
                }
                if (Schema::hasColumn('ekstrakurikuler', 'dokumentasi')) {
                    $table->dropColumn('dokumentasi');
                }
            });
        }

        if (Schema::hasTable('tata_tertib')) {
            Schema::table('tata_tertib', function (Blueprint $table) {
                if (Schema::hasColumn('tata_tertib', 'judul')) {
                    $table->dropColumn('judul');
                }
                if (Schema::hasColumn('tata_tertib', 'file_pdf')) {
                    $table->dropColumn('file_pdf');
                }
            });
        }
    }
};
