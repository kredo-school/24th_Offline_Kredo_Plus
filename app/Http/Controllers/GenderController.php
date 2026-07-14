<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        ]);

        $user->update([
            'gender' => $validated['gender'],
            'gender_locked' => true,
        ]);

        // /shower にアクセスしようとしていたなら、そこに戻す
        $intended = session()->pull('url.intended', route('shower.entry'));

        return redirect($intended);
    }
}
