<?php

namespace App\Http\Controllers\Shower;

use App\Http\Controllers\Controller;
use App\Models\Shower\ShowerMalfunctionReport;
use App\Models\Shower\ShowerReport;
use Illuminate\Http\Request;

class ShowerTrendDataController extends Controller
{
    private const TIMEZONE = 'Asia/Manila';

    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'shower_number' => ['required', 'integer', 'between:1,7'],
            'days' => ['required', 'integer', 'in:3,7,14'],
        ]);

        $gender = $request->user()->gender;
        $rangeStart = now(self::TIMEZONE)->subDays($validated['days'] - 1)->startOfDay();

        $reports = ShowerReport::query()
            ->where('gender', $gender)
            ->where('shower_number', $validated['shower_number'])
            ->where('created_at', '>=', $rangeStart->clone()->setTimezone(config('app.timezone')))
            ->get(['created_at', 'temperature', 'pressure']);

        // Asia/Manila基準の日付でグループ化して平均を算出
        $points = $reports
            ->groupBy(fn ($report) => $report->created_at->copy()->setTimezone(self::TIMEZONE)->format('Y-m-d'))
            ->map(function ($group, $date) {
                return [
                    'date' => $date,
                    'temperature' => round($group->avg('temperature'), 1),
                    'pressure' => round($group->avg('pressure'), 1),
                ];
            })
            ->sortKeys()
            ->values();

        $brokenPeriods = $this->brokenPeriodsWithinRange($gender, $validated['shower_number'], $rangeStart);

        return response()->json([
            'points' => $points,
            'broken_periods' => $brokenPeriods,
        ]);
    }

    /**
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
            $localTime = $report->created_at->copy()->setTimezone(self::TIMEZONE);

            if ($report->status === 'broken' && $brokenSince === null) {
                $brokenSince = $localTime;
            } elseif ($report->status === 'fixed' && $brokenSince !== null) {
                $periods[] = ['start' => $brokenSince, 'end' => $localTime];
                $brokenSince = null;
            }
        }

        if ($brokenSince !== null) {
            $periods[] = ['start' => $brokenSince, 'end' => now(self::TIMEZONE)];
        }

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