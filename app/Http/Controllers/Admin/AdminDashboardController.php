<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\English\StudyLog;
use App\Models\MainCategory;
use App\Models\Notice;
use App\Models\Post;
use App\Models\Shower\ShowerCapacityReport;
use App\Models\Shower\ShowerMalfunctionReport;
use App\Models\Shower\ShowerReport;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        // 1. ユーザー一覧データの整形
        $users = User::get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'dorm' => $user->gender === 'female' ? '女子寮 (Female)' : ($user->gender === 'male' ? '男子寮 (Male)' : '未設定'),
                'registered_at' => $user->created_at ? $user->created_at->format('Y/m/d') : '-',
                'last_active' => $user->updated_at ? $user->updated_at->diffForHumans() : '未アクティブ',
                'active_hours' => ($user->total_study_time ?? 0) . '時間',
                'status' => 'active',
            ];
        });

        // 2. 日次スタッツ（本日 vs 前日）
        $today = today();
        $yesterday = today()->subDay();

        $todayActiveUsersCount = User::whereDate('updated_at', $today)->count();
        $yesterdayActiveUsersCount = User::whereDate('updated_at', $yesterday)->count();
        $activeUsersDiff = $todayActiveUsersCount - $yesterdayActiveUsersCount;

        $todayInfoUpdates = Post::whereDate('created_at', $today)->count();
        $yesterdayInfoUpdates = Post::whereDate('created_at', $yesterday)->count();
        $infoUpdatesDiff = $todayInfoUpdates - $yesterdayInfoUpdates;

        $todayStudyActiveUsersCount = StudyLog::whereDate('studied_date', $today)->distinct('user_id')->count('user_id');
        $yesterdayStudyActiveUsersCount = StudyLog::whereDate('studied_date', $yesterday)->distinct('user_id')->count('user_id');
        $studyActiveUsersDiff = $todayStudyActiveUsersCount - $yesterdayStudyActiveUsersCount;

        $todayShowerUpdates = ShowerReport::whereDate('created_at', $today)->count();
        $yesterdayShowerUpdates = ShowerReport::whereDate('created_at', $yesterday)->count();
        $showerUpdatesDiff = $todayShowerUpdates - $yesterdayShowerUpdates;

        $stats = [
            'todayActiveUsersCount' => $todayActiveUsersCount,
            'activeUsersDiff'       => $activeUsersDiff,
            'todayInfoUpdates'      => $todayInfoUpdates,
            'infoUpdatesDiff'       => $infoUpdatesDiff,
            'todayStudyActiveUsersCount' => $todayStudyActiveUsersCount,
            'studyActiveUsersDiff'           => $studyActiveUsersDiff,
            'todayShowerUpdates'    => $todayShowerUpdates,
            'showerUpdatesDiff'     => $showerUpdatesDiff,
        ];

        // 3. 期間分析（週次・月次・年次）
        $now = now();
        $totalUsersCount = User::count();

        // 週間サマリー（過去7日間 vs 前週7日間）
        $weeklyActiveCount = User::where('updated_at', '>=', $now->copy()->subDays(7))->count();
        $prevWeeklyActiveCount = User::whereBetween('updated_at', [$now->copy()->subDays(14), $now->copy()->subDays(7)])->count();
        $wauRate = $totalUsersCount > 0 ? round(($weeklyActiveCount / $totalUsersCount) * 100, 1) : 0;
        $prevWauRate = $totalUsersCount > 0 ? round(($prevWeeklyActiveCount / $totalUsersCount) * 100, 1) : 0;
        $wauDiff = round($wauRate - $prevWauRate, 1);

        $weeklyEnglishUsers = StudyLog::where('studied_date', '>=', $now->copy()->subDays(7))->distinct('user_id')->count('user_id');
        $weeklyEnglishRate = $weeklyActiveCount > 0 ? round(($weeklyEnglishUsers / $weeklyActiveCount) * 100, 1) : 0;

        $weeklyPostsCount = Post::where('created_at', '>=', $now->copy()->subDays(7))->count();
        $prevWeeklyPostsCount = Post::whereBetween('created_at', [$now->copy()->subDays(14), $now->copy()->subDays(7)])->count();
        $weeklyPostsDiff = $weeklyPostsCount - $prevWeeklyPostsCount;

        $weeklyShowerCount = ShowerReport::where('created_at', '>=', $now->copy()->subDays(7))->count();
        $weeklyAvgShowerReviews = $weeklyActiveCount > 0 ? round($weeklyShowerCount / $weeklyActiveCount, 1) : 0;

        // 月間サマリー（過去30日間 vs 前月30日間）
        $monthlyActiveCount = User::where('updated_at', '>=', $now->copy()->subDays(30))->count();
        $mauRate = $totalUsersCount > 0 ? round(($monthlyActiveCount / $totalUsersCount) * 100, 1) : 0;

        $monthlyEnglishUsers = StudyLog::where('studied_date', '>=', $now->copy()->subDays(30))->distinct('user_id')->count('user_id');
        $monthlyEnglishRate = $monthlyActiveCount > 0 ? round(($monthlyEnglishUsers / $monthlyActiveCount) * 100, 1) : 0;

        $monthlyPostsCount = Post::where('created_at', '>=', $now->copy()->subDays(30))->count();
        $prevMonthlyPostsCount = Post::whereBetween('created_at', [$now->copy()->subDays(60), $now->copy()->subDays(30)])->count();
        $monthlyPostsDiff = $monthlyPostsCount - $prevMonthlyPostsCount;

        $monthlyShowerCount = ShowerReport::where('created_at', '>=', $now->copy()->subDays(30))->count();
        $monthlyAvgShowerReviews = $monthlyActiveCount > 0 ? round($monthlyShowerCount / $monthlyActiveCount, 1) : 0;

        // 年次サマリー（過去365日間）
        $yearlyActiveCount = User::where('updated_at', '>=', $now->copy()->subDays(365))->count();
        $retentionRate = $totalUsersCount > 0 ? round(($yearlyActiveCount / $totalUsersCount) * 100, 1) : 0;

        $yearlyEnglishUsers = StudyLog::where('studied_date', '>=', $now->copy()->subDays(365))->distinct('user_id')->count('user_id');
        $yearlyEnglishRate = $yearlyActiveCount > 0 ? round(($yearlyEnglishUsers / $yearlyActiveCount) * 100, 1) : 0;

        $yearlyPostsCount = Post::where('created_at', '>=', $now->copy()->subDays(365))->count();

        $yearlyShowerCount = ShowerReport::where('created_at', '>=', $now->copy()->subDays(365))->count();
        $yearlyAvgShowerReviews = $yearlyActiveCount > 0 ? round($yearlyShowerCount / $yearlyActiveCount, 1) : 0;

        $analytics = compact(
            'totalUsersCount',
            'weeklyActiveCount', 'wauRate', 'wauDiff', 'weeklyEnglishUsers', 'weeklyEnglishRate', 'weeklyPostsCount', 'weeklyPostsDiff', 'weeklyShowerCount', 'weeklyAvgShowerReviews',
            'monthlyActiveCount', 'mauRate', 'monthlyEnglishUsers', 'monthlyEnglishRate', 'monthlyPostsCount', 'monthlyPostsDiff', 'monthlyShowerCount', 'monthlyAvgShowerReviews',
            'yearlyActiveCount', 'retentionRate', 'yearlyEnglishUsers', 'yearlyEnglishRate', 'yearlyPostsCount', 'yearlyShowerCount', 'yearlyAvgShowerReviews'
        );

        // 4. お知らせ一覧の取得（作成日時順）
        $notices = Notice::latest()->get()->map(function ($notice) {
            return [
                'id' => $notice->id,
                'title' => $notice->title,
                'category' => $notice->category,
                'target' => $notice->target,
                'sent_at' => $notice->created_at ? $notice->created_at->format('Y/m/d H:i') : '-',
                'content' => $notice->content,
            ];
        });

        // 5. 留学情報管理タブ用(myu担当): メイン/サブカテゴリー一覧(隠しカテゴリーは管理画面には出さない)
        // 削除ボタンの「中身が残っているか」の警告表示のため、sub_count / post_count を各行に付与しておく。
        $adminMainCategories = MainCategory::orderBy('sort_order')->orderBy('id')->get();
        $subCounts = Category::where('is_hidden', false)
            ->selectRaw('section, count(*) as cnt')->groupBy('section')->pluck('cnt', 'section');
        $adminMainCategories->each(function ($mc) use ($subCounts) {
            $mc->sub_count = (int) ($subCounts[$mc->key] ?? 0);
        });

        $adminCategories = Category::where('is_hidden', false)->orderBy('section')->orderBy('sort_order')->get();
        $postCounts = Post::selectRaw('category_id, count(*) as cnt')->groupBy('category_id')->pluck('cnt', 'category_id');
        $adminCategories->each(function ($c) use ($postCounts) {
            $c->post_count = (int) ($postCounts[$c->id] ?? 0);
        });

        return view('admin.dashboard', array_merge(
            compact('users', 'notices', 'adminMainCategories', 'adminCategories'),
            $stats,
            $analytics
        ));
    }

    /**
     * 新規お知らせの保存処理（Alpine.js / Fetch API用）
     */
    public function storeNotice(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'content'  => 'required|string',
        ]);

        $notice = Notice::create([
            'title'    => $validated['title'],
            'category' => $validated['category'],
            'target'   => '全員',
            'content'  => $validated['content'],
        ]);

        return response()->json([
            'success' => true,
            'notice'  => [
                'id'       => $notice->id,
                'title'    => $notice->title,
                'category' => $notice->category,
                'target'   => $notice->target,
                'sent_at'  => $notice->created_at ? $notice->created_at->format('Y/m/d H:i') : now()->format('Y/m/d H:i'),
                'content'  => $notice->content,
            ]
        ], 201);
    }
}