<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shower\ShowerMalfunctionReport;
use App\Services\Shower\ShowerNotificationService;
use Illuminate\Http\Request;

class AdminShowerMalfunctionController extends Controller
{
    public function index()
    {
        return view('admin.shower.malfunctions', [
            'currentlyBroken' => ShowerMalfunctionReport::currentlyBroken(),
            'history' => ShowerMalfunctionReport::with('user')
                ->latestFirst()
                ->paginate(30),
        ]);
    }

    public function fix(Request $request, string $gender, int $showerNumber, ShowerNotificationService $notificationService)
    {
        ShowerMalfunctionReport::create([
            'gender' => $gender,
            'shower_number' => $showerNumber,
            'status' => 'fixed',
            'user_id' => $request->user()->id,
            'comment' => null,
        ]);

        $notificationService->malfunctionFixed($gender, $showerNumber);

        return back()->with('success', "{$gender} {$showerNumber}番の修理完了を記録しました");
    }
}
