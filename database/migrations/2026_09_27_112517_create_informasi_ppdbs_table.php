<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informasi_ppdbs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppdb_id')->nullable()->constrained('ppdbs')->onDelete('cascade');
            $table->string('nama_agenda', 255);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->string('keterangan', 100)->nullable();
            $table->text('persyaratan')->nullable();
            $table->string('daya_tampung', 100)->nullable();
            $table->string('dokumentasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasi_ppdbs');
    }
};