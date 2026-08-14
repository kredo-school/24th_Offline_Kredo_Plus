<?php

namespace App\Http\Controllers;

use App\Models\EarthLocation;

class EarthController extends Controller
{
    public function index()
    {
        $locations = EarthLocation::with('post.category')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereHas('post')
            ->get();

        return view('earth.index', compact('locations'));
    }
}
