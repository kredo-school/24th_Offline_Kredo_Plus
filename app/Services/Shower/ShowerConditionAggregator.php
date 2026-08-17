<?php

namespace App\Services\Shower;

use App\Models\Shower\ShowerReport;
use Illuminate\Support\Collection;

class ShowerConditionAggregator
{
    // 期間キー => 時間数(nullは「最新1件」の特別扱い)
    public const PERIOD_HOURS = [
        'latest' => null,
        '24h' => 24,
        '3d' => 72,
        '7d' => 168,
        '14d' => 336,
    ];

    /**
     * 指定した性別・期間で、シャワー番号ごとの状態(温度・水圧の平均 or 最新値)を取得する。
     *
     * @return Collection<int, array{shower_number:int, temperature:float|null, pressure:float|null, reported_at:?string}>
     */
    public function getConditions(string $gender, string $period): Collection
    {
        if (! array_key_exists($period, self::PERIOD_HOURS)) {
            throw new \InvalidArgumentException("Unknown period: {$period}");
        }

        return $period === 'latest'
            ? $this->latestPerShower($gender)
            : $this->averagePerShower($gender, self::PERIOD_HOURS[$period]);
    }

    /**
     * 1つのシャワー番号だけを取得したい場合(ゲージ表示用)
     */
    public function getCondition(string $gender, int $showerNumber, string $period): ?array
    {
        return $this->getConditions($gender, $period)
            ->firstWhere('shower_number', $showerNumber);
    }

    private function averagePerShower(string $gender, int $hours): Collection
    {
        return ShowerReport::query()
            ->where('gender', $gender)
            ->where('created_at', '>=', now()->subHours($hours))
            ->selectRaw('shower_number, AVG(temperature) as temperature, AVG(pressure) as pressure, MAX(created_at) as reported_at')
            ->groupBy('shower_number')
            ->get()
            ->map(fn ($row) => [
                'shower_number' => (int) $row->shower_number,
                'temperature' => $row->temperature !== null ? round((float) $row->temperature, 1) : null,
                'pressure' => $row->pressure !== null ? round((float) $row->pressure, 1) : null,
                'reported_at' => $row->reported_at,
            ]);
    }

    private function latestPerShower(string $gender): Collection
    {
        return ShowerReport::query()
            ->where('gender', $gender)
            ->orderBy('shower_number')
            ->orderByDesc('created_at')
            ->get()
            ->unique('shower_number') // 番号ごとに、created_at降順の先頭(=最新)だけ残す
            ->map(fn ($report) => [
                'shower_number' => $report->shower_number,
                'temperature' => (float) $report->temperature,
                'pressure' => (float) $report->pressure,
                'reported_at' => $report->created_at->toISOString(),
            ])
            ->values();
    }
}