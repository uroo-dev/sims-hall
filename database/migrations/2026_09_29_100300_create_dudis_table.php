<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dudis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dudi');
            $table->text('alamat');
            $table->string('kota');
            $table->string('bidang_usaha');
            $table->string('kontak_person')->nullable();
            $table->string('no_hp')->nullable();
            $table->unsignedInteger('kuota_maksimal')->default(0);
            $table->boolean('is_mitra_resmi')->default(false);
            $table->boolean('tampil_di_landing')->default(false);
            $table->timestamps();

            $table->index('is_mitra_resmi');
            $table->index('tampil_di_landing');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dudis');
    }
};
