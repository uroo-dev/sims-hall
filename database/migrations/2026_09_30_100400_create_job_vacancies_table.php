<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('perusahaan');
            $table->string('posisi');
            $table->string('lokasi')->nullable();
            $table->date('batas_lamaran')->nullable();
            $table->string('link_pendaftaran')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_aktif')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
