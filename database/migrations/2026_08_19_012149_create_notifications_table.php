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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->enum('gender', ['male', 'female']);
            $table->enum('type', ['capacity_full', 'capacity_vacant', 'malfunction_broken', 'malfunction_fixed']);
            $table->unsignedTinyInteger('shower_number')->nullable();
            $table->text('message');
            $table->timestamps();

            $table->index(['gender', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
