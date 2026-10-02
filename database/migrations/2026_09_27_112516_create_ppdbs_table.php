<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdbs', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 250);
            $table->text('deskripsi')->nullable();
            $table->text('persyaratan')->nullable();
            $table->string('dokumentasi')->nullable();
            $table->string('tahun_ajaran', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdbs');
    }
};
