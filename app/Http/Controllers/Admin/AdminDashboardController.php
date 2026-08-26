<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\MainCategory;
use App\Models\Category;
use App\Models\Shower\ShowerReport;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 今日のシャワー利用報告数
        $dailyShowerCount = ShowerReport::whereDate('created_at', Carbon::today())->count();

        // 今週のシャワー利用報告数
        $weeklyShowerCount = ShowerReport::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();

        // 今月のシャワー利用報告数
        $monthlyShowerCount = ShowerReport::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // 今年のシャワー利用報告数
        $yearlyShowerCount = ShowerReport::whereYear('created_at', Carbon::now()->year)->count();

        // ダッシュボードに必要なデータの取得
        $users = User::latest()->paginate(10);
        $mainCategories = MainCategory::all();
        $totalUsers = User::count();
        $totalPosts = Post::count();

        return view('admin.dashboard', compact(
            'dailyShowerCount',
            'weeklyShowerCount',
            'monthlyShowerCount',
            'yearlyShowerCount',
            'users',
            'mainCategories',
            'totalUsers',
            'totalPosts'
        ));
    }

    public function storeNotice(Request $request)
    {
        // お知らせ保存処理
    }

    public function storeMainCategory(Request $request)
    {
        // メインカテゴリー保存処理
    }

    public function storeCategory(Request $request)
    {
        // サブカテゴリー保存処理
    }
}