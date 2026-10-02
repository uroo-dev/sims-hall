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
        Schema::create('paket_peminjamans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket', 150)->nullable();
            $table->enum('kategori', ['unggulan', 'terjangkau', 'standar 1', 'standar 2', 'standar 3'])->index();
            $table->decimal('harga', 12, 2); // Diubah dari varchar ke decimal untuk kemudahan kalkulasi
            $table->decimal('harga_dp', 12, 2)->nullable(); // Diubah dari varchar ke decimal untuk kemudahan kalkulasi
            $table->text('deskripsi')->nullable();
            $table->string('durasi', 50)->default('4 Jam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_peminjamans');
    }
};
