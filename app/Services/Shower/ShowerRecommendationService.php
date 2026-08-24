<?php

namespace App\Services\Shower;

use App\Models\Shower\ShowerMalfunctionReport;
use App\Models\User;
use Illuminate\Support\Collection;

class ShowerRecommendationService
{
    public function __construct(
        private ShowerConditionAggregator $aggregator,
    ) {
    }

    /**
     * @return array{shower_number:int, match_percent:int, temperature:float, pressure:float}|null
     */

    /**
     * @param string|null $period 指定した場合はその期間のみで計算(フォールバックなし)。
     *                            nullの場合は24h→3d→7d→14dの順にフォールバックする。
     */
    public function recommend(User $user, ?string $period = null): ?array
    {
        $conditions = $period !== null
            ? $this->aggregator->getConditions($user->gender, $period)
            : $this->conditionsWithFallback($user->gender);

        if ($conditions === null || $conditions->isEmpty()) {
            return null;
        }

        // 故障中の番号を除外
        $brokenNumbers = ShowerMalfunctionReport::brokenShowerNumbers($user->gender);
        $conditions = $conditions->reject(
            fn (array $condition) => $brokenNumbers->contains($condition['shower_number'])
        );

        if ($conditions->isEmpty()) {
            return null;
        }

        [$temperatureWeight, $pressureWeight] = $this->weightsFor($user->shower_priority_factor);

        $candidates = $conditions
            ->map(function (array $condition) use ($user, $temperatureWeight, $pressureWeight) {
                $temperatureDistance = abs($user->preferred_temperature - $condition['temperature']);
                $pressureDistance = abs($user->preferred_pressure - $condition['pressure']);

                // 選定用: 重視設定を反映した加重距離
                $weightedDistance = ($temperatureDistance * $temperatureWeight)
                    + ($pressureDistance * $pressureWeight);

                // 表示用: 常に均等(50/50)で計算するマッチ度
                $balancedDistance = ($temperatureDistance * 0.5) + ($pressureDistance * 0.5);
                $balancedMatchPercent = (int) round((1 - min($balancedDistance, 10) / 10) * 100);

                return [
                    'shower_number' => $condition['shower_number'],
                    'temperature' => $condition['temperature'],
                    'pressure' => $condition['pressure'],
                    'match_percent' => $balancedMatchPercent, // 表示用の値をそのまま採用
                    'temperature_distance' => $temperatureDistance,
                    'pressure_distance' => $pressureDistance,
                    '_weighted_distance' => $weightedDistance, // 選定(ソート)専用、返却時に除去
                ];
            })
            ->all();

        usort($candidates, function ($a, $b) use ($user) {
            // ① 選定は重み付き距離が小さい方を優先(値が小さい=好みに近い)
            if ($a['_weighted_distance'] !== $b['_weighted_distance']) {
                return $a['_weighted_distance'] <=> $b['_weighted_distance'];
            }

            // ② 同率の場合のタイブレーク
            $tiebreakA = $this->tiebreakDistance($a, $user->shower_priority_factor);
            $tiebreakB = $this->tiebreakDistance($b, $user->shower_priority_factor);

            if ($tiebreakA !== $tiebreakB) {
                return $tiebreakA <=> $tiebreakB;
            }

            // ③ それでも同率ならシャワー番号が若い方
            return $a['shower_number'] <=> $b['shower_number'];
        });

        $best = $candidates[0];
        unset($best['temperature_distance'], $best['pressure_distance'], $best['_weighted_distance']);

        return $best;
    }

    private function conditionsWithFallback(string $gender): ?Collection
    {
        foreach (['24h', '3d', '7d', '14d'] as $period) {
            $conditions = $this->aggregator->getConditions($gender, $period);

            if ($conditions->isNotEmpty()) {
                return $conditions;
            }
        }

        return null;
    }

    private function tiebreakDistance(array $candidate, ?string $priorityFactor): float
    {
        return match ($priorityFactor) {
            'temperature' => $candidate['pressure_distance'],
            'pressure' => $candidate['temperature_distance'],
            default => $candidate['temperature_distance'] + $candidate['pressure_distance'],
        };
    }

    private function weightsFor(?string $priorityFactor): array
    {
        return match ($priorityFactor) {
            'temperature' => [1.0, 0.0],
            'pressure' => [0.0, 1.0],
            default => [0.5, 0.5],
        };
    }
}