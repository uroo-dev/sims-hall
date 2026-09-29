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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_peminjaman_id')->constrained('paket_peminjamans')->restrictOnDelete();
            $table->string('nama', 150)->index();
            $table->string('email_instansi', 150)->index();
            $table->dateTime('tanggal_mulai'); // Menggunakan datetime untuk antisipasi jam pemakaian
            $table->dateTime('tanggal_selesai');
            $table->text('catatan')->nullable();
            $table->string('surat_pengantar', 250)->nullable();
            $table->enum('status', ['draft', 'pending', 'approved_1', 'approved_final', 'rejected'])->default('draft')->index();
            $table->timestamps();

            // Index pencarian & validasi rentang jadwal peminjaman
            $table->index('tanggal_mulai');
            $table->index('tanggal_selesai');
            $table->index(['tanggal_mulai', 'tanggal_selesai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
