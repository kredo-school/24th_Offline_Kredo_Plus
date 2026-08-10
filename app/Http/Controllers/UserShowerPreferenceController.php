<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Shower\ShowerScale;
use App\Http\Requests\Shower\UpdateShowerPreferenceRequest;


class UserShowerPreferenceController extends Controller
{
    

    public function update(UpdateShowerPreferenceRequest $request)
    {
        $request->user()->update([
            'preferred_temperature' => ShowerScale::PREFERENCE_TEMPERATURE_LEVELS[$request->validated('temperature')],
            'preferred_pressure' => ShowerScale::PREFERENCE_PRESSURE_LEVELS[$request->validated('pressure')],
        ]);

        return back()->with('success', '好みを保存しました');
    }
}
