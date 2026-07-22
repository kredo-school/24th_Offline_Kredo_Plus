<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request): View
    {
        return view('dashboard', [
            'showIntro' => $request->session()->pull('show_intro', false),
        ]);
    }
}
