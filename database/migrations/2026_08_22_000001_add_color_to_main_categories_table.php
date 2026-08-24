<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * メインカテゴリーの色を管理画面から手動で選べるようにするためのカラム。
     * null のままなら、これまで通り MainCategory::color() の自動割り当てロジック
     * (固定4色 + 5個目以降はパレットを順番に割り当て)にフォールバックする。
     */
    public function up(): void
    {
        Schema::table('main_categories', function (Blueprint $table) {
            $table->string('color', 7)->nullable()->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('main_categories', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
