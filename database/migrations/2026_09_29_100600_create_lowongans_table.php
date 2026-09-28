<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dudi_id')->nullable()->constrained('dudis')->nullOnDelete();
            $table->string('nama_perusahaan');
            $table->string('posisi');
            $table->enum('tipe', ['Pekerjaan', 'Magang'])->default('Pekerjaan');
            $table->string('jurusan_sesuai')->default('Semua Jurusan');
            $table->text('deskripsi');
            $table->string('link_daftar');
            $table->date('deadline');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Career Center publik: filter is_active + deadline
            $table->index(['is_active', 'deadline']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongans');
    }
};
