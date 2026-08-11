<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * sections テーブルを main_categories という名前に変更する。
     * テーブルの中身(データ・ID)はそのまま引き継がれる。
     */
    public function up(): void
    {
        Schema::rename('sections', 'main_categories');
    }

    public function down(): void
    {
        Schema::rename('main_categories', 'sections');
    }
};
