<?php

namespace App\Http\Controllers;

use App\Services\Shower\ShowerScale;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GenderController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->gender_locked) {
            abort(403, '性別は既に登録済みのため変更できません。');
        }

        $validated = $request->validate([
            'gender' => ['required', 'in:male,female'],
            'temp' => ['required', Rule::in(array_keys(ShowerScale::PREFERENCE_TEMPERATURE_LEVELS))],
            'pressure' => ['required', Rule::in(array_keys(ShowerScale::PREFERENCE_PRESSURE_LEVELS))],
        ]);

        $user->update([
            'gender' => $validated['gender'],
            'gender_locked' => true,
            'preferred_temperature' => ShowerScale::PREFERENCE_TEMPERATURE_LEVELS[$validated['temp']],
            'preferred_pressure' => ShowerScale::PREFERENCE_PRESSURE_LEVELS[$validated['pressure']],
        ]);

        // /shower にアクセスしようとしていたなら、そこに戻す
        $intended = session()->pull('url.intended', route('shower.entry'));

        return redirect($intended);
    }
}