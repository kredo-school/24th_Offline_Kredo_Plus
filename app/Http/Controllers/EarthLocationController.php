<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EarthLocation;

class EarthLocationController extends Controller
{
    public function create()
    {
        return view('earth.earth_locations.create');
    }

    public function store(Request $request)
    {
        $location = EarthLocation::create([
            'place_name' => $request->place_name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route(
            'information.create',
            [
                'earth_location_id' => $location->id
            ]
        );
    }
}
