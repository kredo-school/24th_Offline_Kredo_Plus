<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * categories テーブル
     *
     * "section" でどのグループ(travel / other / restaurant-cafe など)に
     * 属するカテゴリーかを区別する。
     * 同じ section 内での並び順(id昇順)が、そのままKredoカラーの割り当て順になる。
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('section');      // 例: 'travel', 'other', 'restaurant-cafe'
            $table->string('name');         // 例: 'IT Park'
            $table->string('slug')->unique(); // 例: 'it-park' (URLに使用)
            $table->string('hero_image')->nullable();  // カテゴリートップのバナー画像URL
            $table->text('description')->nullable();   // カテゴリートップの説明文
            $table->unsignedInteger('sort_order')->default(0); // 表示順(色の割り当てにも使用)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
