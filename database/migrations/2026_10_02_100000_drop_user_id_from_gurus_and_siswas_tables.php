<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('gurus', 'user_id')) {
            Schema::table('gurus', function (Blueprint $table) {
                if (DB::getDriverName() === 'sqlite') {
                    $table->dropConstrainedForeignId('user_id');
                } else {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
            });
        }

        if (Schema::hasColumn('siswas', 'user_id')) {
            Schema::table('siswas', function (Blueprint $table) {
                if (DB::getDriverName() === 'sqlite') {
                    $table->dropConstrainedForeignId('user_id');
                } else {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('gurus', 'user_id')) {
            Schema::table('gurus', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            });
        }

        if (! Schema::hasColumn('siswas', 'user_id')) {
            Schema::table('siswas', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            });
        }
    }
};
