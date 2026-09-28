<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_pengajuans', function (Blueprint $table) {
            $table->id();
            // Format: 421/BKK/{tahun}/{index} - unique per tahun
            $table->string('nomor_surat')->unique();
            $table->foreignId('dudi_id')->constrained('dudis')->cascadeOnDelete();
            $table->date('tanggal_surat');
            $table->date('tgl_mulai_pkl');
            $table->date('tgl_selesai_pkl');
            $table->string('file_pdf_path')->nullable();
            $table->timestamps();

            $table->index('tanggal_surat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_pengajuans');
    }
};
