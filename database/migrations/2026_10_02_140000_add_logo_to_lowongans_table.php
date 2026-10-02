<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('lowongans') && ! Schema::hasColumn('lowongans', 'logo')) {
            Schema::table('lowongans', function (Blueprint $table) {
                $table->string('logo')->nullable()->after('posisi');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('lowongans') && Schema::hasColumn('lowongans', 'logo')) {
            Schema::table('lowongans', function (Blueprint $table) {
                $table->dropColumn('logo');
            });
        }
    }
};
