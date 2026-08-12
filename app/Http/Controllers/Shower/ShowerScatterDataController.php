<?php

namespace App\Http\Controllers\Shower;

use App\Http\Controllers\Controller;
use App\Models\Shower\ShowerMalfunctionReport;
use App\Services\Shower\ShowerConditionAggregator;
use Illuminate\Http\Request;

class ShowerScatterDataController extends Controller
{
    public function __invoke(Request $request, ShowerConditionAggregator $aggregator)
    {
        $validated = $request->validate([
            'period' => ['required', 'in:latest,24h,3d,7d,14d'],
        ]);

        $gender = $request->user()->gender;
        $brokenNumbers = \App\Models\Shower\ShowerMalfunctionReport::brokenShowerNumbers($gender);

        // グラフにプロットする点からは、故障中の番号をあらかじめ除外
        $points = $aggregator->getConditions($gender, $validated['period'])
            ->reject(fn (array $condition) => $brokenNumbers->contains($condition['shower_number']))
            ->values();

        return response()->json([
            'points' => $points,
            'broken_numbers' => $brokenNumbers,
        ]);
    }
}
