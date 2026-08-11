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
        Schema::create('shower_reports', function (Blueprint $table) {
            $table->id();
            $table->enum('gender', ['male', 'female']);
            $table->unsignedTinyInteger('shower_number'); // 1〜7
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('temperature', 3, 1); // 0.0〜10.0
            $table->decimal('pressure', 3, 1);    // 0.0〜10.0
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
        Schema::dropIfExists('shower_reports');
    }
};
