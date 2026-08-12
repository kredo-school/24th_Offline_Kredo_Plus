<?php

namespace App\Http\Controllers\Shower;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shower\UpdateShowerPriorityRequest;
use App\Services\Shower\ShowerRecommendationService;

class ShowerPriorityController extends Controller
{
    public function update(UpdateShowerPriorityRequest $request, ShowerRecommendationService $service)
    {
        $factor = $request->validated('factor');

        $user = $request->user();
        $user->update([
            'shower_priority_factor' => $factor === 'none' ? null : $factor,
        ]);

        $recommendation = $service->recommend($user);

        return response()->json([
            'priority_factor' => $user->shower_priority_factor,
            'recommendation' => $recommendation,
        ]);
    }
}
