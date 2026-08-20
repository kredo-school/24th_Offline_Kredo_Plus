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
    $validated = $request->validate([
        'place_name' => ['nullable', 'string', 'max:255'],
        'address' => ['nullable', 'string', 'max:255'],
        'latitude' => ['required', 'numeric'],
        'longitude' => ['required', 'numeric'],
        'post_id' => ['nullable', 'exists:posts,id'],
    ], [
        'place_name.required' => '場所を入力してください。',
        'address.required' => '住所を入力してください。',
        'latitude.required' => '地図上でピンを立てて位置を選んでください。',
        'longitude.required' => '地図上でピンを立てて位置を選んでください。',
    ]);

    $location = EarthLocation::create($validated);

    // Editページから来た場合(post_idがある場合)は、そのままEditページへ戻す
    if ($request->filled('post_id')) {

        return redirect()
            ->route('information.edit', $request->post_id)
            ->with('status', '位置情報を追加しました。');

    }

    // Createページから来た場合(post_idが無い場合)は、従来通りCreateページへ
    return redirect()->route(
        'information.create',
        [
            'earth_location_id' => $location->id
        ]
    );
}}
