<?php

namespace App\Http\Controllers\Shower;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shower\StoreShowerMalfunctionReportRequest;
use App\Models\Shower\ShowerMalfunctionReport;
use App\Services\Shower\ShowerNotificationService;

class ShowerMalfunctionReportController extends Controller
{
    public function store(StoreShowerMalfunctionReportRequest $request, ShowerNotificationService $notificationService)
    {
        $gender = $request->user()->gender;
        $comment = $request->validated('comment');

        foreach ($request->validated('defected_shower') as $number) {
            ShowerMalfunctionReport::create([
                'gender' => $gender,
                'shower_number' => $number,
                'status' => 'broken',
                'user_id' => $request->user()->id,
                'comment' => $comment,
            ]);

            $notificationService->malfunctionBroken($gender, (int) $number);
        }

        return back()->with('success', '故障情報を報告しました');
    }
}
