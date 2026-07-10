<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * posts テーブル
     *
     * みんなで合意した設計に基づく(dbdiagramの案から下記2点を調整):
     * - comment → description に変更(comments テーブルと名前が被るのを避けるため)
     * - category_post(多対多の中間テーブル)は使わず、category_id で1投稿=1カテゴリーに統一
     * - map_query を追加(地球儀/マップボタン用)
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();     // storage/posts/ 以下に保存される画像パス
            $table->decimal('price', 10, 2)->nullable();
            $table->string('map_query')->nullable();  // Googleマップ検索用のキーワード(将来lat/lngに置き換えも可)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
