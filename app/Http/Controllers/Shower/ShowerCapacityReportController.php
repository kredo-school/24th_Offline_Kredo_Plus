<?php

namespace App\Http\Controllers\Shower;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shower\StoreShowerCapacityReportRequest;
use App\Models\Shower\ShowerCapacityReport;
use App\Services\Shower\ShowerNotificationService;

class ShowerCapacityReportController extends Controller
{
    public function store(StoreShowerCapacityReportRequest $request, ShowerNotificationService $notificationService)
    {
        $gender = $request->user()->gender;
        $status = $request->validated('status');

        ShowerCapacityReport::create([
            'gender' => $gender,
            'status' => $status,
            'user_id' => $request->user()->id,
        ]);

        if ($status === 'full') {
            $notificationService->capacityFull($gender);
        } else {
            $notificationService->capacityVacant($gender);
        }

        $message = $status === 'full'
            ? '満室情報を報告しました'
            : '空室情報を報告しました';

        return back()->with('success', $message);
    }
}
