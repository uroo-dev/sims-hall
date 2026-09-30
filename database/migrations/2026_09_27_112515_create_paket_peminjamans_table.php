<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_peminjamans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket', 100);
            $table->integer('harga');
            $table->enum('kategori', ['unggulan', 'terjangkau', 'standar_1', 'standar_2', 'standar_3'])->default('terjangkau');
            $table->string('durasi', 50)->default('4 Jam');
            $table->text('fasilitas')->nullable(); // Simpan sebagai comma-separated
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_peminjamans');
    }
};