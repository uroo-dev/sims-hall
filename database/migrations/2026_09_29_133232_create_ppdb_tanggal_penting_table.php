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
        Schema::create('ppdb_tanggal_penting', function (Blueprint $table) {
            $table->id();
            $table->string('nama_agenda', 100);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('keterangan', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_tanggal_penting');
    }
};
