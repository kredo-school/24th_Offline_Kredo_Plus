<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 既存の`color`列は「背景色」として扱う(ボタン/バッジの薄い背景)。
     * こちらは新規で「文字色」用の列を追加する(ボタン/バッジの濃い文字色)。
     * どちらも未設定(null)の場合は、これまで通りアプリ側で自動的に色を割り当てる。
     */
    public function up(): void
    {
        Schema::table('main_categories', function (Blueprint $table) {
            $table->string('text_color', 7)->nullable()->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('main_categories', function (Blueprint $table) {
            $table->dropColumn('text_color');
        });
    }
};
