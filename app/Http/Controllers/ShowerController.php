<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Shower\ShowerRecommendationService;

class ShowerController extends Controller
{
    // /shower にアクセスされた時の振り分け専用
    public function entry(Request $request)
    {
        $user = $request->user();

        return match ($user->gender) {
            'male' => redirect()->route('shower.male'),
            'female' => redirect()->route('shower.female'),
        };
    }

    public function male(Request $request, ShowerRecommendationService $service)
    {
        return view('showers.males.home', [
            'user' => $request->user(),
            'recommendation' => $service->recommend($request->user()),
        ]);
    }

    public function female(Request $request, ShowerRecommendationService $service)
    {
        return view('showers.females.home', [
            'user' => $request->user(),
            'recommendation' => $service->recommend($request->user()),
        ]);
    }
}
