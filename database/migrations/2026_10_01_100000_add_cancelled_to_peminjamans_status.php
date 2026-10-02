<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('draft', 'pending', 'approved_1', 'approved_final', 'rejected', 'cancelled') NOT NULL DEFAULT 'draft'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('draft', 'pending', 'approved_1', 'approved_final', 'rejected') NOT NULL DEFAULT 'draft'");
        }
    }
};
