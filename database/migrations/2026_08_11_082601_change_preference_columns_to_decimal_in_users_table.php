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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('preferred_temperature', 3, 1)->default(7.5)->change();
            $table->decimal('preferred_pressure', 3, 1)->default(6.6)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('preferred_temperature')->default(8)->change();
            $table->unsignedTinyInteger('preferred_pressure')->default(8)->change();
        });
    }
};
