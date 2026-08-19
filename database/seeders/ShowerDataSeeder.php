<?php

namespace Database\Seeders;

use App\Models\Shower\ShowerMalfunctionReport;
use App\Models\Shower\ShowerReport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ShowerDataSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['male', 'female'] as $gender) {
            $users = User::query()
                ->where('gender', $gender)
                ->where('gender_locked', true)
                ->get();

            if ($users->isEmpty()) {
                $this->command?->warn("スキップ: {$gender} の登録済みユーザーが見つかりません。");
                continue;
            }

            $brokenPeriods = $this->seedMalfunctions($gender, $users);
            $this->seedReports($gender, $users, $brokenPeriods);
        }
    }

    /**
     * @return array<int, array{number:int, start:Carbon, end:Carbon}>
     */
    private function seedMalfunctions(string $gender, $users): array
    {
        $comments = [
            'お湯が出ません',
            '水圧が弱すぎます',
            '水が止まりません',
            null,
        ];

        $brokenNumbers = collect(range(1, 7))->random(rand(2, 3));
        $periods = [];

        foreach ($brokenNumbers as $number) {
            $daysAgo = rand(2, 12);
            $brokenTime = Carbon::now()->subDays($daysAgo)->setHour(rand(7, 20))->setMinute(rand(0, 59));

            ShowerMalfunctionReport::create([
                'gender' => $gender,
                'shower_number' => $number,
                'status' => 'broken',
                'user_id' => $users->random()->id,
                'comment' => $comments[array_rand($comments)],
                'created_at' => $brokenTime,
                'updated_at' => $brokenTime,
            ]);

            $fixedTime = null;

            if (rand(1, 10) <= 6) {
                $candidate = $brokenTime->copy()->addDays(rand(1, 3))->setHour(rand(9, 18));

                if ($candidate->lessThan(Carbon::now())) {
                    $fixedTime = $candidate;

                    ShowerMalfunctionReport::create([
                        'gender' => $gender,
                        'shower_number' => $number,
                        'status' => 'fixed',
                        'user_id' => $users->random()->id,
                        'comment' => null,
                        'created_at' => $fixedTime,
                        'updated_at' => $fixedTime,
                    ]);
                }
            }

            // 修理完了していなければ「今も故障中」として、期間の終わりを現在時刻にする
            $periods[] = [
                'number' => $number,
                'start' => $brokenTime,
                'end' => $fixedTime ?? Carbon::now(),
            ];
        }

        return $periods;
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

        for ($daysAgo = 13; $daysAgo >= 0; $daysAgo--) {
            $date = Carbon::now()->subDays($daysAgo);
            $postCount = rand(3, 8);

            for ($i = 0; $i < $postCount; $i++) {
                $time = $date->copy()
                    ->setHour(rand(6, 23))
                    ->setMinute(rand(0, 59))
                    ->setSecond(rand(0, 59));

                $number = $this->pickAvailableShowerNumber($time, $brokenPeriods);

                // 全番号が塞がっていた(まず無いはずだが念のため)場合はスキップ
                if ($number === null) {
                    continue;
                }

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

    /**
     * 指定した日時に「故障中でない」シャワー番号をランダムに1つ選ぶ。
     */
    private function pickAvailableShowerNumber(Carbon $time, array $brokenPeriods): ?int
    {
        $candidates = range(1, 7);

        // その時刻に故障中の番号を候補から除外
        foreach ($brokenPeriods as $period) {
            if ($time->betweenIncluded($period['start'], $period['end'])) {
                $candidates = array_diff($candidates, [$period['number']]);
            }
        }

        if (empty($candidates)) {
            return null;
        }

        return $candidates[array_rand($candidates)];
    }

    private function seedCapacity(string $gender, $users): void
    {
        $daysWithFullReport = collect(range(0, 13))->random(rand(5, 8));

        foreach ($daysWithFullReport as $daysAgo) {
            $date = Carbon::now()->subDays($daysAgo);
            $fullTime = $date->copy()->setHour(rand(7, 22))->setMinute(rand(0, 59));

            ShowerCapacityReport::create([
                'gender' => $gender,
                'status' => 'full',
                'user_id' => $users->random()->id,
                'created_at' => $fullTime,
                'updated_at' => $fullTime,
            ]);

            if (rand(1, 10) <= 7) {
                $vacantTime = $fullTime->copy()->addMinutes(rand(5, 25));

                ShowerCapacityReport::create([
                    'gender' => $gender,
                    'status' => 'vacant',
                    'user_id' => $users->random()->id,
                    'created_at' => $vacantTime,
                    'updated_at' => $vacantTime,
                ]);
            }
        }
    }
}