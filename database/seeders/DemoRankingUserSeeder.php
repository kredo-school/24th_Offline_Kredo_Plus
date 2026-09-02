<?php

namespace Database\Seeders;

use App\Models\English\StudyLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

    /**
     * デモユーザー一覧。
     *
     * AdminSeeder と同じく、動作確認できるようログイン情報（email / 平文 password）を
     * このファイルに記載している。password は初回作成時にランダム生成したもの。
     * avatar は database/seeders/data/avatars/ 配下のファイル名。
     * total_xp の降順 = Total ランキングの順位（Masako Yajima を最上位に設定）。
     */
    private const USERS = [
        [
            'name'       => 'Masako Yajima',
            'email'      => 'masako.yajima@example.com',
            'password'   => 'NHQistny7986',
            'avatar'     => 'masako-yajima.jpg',
            'total_xp'   => 5200,
            'study_days' => 42,
        ],
        [
            'name'       => 'Haruka Fukunaga',
            'email'      => 'haruka.fukunaga@example.com',
            'password'   => 'GWPunbtm5862',
            'avatar'     => 'haruka-fukunaga.jpg',
            'total_xp'   => 4300,
            'study_days' => 35,
        ],
        [
            'name'       => 'Myu Okada',
            'email'      => 'myu.okada@example.com',
            'password'   => 'DAReutwc4867',
            'avatar'     => 'myu-okada.jpg',
            'total_xp'   => 3600,
            'study_days' => 28,
        ],
        [
            'name'       => 'Kazuki Toyama',
            'email'      => 'kazuki.toyama@example.com',
            'password'   => 'ALKdpejw3458',
            'avatar'     => 'kazuki-toyama.jpg',
            'total_xp'   => 2800,
            'study_days' => 22,
        ],
        [
            'name'       => 'Kanna Horiuchi',
            'email'      => 'kanna.horiuchi@example.com',
            'password'   => 'MDEwtkij7629',
            'avatar'     => 'kanna-horiuchi.jpg',
            'total_xp'   => 1900,
            'study_days' => 15,
        ],
        [
            'name'       => 'Orina Mannami',
            'email'      => 'orina.mannami@example.com',
            'password'   => 'FHXmwkzt5924',
            'avatar'     => 'orina-mannami.jpg',
            'total_xp'   => 950,
            'study_days' => 8,
        ],
    ];

    public function run(): void
    {
        foreach (self::USERS as $data) {
            $user = User::firstOrNew(['email' => $data['email']]);
            $user->name     = $data['name'];
            $user->email    = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->role_id  = User::USER_ROLE_ID;
            $user->avatar   = $this->storeAvatar($data['avatar']) ?? $user->avatar;
            $user->save();

            $this->command?->info(sprintf('  %-16s %s / %s', $data['name'], $data['email'], $data['password']));

            // 既存の学習ログをリセットしてから再生成する（何度実行しても同じ結果になるように）
            StudyLog::where('user_id', $user->id)->delete();

            $dates = $this->buildStudyDates($data['study_days']);
            $xpPerDay = $this->splitXp($data['total_xp'], count($dates));

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

            $user->total_xp         = $data['total_xp'];
            $user->study_streak     = min($data['study_days'], 7);
            $user->last_study_date  = $dates[0] ?? null;
            $user->total_study_time = collect($dates)->count() * random_int(600, 1500);
            $user->save();
        }
    }

    /**
     * database/seeders/data/avatars/ の画像を public ディスク（storage/app/public/avatars/）へ
     * コピーし、users.avatar に保存する相対パスを返す。画像が無ければ null。
     */
    private function storeAvatar(string $filename): ?string
    {
        $source = database_path('seeders/data/avatars/' . $filename);
        if (! is_file($source)) {
            $this->command?->warn("  avatar not found: {$source}");

            return null;
        }

        $path = 'avatars/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($source));

        return $path;
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
