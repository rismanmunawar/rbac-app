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
            $table->string('action');
            $table->string('description')->nullable();
            $table->json('properties')->nullable(); // before/after data
            $table->nullableMorphs('subject'); // subject_type & subject_id (misalnya: User, Role, Permission)
            $table->foreignId('causer_id')->nullable()->constrained('users')->nullOnDelete(); // pelaku
            $table->string('ip_address')->nullable();
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
