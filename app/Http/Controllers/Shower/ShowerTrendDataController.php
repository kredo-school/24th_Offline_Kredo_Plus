<?php

namespace App\Http\Controllers\Shower;

use App\Http\Controllers\Controller;
use App\Models\Shower\ShowerMalfunctionReport;
use App\Models\Shower\ShowerReport;
use Illuminate\Http\Request;

class ShowerTrendDataController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'shower_number' => ['required', 'integer', 'between:1,7'],
            'days' => ['required', 'integer', 'in:3,7,14'],
        ]);

        $gender = $request->user()->gender;
        $rangeStart = now()->subDays($validated['days'] - 1)->startOfDay();

        $points = ShowerReport::query()
            ->where('gender', $gender)
            ->where('shower_number', $validated['shower_number'])
            ->where('created_at', '>=', $rangeStart)
            ->selectRaw('DATE(created_at) as date, AVG(temperature) as temperature, AVG(pressure) as pressure')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($row) => [
                'date' => $row->date,
                'temperature' => round((float) $row->temperature, 1),
                'pressure' => round((float) $row->pressure, 1),
            ]);


        $brokenPeriods = $this->brokenPeriodsWithinRange($gender, $validated['shower_number'], $rangeStart);

        return response()->json([
            'points' => $points,
            'broken_periods' => $brokenPeriods,
        ]);
    }

    /**
     * 指定した期間内に発生した故障区間(開始日〜終了日)を返す。
     * 現在も故障中の場合、終了日は今日になる。
     *
     * @return array<int, array{start: string, end: string}>
     */
    private function brokenPeriodsWithinRange(string $gender, int $showerNumber, \Illuminate\Support\Carbon $rangeStart): array
    {
        $reports = ShowerMalfunctionReport::query()
            ->where('gender', $gender)
            ->where('shower_number', $showerNumber)
            ->orderBy('created_at')
            ->get(['status', 'created_at']);

        $periods = [];
        $brokenSince = null;

        foreach ($reports as $report) {
            if ($report->status === 'broken' && $brokenSince === null) {
                $brokenSince = $report->created_at;
            } elseif ($report->status === 'fixed' && $brokenSince !== null) {
                $periods[] = ['start' => $brokenSince, 'end' => $report->created_at];
                $brokenSince = null;
            }
        }

        // 現在も故障中なら、期間の終わりを「今」として扱う
        if ($brokenSince !== null) {
            $periods[] = ['start' => $brokenSince, 'end' => now()];
        }

        // 指定した表示範囲(rangeStart〜今日)に重なる区間だけに絞り、日付文字列に変換
        return collect($periods)
            ->filter(fn ($period) => $period['end']->greaterThanOrEqualTo($rangeStart))
            ->map(fn ($period) => [
                'start' => $period['start']->max($rangeStart)->toDateString(),
                'end' => $period['end']->toDateString(),
            ])
            ->values()
            ->all();
    }
}
