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

    public function edit(EarthLocation $earthLocation)
    {
        return view(
            'earth.earth_locations.edit',
            compact('earthLocation')
        );
    }

    public function update(Request $request, EarthLocation $earthLocation)
    {
        $earthLocation->update([
            'place_name' => $request->place_name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()
            ->route('information.edit', $earthLocation->post_id)
            ->with('status', '位置情報を更新しました。');
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
