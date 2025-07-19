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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('causer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // contoh: 'create_user'
            $table->string('description')->nullable(); // penjelasan opsional
            $table->json('properties')->nullable(); // data before/after
            $table->string('subject_type')->nullable(); // model yang diubah
            $table->unsignedBigInteger('subject_id')->nullable(); // id dari model yang diubah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
