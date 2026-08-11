<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * sections テーブル
     *
     * 「メインカテゴリーそのもの」(Carinderia / Restaurant&Cafe / Travel / Other、
     * および今後アドミンが追加する5個目以降)を表すテーブル。
     *
     * categories テーブルとは別物: categoriesは各メインカテゴリーの「中の
     * サブカテゴリー」(SIM/Hospital、IT Park/North Areaなど)を管理する場所で、
     * このsectionsテーブルは各ページのヒーロー画像・タイトル・説明文と、
     * メインカテゴリー自体の一覧を管理する場所。
     */
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // 例: 'carinderia', 'restaurant-cafe'（ルート名・categories.sectionと一致させる）
            $table->string('name');          // 例: 'Carinderia'（ヒーローのタイトルに表示）
            $table->string('hero_image')->nullable(); // ヒーローのバナー画像URL
            $table->text('description')->nullable();  // ヒーローの説明文
            $table->unsignedInteger('sort_order')->default(0); // 表示順（ホーム画面のボタン順など）
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
