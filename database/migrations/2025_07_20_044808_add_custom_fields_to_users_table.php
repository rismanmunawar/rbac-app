<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik')->unique()->nullable()->after('id');
            $table->string('alias')->nullable()->after('name');
            $table->string('designation')->nullable()->after('alias');
            $table->string('phone')->nullable()->after('designation');
            $table->string('plant')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nik', 'alias', 'designation', 'phone', 'plant']);
        });
    }
};
