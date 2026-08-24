<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Notice;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function data(Request $request)
    {
        $user = $request->user();
        $gender = $user->gender;
        $lastSeenAt = $user->notifications_last_seen_at;

        // 1. シャワーセクション等のシステム通知を取得
        $systemNotifications = Notification::forGender($gender)
            ->orderByDesc('created_at')
            ->limit(30)
            ->get()
            ->map(fn ($n) => [
                'id'         => 'system_' . $n->id,
                'type'       => $n->type, // 例: malfunction, congestion 等
                'title'      => 'シャワーお知らせ',
                'message'    => $n->message,
                'created_at' => $n->created_at,
                'is_unread'  => $lastSeenAt === null || $n->created_at->greaterThan($lastSeenAt),
            ]);

        // 2. 管理画面から送信された全体お知らせ（Notice）を取得
        $adminNotices = Notice::orderByDesc('created_at')
            ->limit(30)
            ->get()
            ->map(fn ($n) => [
                'id'         => 'notice_' . $n->id,
                'type'       => 'notice', // 管理者お知らせ用識別子
                'category'   => $n->category, // 重要, イベント, 生活 等
                'title'      => $n->title,
                'message'    => $n->content,
                'created_at' => $n->created_at,
                'is_unread'  => $lastSeenAt === null || $n->created_at->greaterThan($lastSeenAt),
            ]);

        // 3. 2つのコレクションを統合し、作成日時の降順（最新順）に並び替えて最大30件に絞り込む
        $allNotifications = $systemNotifications
            ->concat($adminNotices)
            ->sortByDesc('created_at')
            ->take(30)
            ->values()
            ->map(function ($item) {
                // フロント表示用に diffForHumans の文字列に変換
                $item['created_at'] = $item['created_at']->setTimezone('Asia/Manila')->diffForHumans();
                return $item;
            });

        return response()->json([
            'notifications' => $allNotifications,
            'unread_count'  => $allNotifications->where('is_unread', true)->count(),
        ]);
    }

    public function markSeen(Request $request)
    {
        $request->user()->update(['notifications_last_seen_at' => now()]);

        return response()->json(['success' => true]);
    }
}