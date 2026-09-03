<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminMessage;
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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        // 1. ユーザー一覧データの整形
        $users = User::withCount(['showerReports', 'posts', 'suggestions'])
            ->with([
                'showerReports:id,user_id,created_at',
                'posts:id,user_id,created_at',
                'suggestions:id,user_id,created_at',
            ])
            ->latest()
            ->get()
            ->map(function ($user) {
                return [
                    'id'            => $user->id,
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'avatar_url'    => $user->avatar_url,
                    'role'          => $user->role,
                    'role_id'       => $user->role_id,
                    'is_active'     => $user->is_active ?? true,
                    'dorm'          => $user->dorm ?? ($user->gender === 'female' ? '女子寮 (Female)' : ($user->gender === 'male' ? '男子寮 (Male)' : '未設定')),
                    'course'        => $user->course ?? '',
                    'registered_at' => $user->created_at ? $user->created_at->format('Y/m/d') : '-',
                    'created_at'    => $user->created_at ? $user->created_at->format('Y/m/d') : '-',
                    'last_active'   => $user->updated_at ? $user->updated_at->diffForHumans() : '未アクティブ',
                    'active_hours'  => ($user->total_study_time ?? 0) . '時間',
                    'status'        => ($user->is_active ?? true) ? 'active' : 'inactive',

                    'gender'                      => $user->gender ?? '未設定',
                    'graduation_date'             => $user->graduation_date ? $user->graduation_date->format('Y/m/d') : '未設定',
                    'preferred_temperature_label' => $user->preferred_temperature_label ?? '未設定',
                    'preferred_pressure_label'    => $user->preferred_pressure_label ?? '未設定',
                    'toeic_exam_date'             => $user->toeic_exam_date ? $user->toeic_exam_date->format('Y/m/d') : '未登録',
                    'ielts_exam_date'             => $user->ielts_exam_date ? $user->ielts_exam_date->format('Y/m/d') : '未登録',

                    'total_xp'         => $user->total_xp ?? 0,
                    'study_streak'     => $user->study_streak ?? 0,
                    'total_study_time' => $user->total_study_time ?? 0,
                    'last_study_date'  => $user->last_study_date ? $user->last_study_date->format('Y/m/d') : '未学習',

                    'shower_reports' => $user->showerReports,
                    'posts'          => $user->posts,
                    'suggestions'    => $user->suggestions,
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
        $dailyShowerCount = $todayShowerUpdates;
        $yesterdayShowerUpdates = ShowerReport::whereDate('created_at', $yesterday)->count();
        $showerUpdatesDiff = $todayShowerUpdates - $yesterdayShowerUpdates;

        $stats = [
            'todayActiveUsersCount'      => $todayActiveUsersCount,
            'activeUsersDiff'            => $activeUsersDiff,
            'todayInfoUpdates'           => $todayInfoUpdates,
            'infoUpdatesDiff'            => $infoUpdatesDiff,
            'todayStudyActiveUsersCount' => $todayStudyActiveUsersCount,
            'studyActiveUsersDiff'       => $studyActiveUsersDiff,
            'todayShowerUpdates'         => $todayShowerUpdates,
            'showerUpdatesDiff'          => $showerUpdatesDiff,
            'dailyShowerCount'           => $dailyShowerCount,
        ];

        // 3. 期間分析（週次・月次・年次）
        $now = now();
        $totalUsersCount = User::count();

        // 週間サマリー
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

        // 月間サマリー
        $monthlyActiveCount = User::where('updated_at', '>=', $now->copy()->subDays(30))->count();
        $mauRate = $totalUsersCount > 0 ? round(($monthlyActiveCount / $totalUsersCount) * 100, 1) : 0;

        $monthlyEnglishUsers = StudyLog::where('studied_date', '>=', $now->copy()->subDays(30))->distinct('user_id')->count('user_id');
        $monthlyEnglishRate = $monthlyActiveCount > 0 ? round(($monthlyEnglishUsers / $monthlyActiveCount) * 100, 1) : 0;

        $monthlyPostsCount = Post::where('created_at', '>=', $now->copy()->subDays(30))->count();
        $prevMonthlyPostsCount = Post::whereBetween('created_at', [$now->copy()->subDays(60), $now->copy()->subDays(30)])->count();
        $monthlyPostsDiff = $monthlyPostsCount - $prevMonthlyPostsCount;

        $monthlyShowerCount = ShowerReport::where('created_at', '>=', $now->copy()->subDays(30))->count();
        $monthlyAvgShowerReviews = $monthlyActiveCount > 0 ? round($monthlyShowerCount / $monthlyActiveCount, 1) : 0;

        // 年次サマリー
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

        // 4. お知らせ一覧の取得
        $notices = Notice::latest()->get()->map(function ($notice) {
            return [
                'id'       => $notice->id,
                'title'    => $notice->title,
                'category' => $notice->category,
                'target'   => $notice->target,
                'sent_at'  => $notice->created_at ? $notice->created_at->format('Y/m/d H:i') : '-',
                'content'  => $notice->content,
            ];
        });

        // 故障中シャワーの取得
        $brokenShowers = ShowerMalfunctionReport::currentlyBroken();

        // 管理者伝言板の取得（最新10件）
        $adminMessages = AdminMessage::with('user')->latest()->take(10)->get();

        // 5. 留学情報管理タブ用
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

        // 6. パフォーマンス・サマリーカード用データ ($periods) の動的生成
        $dailyEnglishRate = $todayActiveUsersCount > 0 ? round(($todayStudyActiveUsersCount / $todayActiveUsersCount) * 100, 1) : 0;
        $dailyShowerRate  = $todayActiveUsersCount > 0 ? round(($todayShowerUpdates / $todayActiveUsersCount) * 100, 1) : 0;

        $periods = [
            'daily' => [
                ['title' => 'アクティブユーザー', 'val' => (string)$todayActiveUsersCount, 'unit' => '人', 'sub' => '前日比 ' . ($activeUsersDiff >= 0 ? "+{$activeUsersDiff}" : $activeUsersDiff), 'dot' => 'bg-emerald-500', 'feat' => 'users'],
                ['title' => '英語学習利用率', 'val' => (string)$dailyEnglishRate, 'unit' => '%', 'sub' => "アクティブ {$todayStudyActiveUsersCount}人", 'dot' => 'bg-yellow-500', 'feat' => 'english'],
                ['title' => '情報投稿数', 'val' => (string)$todayInfoUpdates, 'unit' => '件', 'sub' => '前日比 ' . ($infoUpdatesDiff >= 0 ? "+{$infoUpdatesDiff}" : $infoUpdatesDiff), 'dot' => 'bg-lime-500', 'feat' => 'info'],
                ['title' => 'シャワー利用率', 'val' => (string)$dailyShowerRate, 'unit' => '%', 'sub' => "投稿 {$todayShowerUpdates}件", 'dot' => 'bg-sky-500', 'feat' => 'shower'],
            ],
            'weekly' => [
                ['title' => 'WAU (週間アクティブ)', 'val' => (string)$weeklyActiveCount, 'unit' => '人', 'sub' => "アクティブ率 {$wauRate}%", 'dot' => 'bg-emerald-500', 'feat' => 'users'],
                ['title' => '英語学習利用率', 'val' => (string)$weeklyEnglishRate, 'unit' => '%', 'sub' => "利用人数 {$weeklyEnglishUsers}人", 'dot' => 'bg-yellow-500', 'feat' => 'english'],
                ['title' => '新規情報投稿数', 'val' => (string)$weeklyPostsCount, 'unit' => '件', 'sub' => '前週比 ' . ($weeklyPostsDiff >= 0 ? "+{$weeklyPostsDiff}" : $weeklyPostsDiff), 'dot' => 'bg-lime-500', 'feat' => 'info'],
                ['title' => '平均レビュー数/人', 'val' => (string)$weeklyAvgShowerReviews, 'unit' => '件', 'sub' => "総投稿数 {$weeklyShowerCount}件", 'dot' => 'bg-sky-500', 'feat' => 'shower'],
            ],
            'monthly' => [
                ['title' => 'MAU (月間アクティブ)', 'val' => (string)$monthlyActiveCount, 'unit' => '人', 'sub' => "アクティブ率 {$mauRate}%", 'dot' => 'bg-emerald-500', 'feat' => 'users'],
                ['title' => '英語学習利用率', 'val' => (string)$monthlyEnglishRate, 'unit' => '%', 'sub' => "利用人数 {$monthlyEnglishUsers}人", 'dot' => 'bg-yellow-500', 'feat' => 'english'],
                ['title' => '新規情報投稿数', 'val' => (string)$monthlyPostsCount, 'unit' => '件', 'sub' => '前月比 ' . ($monthlyPostsDiff >= 0 ? "+{$monthlyPostsDiff}" : $monthlyPostsDiff), 'dot' => 'bg-lime-500', 'feat' => 'info'],
                ['title' => '平均レビュー数/人', 'val' => (string)$monthlyAvgShowerReviews, 'unit' => '件', 'sub' => "総投稿数 {$monthlyShowerCount}件", 'dot' => 'bg-sky-500', 'feat' => 'shower'],
            ],
            'yearly' => [
                ['title' => '年間アクティブ', 'val' => (string)$yearlyActiveCount, 'unit' => '人', 'sub' => "定着率 {$retentionRate}%", 'dot' => 'bg-emerald-500', 'feat' => 'users'],
                ['title' => '英語学習利用率', 'val' => (string)$yearlyEnglishRate, 'unit' => '%', 'sub' => "利用人数 {$yearlyEnglishUsers}人", 'dot' => 'bg-yellow-500', 'feat' => 'english'],
                ['title' => '総情報投稿数', 'val' => (string)$yearlyPostsCount, 'unit' => '件', 'sub' => '年次累計データ', 'dot' => 'bg-lime-500', 'feat' => 'info'],
                ['title' => '平均レビュー数/人', 'val' => (string)$yearlyAvgShowerReviews, 'unit' => '件', 'sub' => "総投稿数 {$yearlyShowerCount}件", 'dot' => 'bg-sky-500', 'feat' => 'shower'],
            ],
        ];

        // 7. 機能別分析用データの生成 ($featureAnalyticsData)
        $featureAnalyticsData = [
            'analyticsData' => [
                'daily' => [
                    'periodLabel' => '今日',
                    'english' => ['users' => $todayStudyActiveUsersCount, 'rate' => $dailyEnglishRate],
                    'info'    => ['count' => $todayInfoUpdates, 'rate' => $todayActiveUsersCount > 0 ? round(($todayInfoUpdates / $todayActiveUsersCount) * 100, 1) : 0],
                    'shower'  => ['count' => $todayShowerUpdates, 'rate' => $dailyShowerRate],
                ],
                'weekly' => [
                    'periodLabel' => '今週',
                    'english' => ['users' => $weeklyEnglishUsers, 'rate' => $weeklyEnglishRate],
                    'info'    => ['count' => $weeklyPostsCount, 'rate' => $weeklyActiveCount > 0 ? round(($weeklyPostsCount / $weeklyActiveCount) * 100, 1) : 0],
                    'shower'  => ['count' => $weeklyShowerCount, 'rate' => $weeklyActiveCount > 0 ? round(($weeklyShowerCount / $weeklyActiveCount) * 100, 1) : 0],
                ],
                'monthly' => [
                    'periodLabel' => '今月',
                    'english' => ['users' => $monthlyEnglishUsers, 'rate' => $monthlyEnglishRate],
                    'info'    => ['count' => $monthlyPostsCount, 'rate' => $monthlyActiveCount > 0 ? round(($monthlyPostsCount / $monthlyActiveCount) * 100, 1) : 0],
                    'shower'  => ['count' => $monthlyShowerCount, 'rate' => $monthlyActiveCount > 0 ? round(($monthlyShowerCount / $monthlyActiveCount) * 100, 1) : 0],
                ],
                'yearly' => [
                    'periodLabel' => '今年',
                    'english' => ['users' => $yearlyEnglishUsers, 'rate' => $yearlyEnglishRate],
                    'info'    => ['count' => $yearlyPostsCount, 'rate' => $yearlyActiveCount > 0 ? round(($yearlyPostsCount / $yearlyActiveCount) * 100, 1) : 0],
                    'shower'  => ['count' => $yearlyShowerCount, 'rate' => $yearlyActiveCount > 0 ? round(($yearlyShowerCount / $yearlyActiveCount) * 100, 1) : 0],
                ],
            ],
        ];

        // 8. ビューで未定義エラーになりやすい変数のデフォルト値補填
        $defaults = [
            'categoryFormMode'     => 'addMain',
            'dailyCards'           => $periods['daily'],
            'featureAnalyticsData' => $featureAnalyticsData,
            'periods'              => $periods,
            'categoryFormHasError' => session('categoryFormHasError', false),
        ];

        return view('admin.dashboard', array_merge(
            compact(
                'users',
                'notices',
                'adminMessages',
                'adminMainCategories',
                'adminCategories',
                'brokenShowers'
            ),
            $stats,
            $analytics,
            $defaults
        ));
    }

    /**
     * 管理者伝言板の保存処理
     */
    public function storeAdminMessage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // DB保存処理
        AdminMessage::create([
            'user_id' => $validated['user_id'] ?? auth()->id(),
            'message' => $validated['message'],
        ]);

        // 明示的にダッシュボード画面（GET）にリダイレクト
        return redirect()->route('admin.dashboard')->with('success', '伝言を投稿しました。');
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