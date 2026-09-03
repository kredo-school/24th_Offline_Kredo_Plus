<?php

namespace Database\Seeders;

use App\Models\Shower\ShowerReport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ShowerDataSeeder extends Seeder
{
    public function run(): void
    {
        $maleUsers = User::query()
            ->where('gender', 'male')
            ->where('gender_locked', true)
            ->get();

        $femaleUsers = User::query()
            ->where('gender', 'female')
            ->where('gender_locked', true)
            ->get();

        if ($maleUsers->isEmpty()) {
            $this->command?->warn("スキップ: male の登録済みユーザーが見つかりません。");
        }

        if ($femaleUsers->isEmpty()) {
            $this->command?->warn("スキップ: female の登録済みユーザーが見つかりません。");
        }

        foreach (['male', 'female'] as $gender) {
            $users = $gender === 'male' ? $maleUsers : $femaleUsers;

            if ($users->isEmpty()) {
                continue;
            }

            // 故障報告の生成を行わないため、空の配列を渡す
            $this->seedReports($gender, $users, []);
        }
    }

    private function seedReports(string $gender, $users, array $brokenPeriods): void
    {
        $comments = [
            null, null, null,
            'お湯が出るまで時間がかかりました',
            '水圧がとても気持ちよかったです',
            'ちょうど良い温度でした',
            '少しぬるかったです',
            '特に問題ありませんでした',
        ];

        // 過去13日前から今日まで（翌日以降は含まない）
        for ($daysAgo = 13; $daysAgo >= 0; $daysAgo--) {
            $date = Carbon::now()->subDays($daysAgo);
            $postCount = rand(3, 8);

            for ($i = 0; $i < $postCount; $i++) {
                $time = $date->copy()
                    ->setHour(rand(6, 23))
                    ->setMinute(rand(0, 59))
                    ->setSecond(rand(0, 59));

                if ($time->greaterThan(Carbon::now())) {
                    continue;
                }

                // 1〜7の中からランダムにシャワー番号を選ぶ
                $number = rand(1, 7);

                ShowerReport::create([
                    'gender' => $gender,
                    'shower_number' => $number,
                    'user_id' => $users->random()->id,
                    'temperature' => round(rand(20, 100) / 10, 1),
                    'pressure' => round(rand(0, 100) / 10, 1),
                    'comment' => $comments[array_rand($comments)],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}