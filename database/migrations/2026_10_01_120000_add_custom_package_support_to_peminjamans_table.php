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
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->foreignId('paket_peminjaman_id')->nullable()->change();
            $table->boolean('is_custom')->default(false)->after('paket_peminjaman_id');
            $table->decimal('harga_custom', 12, 2)->nullable()->after('is_custom');
        });

        Schema::create('peminjaman_facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjamans')->cascadeOnDelete();
            $table->foreignId('facility_id')->constrained('facilities')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['peminjaman_id', 'facility_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_facilities');

        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn(['is_custom', 'harga_custom']);
            $table->foreignId('paket_peminjaman_id')->nullable(false)->change();
        });
    }
};
