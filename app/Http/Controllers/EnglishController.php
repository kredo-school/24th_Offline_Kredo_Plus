<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class EnglishController extends Controller
{
    /**
     * Display the English learning dashboard.
     */
    public function index(): View
    {
        return view('english.index', [
            'levelInfo' => [
                'level' => 7,
                'current_xp' => 3445,
                'xp_in_level' => 445,
                'xp_needed' => 500,
            ],
            'totalStudyDays' => 7,
            'overallProgress' => 48,
            'featureProgress' => [
                'toeic' => 100,
                'ielts' => 14,
                'vocabulary' => 75,
                'typing' => 1,
            ],
        ]);
    }
}
