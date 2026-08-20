<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function data(Request $request)
    {
        $user = $request->user();
        $gender = $user->gender;

        $notifications = Notification::forGender($gender)
            ->orderByDesc('created_at')
            ->limit(30)
            ->get()
            ->map(fn ($n) => [
                'id' => $n->id,
                'type' => $n->type,
                'message' => $n->message,
                'created_at' => $n->created_at->setTimezone('Asia/Manila')->diffForHumans(),
                'is_unread' => $user->notifications_last_seen_at === null
                    || $n->created_at->greaterThan($user->notifications_last_seen_at),
            ]);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $notifications->where('is_unread', true)->count(),
        ]);
    }

    public function markSeen(Request $request)
    {
        $request->user()->update(['notifications_last_seen_at' => now()]);

        return response()->json(['success' => true]);
    }
}