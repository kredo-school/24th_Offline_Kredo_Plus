<?php

namespace App\Http\Controllers\Shower;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shower\StoreShowerMalfunctionReportRequest;
use App\Models\Shower\ShowerMalfunctionReport;

class ShowerMalfunctionReportController extends Controller
{
    public function store(StoreShowerMalfunctionReportRequest $request)
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
        }

        return back()->with('success', '故障情報を報告しました');
    }
}
