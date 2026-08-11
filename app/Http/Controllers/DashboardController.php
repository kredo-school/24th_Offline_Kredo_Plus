<?php

namespace App\Http\Controllers;

use App\Models\MainCategory;
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
            // 留学情報カードのアイコン一覧。既存4つ+アドミンが追加した分もすべて表示する
            'mainCategories' => MainCategory::allOrdered(),
        ]);
    }
}
