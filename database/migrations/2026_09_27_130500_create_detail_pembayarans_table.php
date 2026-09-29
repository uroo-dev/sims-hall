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
        Schema::create('detail_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_id')->constrained('pembayarans')->cascadeOnDelete();
            $table->string('kode_transaksi', 50)->unique()->nullable(); // Contoh: TRX-DP-202609-001
            $table->enum('tipe_pembayaran', ['dp', 'pelunasan', 'lunas_langsung', 'refund'])->index(); // Cicilan 1 / DP atau Cicilan 2 / Pelunasan
            $table->decimal('jumlah_bayar', 12, 2); // Nominal transfer pada cicilan ini
            $table->enum('metode', ['transfer', 'cash'])->default('transfer');
            $table->string('bank_tujuan', 100)->nullable(); // Bank tujuan sekolah (misal: Bank Jateng)
            $table->string('norek_tujuan', 100)->nullable();
            $table->string('bank_pengirim', 100)->nullable(); // Bank asal user (misal: BCA, Mandiri)
            $table->string('norek_pengirim', 100)->nullable();
            $table->string('atas_nama_pengirim', 150)->nullable();
            $table->string('bukti_pembayaran', 255)->nullable(); // Path upload file bukti transfer
            $table->dateTime('tanggal_bayar')->nullable()->index(); // Waktu transfer/upload
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending')->index();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('diverifikasi_pada')->nullable();
            $table->text('catatan')->nullable(); // Catatan admin atau alasan tolak
            $table->timestamps();

            // Index pencarian
            $table->index(['pembayaran_id', 'tipe_pembayaran']);
            $table->index(['pembayaran_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembayarans');
    }
};
