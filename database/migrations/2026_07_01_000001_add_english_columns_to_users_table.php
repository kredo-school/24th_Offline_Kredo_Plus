<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * users テーブルへの英語学習用カラム追加
 *
 * 追加カラム:
 *   - study_streak    : 連続学習日数（StreakService が更新）
 *   - last_study_date : 最後に学習した日（streak 計算の基準日）
 *   - total_study_time: 累積学習時間（秒）。StudyLogService が increment する
 *
 * 移行元の avatar/introduction カラム変更は、Kredo-plus の users テーブルに
 * 該当カラムが存在しないため対象外としています。
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('study_streak')->default(0)->after('total_xp')
                  ->comment('連続学習日数');
            $table->date('last_study_date')->nullable()->after('study_streak')
                  ->comment('最後に学習した日（StreakService が参照）');
            $table->unsignedInteger('total_study_time')->default(0)->after('last_study_date')
                  ->comment('累積学習時間（秒）');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['study_streak', 'last_study_date', 'total_study_time']);
        });
    }
};
