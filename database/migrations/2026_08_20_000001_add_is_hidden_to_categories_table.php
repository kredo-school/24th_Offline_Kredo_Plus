<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * eggやSt Ninoのような「隠しカテゴリー」用のフラグ。
     * true のカテゴリーは、Otherページの通常一覧や投稿フォームのカテゴリー選択には出さない。
     * (隠しリンク経由の専用ページだけからアクセスできるようにするため)
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('is_hidden');
        });
    }
};
