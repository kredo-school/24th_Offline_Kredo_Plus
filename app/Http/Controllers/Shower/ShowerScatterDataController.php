<?php

namespace App\Http\Controllers\Shower;

use App\Http\Controllers\Controller;
use App\Models\Shower\ShowerMalfunctionReport;
use App\Services\Shower\ShowerConditionAggregator;
use App\Services\Shower\ShowerRecommendationService;
use Illuminate\Http\Request;

class ShowerScatterDataController extends Controller
{
    public function __invoke(Request $request, ShowerConditionAggregator $aggregator, ShowerRecommendationService $recommendationService)
    {
        $validated = $request->validate([
            'period' => ['required', 'in:latest,24h,3d,7d,14d'],
        ]);

        $gender = $request->user()->gender;
        $brokenNumbers = ShowerMalfunctionReport::brokenShowerNumbers($gender);

        $points = $aggregator->getConditions($gender, $validated['period'])
            ->reject(fn (array $condition) => $brokenNumbers->contains($condition['shower_number']))
            ->values();

        $recommendation = $recommendationService->recommend($request->user(), $validated['period']);

        return response()->json([
            'points' => $points,
            'broken_numbers' => $brokenNumbers,
            'recommended_number' => $recommendation['shower_number'] ?? null,
        ]);
    }
}
