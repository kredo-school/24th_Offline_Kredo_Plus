<?php

namespace App\Http\Controllers\Shower;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shower\StoreShowerCapacityReportRequest;
use App\Models\Shower\ShowerCapacityReport;

class ShowerCapacityReportController extends Controller
{
    public function store(StoreShowerCapacityReportRequest $request)
    {
        ShowerCapacityReport::create([
            'gender' => $request->user()->gender,
            'status' => $request->validated('status'),
            'user_id' => $request->user()->id,
        ]);

        $message = $request->validated('status') === 'full'
            ? '満室情報を報告しました'
            : '空室情報を報告しました';

        return back()->with('success', $message);
    }
}
