<?php

namespace App\Http\Controllers\English;

use App\Http\Controllers\Controller;
use App\Services\English\ProgressService;
use App\Services\English\StreakService;
use App\Services\English\StudyLogService;
use App\Services\English\XpService;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function __construct(
        private readonly ProgressService $progressService,
        private readonly StudyLogService $studyLogService,
        private readonly StreakService   $streakService,
        private readonly XpService       $xpService,
    ) {}

    /**
     * 学習管理ダッシュボード (S28)
     * GET /english/progress
     */
    public function index()
    {
        $user = Auth::user();

        // 機能別進捗
        $sectionProgress = $this->progressService->getSectionProgress($user);
        $overallProgress = $this->progressService->getOverallProgress($user);

        // 学習時間（users.total_study_time を直接参照）
        $totalStudyTime  = $this->progressService->getTotalStudyTime($user);
        $studyTimeFormatted = ProgressService::formatStudyTime($totalStudyTime);

        // 累計学習日数
        $totalStudyDays = $this->streakService->getTotalStudyDays($user);

        // 最近の学習履歴（10件）
        $recentLogs = $this->studyLogService->getRecentLogs($user, 10);

        // お気に入り単語数
        $favoritesCount = $user->wordFavorites()->count();

        // XP・Level 情報
        $levelInfo = $this->xpService->getLevelInfo($user);

        return view('english.progress.index', compact(
            'user',
            'levelInfo',
            'sectionProgress',
            'overallProgress',
            'totalStudyTime',
            'studyTimeFormatted',
            'totalStudyDays',
            'recentLogs',
            'favoritesCount',
        ));
    }
}
