<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShowerController extends Controller
{
    // /shower にアクセスされた時の振り分け専用
    public function entry(Request $request)
    {
        $user = $request->user();

        return match ($user->gender) {
            'male' => redirect()->route('shower.male'),
            'female' => redirect()->route('shower.female'),
        };
    }

    public function male()
    {
        return view('showers.males.home');
    }

    public function female()
    {
        return view('showers.females.home');
    }
}
