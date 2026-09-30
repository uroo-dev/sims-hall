<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk_unggulans', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 250);
            $table->text('deskripsi')->nullable();
            $table->string('dokumentasi')->nullable();
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusans')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_unggulans');
    }
};