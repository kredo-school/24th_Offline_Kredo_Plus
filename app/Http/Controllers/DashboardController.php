<?php

namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\Shower\ShowerCapacityReport;
use App\Models\Shower\ShowerMalfunctionReport;
use App\Services\Shower\ShowerRecommendationService;
use App\Services\Shower\ShowerScale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request, ShowerRecommendationService $service): View
{
    $user = $request->user();

    $showerSummary = null;

    if ($user->gender_locked) {
        $recommendation = $service->recommend($user);

        $showerSummary = [
            'recommended_number' => $recommendation['shower_number'] ?? null,
            'temperature_label' => $recommendation
                ? \App\Services\Shower\ShowerScale::zoneLabel($recommendation['temperature'], \App\Services\Shower\ShowerScale::CONDITION_TEMPERATURE_LEVELS)
                : null,
            'pressure_label' => $recommendation
                ? \App\Services\Shower\ShowerScale::zoneLabel($recommendation['pressure'], \App\Services\Shower\ShowerScale::CONDITION_PRESSURE_LEVELS)
                : null,
            'is_full' => ShowerCapacityReport::isCurrentlyFull($user->gender),
            'full_reported_minutes_ago' => ShowerCapacityReport::fullReportedMinutesAgo($user->gender),
            'broken_numbers' => ShowerMalfunctionReport::brokenShowerNumbers($user->gender),
        ];
    }

    return view('dashboard', [
            'showIntro' => $request->session()->pull('show_intro', false),
            'mainCategories' => MainCategory::allOrdered(),
            'showerSummary' => $showerSummary,
            'user' => $user,
        ]);
    }
}
