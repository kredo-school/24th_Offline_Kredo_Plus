<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Notice;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * 件数設定
     */
    private const FETCH_LIMIT = 10;   // 個別取得件数
    private const DISPLAY_LIMIT = 5;  // フロントエンド返却最大件数（5件に制限）

    public function data(Request $request)
    {
        $user = $request->user();
        $gender = $user->gender;
        $lastSeenAt = $user->notifications_last_seen_at;

        // 1. シャワー等のシステム通知を取得
        $systemNotifications = Notification::forGender($gender)
            ->latest()
            ->limit(self::FETCH_LIMIT)
            ->get()
            ->map(fn ($n) => [
                'id'         => 'system_' . $n->id,
                'type'       => $n->type,
                'category'   => 'シャワー',
                'title'      => 'シャワーお知らせ',
                'message'    => $n->message,
                'created_at' => $n->created_at,
                'is_unread'  => $lastSeenAt === null || $n->created_at->greaterThan($lastSeenAt),
            ]);

        // 2. 管理画面から配信されたお知らせ（Notice）を取得
        $adminNotices = Notice::latest()
            ->limit(self::FETCH_LIMIT)
            ->get()
            ->map(fn ($n) => [
                'id'         => 'notice_' . $n->id,
                'type'       => 'notice',
                'category'   => $n->category ?? 'お知らせ',
                'title'      => $n->title,
                'message'    => $n->content,
                'created_at' => $n->created_at,
                'is_unread'  => $lastSeenAt === null || $n->created_at->greaterThan($lastSeenAt),
            ]);

        // 3. 2つの通知を統合し、最新順にソートして最大5件に絞り込み
        $allNotifications = $systemNotifications
            ->concat($adminNotices)
            ->sortByDesc('created_at')
            ->take(self::DISPLAY_LIMIT)
            ->values();

        // 4. 未読件数を集計（5件に絞り込んだ中の未読数）
        $unreadCount = $allNotifications->where('is_unread', true)->count();

        // 5. 日時表示のフォーマット変換（フィリピン時間基準）
        $formattedNotifications = $allNotifications->map(function ($item) {
            $item['created_at'] = $item['created_at']->setTimezone('Asia/Manila')->diffForHumans();
            return $item;
        });

        return response()->json([
            'notifications' => $formattedNotifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    public function markSeen(Request $request)
    {
        $request->user()->update([
            'notifications_last_seen_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}