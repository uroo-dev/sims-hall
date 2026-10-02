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
        Schema::create('payment_configurations', function (Blueprint $table) {
            $table->id();

            // Rekening Bank Utama
            $table->string('bank_utama', 100);
            $table->string('norek_utama', 100);
            $table->string('atas_nama_utama', 150);

            // Rekening Alternatif 1
            $table->string('bank_alternatif_1', 100)->nullable();
            $table->string('norek_alternatif_1', 100)->nullable();
            $table->string('atas_nama_alternatif_1', 150)->nullable();

            // Rekening Alternatif 2
            $table->string('bank_alternatif_2', 100)->nullable();
            $table->string('norek_alternatif_2', 100)->nullable();
            $table->string('atas_nama_alternatif_2', 150)->nullable();

            // QRIS
            $table->string('qris_image', 255)->nullable();
            $table->string('qris_merchant', 150)->nullable();

            // Batas Waktu / Jatuh Tempo Pembayaran (dalam satuan jam)
            $table->unsignedInteger('jatuh_tempo_dp_jam')->default(24);
            $table->unsignedInteger('jatuh_tempo_pelunasan_jam')->default(48);

            // Keterangan / Instruksi Tambahan
            $table->text('instruksi_pembayaran')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_configurations');
    }
};
