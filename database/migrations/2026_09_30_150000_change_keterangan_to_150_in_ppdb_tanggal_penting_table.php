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
        Schema::table('ppdb_tanggal_penting', function (Blueprint $table) {
            $table->string('keterangan', 150)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_tanggal_penting', function (Blueprint $table) {
            $table->string('keterangan', 20)->change();
        });
    }
};
