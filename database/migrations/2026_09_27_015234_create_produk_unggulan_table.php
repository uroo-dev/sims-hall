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
        Schema::create('produk_unggulan', function (Blueprint $table) {
            $table->id('produk_unggulanID');
            $table->string('judul', 255);
            $table->text('deskripsi');
            $table->string('dokumentasi', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_unggulan');
    }
};
