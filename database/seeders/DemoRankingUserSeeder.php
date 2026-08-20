<?php

namespace Database\Seeders;

use App\Models\English\StudyLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * プレゼン用のランキング比較デモデータ Seeder。
 *
 * ランキング画面（週間・月間・Total）が見栄えよく比較できるよう、
 * 複数のユーザーと学習ログ(study_logs)を作成する。
 * Masako Yajima が Total XP 最上位（1位）になるよう数値を設定している。
 *
 * 実行方法:
 *   php artisan db:seed --class=Database\\Seeders\\DemoRankingUserSeeder
 */
class DemoRankingUserSeeder extends Seeder
{
    /**
     * activity_type ごとの1回あたりの目安XP（StudyLog::TYPE_* に対応）
     */
    private const ACTIVITY_TYPES = ['typing', 'vocabulary', 'quiz', 'toeic', 'ielts'];

    public function run(): void
    {
        // name => [total_xp, total_study_days]
        // total_xp の降順 = Total ランキングの順位。Masako Yajima を最上位に設定。
        $users = [
            'Masako Yajima'    => ['total_xp' => 5200, 'study_days' => 42],
            'Haruka Fukunaga'  => ['total_xp' => 4300, 'study_days' => 35],
            'Myu Okada'        => ['total_xp' => 3600, 'study_days' => 28],
            'Kazuki Toyama'    => ['total_xp' => 2800, 'study_days' => 22],
            'Kanna Horiuchi'   => ['total_xp' => 1900, 'study_days' => 15],
            'Orina Mannami'    => ['total_xp' => 950,  'study_days' => 8],
        ];

        foreach ($users as $name => $stats) {
            $email = $this->emailFor($name);

            $user = User::firstOrNew(['email' => $email]);
            $user->name     = $name;
            $user->password = $user->exists ? $user->password : Hash::make('password');
            $user->role_id  = User::USER_ROLE_ID;
            $user->save();

            // 既存の学習ログをリセットしてから再生成する（何度実行しても同じ結果になるように）
            StudyLog::where('user_id', $user->id)->delete();

            $dates = $this->buildStudyDates($stats['study_days']);
            $xpPerDay = $this->splitXp($stats['total_xp'], count($dates));

            foreach ($dates as $i => $date) {
                StudyLog::create([
                    'user_id'          => $user->id,
                    'activity_type'    => self::ACTIVITY_TYPES[array_rand(self::ACTIVITY_TYPES)],
                    'activity_id'      => null,
                    'xp_gained'        => $xpPerDay[$i],
                    'duration_seconds' => random_int(300, 1800),
                    'studied_date'     => $date,
                    'created_at'       => $date,
                    'updated_at'       => $date,
                ]);
            }

            $user->total_xp         = $stats['total_xp'];
            $user->study_streak     = min($stats['study_days'], 7);
            $user->last_study_date  = $dates[0] ?? null;
            $user->total_study_time = collect($dates)->count() * random_int(600, 1500);
            $user->save();
        }
    }

    private function emailFor(string $name): string
    {
        $slug = str($name)->lower()->replace(' ', '.')->toString();

        return "{$slug}@example.com";
    }

    /**
     * 直近7日間を必ず含み、残りは当月・過去数ヶ月に散らした学習日の配列（新しい順）を作る。
     *
     * @return array<int, string> Y-m-d 形式の日付配列
     */
    private function buildStudyDates(int $studyDays): array
    {
        $dates = [];
        $today = Carbon::today();

        // 直近7日間（週間ランキング用）
        $recentCount = min($studyDays, 7);
        for ($i = 0; $i < $recentCount; $i++) {
            $dates[] = $today->copy()->subDays($i)->toDateString();
        }

        // 当月の残り（月間ランキング用）
        $remaining = $studyDays - $recentCount;
        $day = 8;
        while ($remaining > 0 && $day <= 27) {
            $dates[] = $today->copy()->subDays($day)->toDateString();
            $day    += random_int(1, 3);
            $remaining--;
        }

        // それでも残る分は過去数ヶ月に散らす（Totalランキング用）
        $day = 40;
        while ($remaining > 0) {
            $dates[] = $today->copy()->subDays($day)->toDateString();
            $day    += random_int(3, 10);
            $remaining--;
        }

        return $dates;
    }

    /**
     * 合計XPを件数分に按分する（多少ランダム性を持たせつつ、最後の要素で端数調整して合計を一致させる）。
     *
     * @return array<int, int>
     */
    private function splitXp(int $totalXp, int $count): array
    {
        if ($count === 0) {
            return [];
        }

        $avg    = intdiv($totalXp, $count);
        $values = [];
        $used   = 0;

        for ($i = 0; $i < $count - 1; $i++) {
            $variance = (int) round($avg * random_int(-20, 20) / 100);
            $value    = max(10, $avg + $variance);
            $values[] = $value;
            $used    += $value;
        }

        // 最後の要素で合計を totalXp に一致させる
        $values[] = max(10, $totalXp - $used);

        return $values;
    }
}
