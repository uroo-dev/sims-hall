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
            $table->foreignId('peminjaman_id')->unique()->constrained('peminjamans')->cascadeOnDelete();
            $table->string('kode_pembayaran', 50)->unique()->nullable(); // Contoh: INV-202609-0001
            $table->decimal('total_tagihan', 12, 2); // Total biaya paket sewa
            $table->decimal('total_terbayar', 12, 2)->default(0); // Akumulasi cicilan yang sudah diverifikasi
            $table->decimal('sisa_tagihan', 12, 2)->default(0); // Sisa nominal yang belum dilunasi
            $table->enum('status_pembayaran', ['pending', 'partial', 'lunas', 'free', 'rejected'])->default('pending')->index();
            $table->dateTime('jatuh_tempo_pelunasan')->nullable()->index(); // Batas waktu transfer pelunasan
            $table->text('catatan')->nullable(); // Keterangan tambahan tagihan
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
