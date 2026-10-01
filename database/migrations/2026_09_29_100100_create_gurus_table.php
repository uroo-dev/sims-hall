<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('nip')->nullable()->unique();
            $table->string('nama');
            $table->string('jurusan');
            $table->string('no_hp')->nullable();
            $table->timestamps();

            $table->index('jurusan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
