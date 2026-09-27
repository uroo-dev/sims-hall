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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjamans')->cascadeOnDelete();
            $table->decimal('jumlah_bayar', 12, 2);
            $table->enum('metode', ['transfer', 'cash'])->nullable();
            $table->string('norek_tujuan', 100)->nullable(); // Contoh: Rekening SMKN 2 Karanganyar
            $table->string('bukti_pembayaran', 255)->nullable();
            $table->dateTime('tanggal_bayar')->nullable();
            $table->enum('status_pembayaran', ['free', 'pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
