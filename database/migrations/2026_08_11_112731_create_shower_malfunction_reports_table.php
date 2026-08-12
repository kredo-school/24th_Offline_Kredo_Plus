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
        Schema::create('shower_malfunction_reports', function (Blueprint $table) {
            $table->id();
            $table->enum('gender', ['male', 'female']);
            $table->unsignedTinyInteger('shower_number');
            $table->enum('status', ['broken', 'fixed']);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index(['gender', 'shower_number', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shower_malfunction_reports');
    }
};
