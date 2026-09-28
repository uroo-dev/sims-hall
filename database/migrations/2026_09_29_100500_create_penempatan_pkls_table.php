<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penempatan_pkls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_pengajuan_id')->constrained('surat_pengajuans')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('dudi_id')->constrained('dudis')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
            $table->enum('status_penempatan', ['pengajuan', 'FIX', 'ditolak'])->default('pengajuan');
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Status penempatan dibaca sangat sering (rekap, landing, chatbot)
            $table->index('status_penempatan');
            $table->index(['siswa_id', 'status_penempatan']);
            $table->index(['dudi_id', 'status_penempatan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penempatan_pkls');
    }
};
