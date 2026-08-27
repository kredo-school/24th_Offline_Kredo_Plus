<?php

namespace App\Http\Controllers;

use App\Models\EarthLocation;

class EarthController extends Controller
{
    public function index()
    {
        $locations = EarthLocation::with([
                'post' => function ($query) {
                    $query->withCount('likes')
                        ->with(['category', 'user', 'likes', 'bookmarks']);
                },
            ])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereHas('post')
            ->get();

        $locations->each(function ($location) {
            if ($location->post) {
                if ($location->post->category) {
                    $location->post->category->pin_color = $location->post->category->color();
                }
                $location->post->created_at_human = $location->post->created_at->diffForHumans();
            }
        });

        return view('earth.index', compact('locations'));
    }
}
