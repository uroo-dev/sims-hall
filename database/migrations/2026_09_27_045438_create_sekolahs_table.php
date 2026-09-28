<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sekolahs', function (Blueprint $table) {
            $table->id();
            // Profil Sekolah (Landing Page)
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            $table->string('dokumentasi')->nullable();
            
            // Sejarah
            $table->text('sejarah')->nullable();
            
            // Profil Detail
            $table->text('profil_judul');
            $table->text('profil_deskripsi');
            $table->string('profil_dokumentasi', 255);
            
            // Visi & Misi (Dipisah)
            $table->text('visi')->nullable();
            $table->text('misi');
            
            // Kepala Sekolah
            $table->text('sambutan_kepsek')->nullable();
            $table->string('nama_kepsek', 255)->nullable();
            $table->string('foto_kepsek', 255)->nullable();
            
            // Yel-yel
            $table->string('yel_yel', 255)->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sekolahs');
    }
};