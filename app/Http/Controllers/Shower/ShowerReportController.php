<?php

namespace App\Http\Controllers\Shower;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shower\StoreShowerReportRequest;
use App\Models\Shower\ShowerReport;

class ShowerReportController extends Controller
{
    public function store(StoreShowerReportRequest $request)
    {
        $validated = $request->validated();

        ShowerReport::create([
            'gender' => $request->user()->gender,
            'shower_number' => $validated['shower_number'],
            'user_id' => $request->user()->id,
            'temperature' => round($validated['temperature'] / 10, 1), // 0〜100 → 0.0〜10.0
            'pressure' => round($validated['pressure'] / 10, 1),
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'シャワー情報を投稿しました');
    }
}
