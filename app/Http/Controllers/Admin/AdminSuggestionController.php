<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminSuggestionController extends Controller
{
    public function data(Request $request)
    {
        $suggestions = Suggestion::query()
            ->with('user')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'user_name' => $s->user->name ?? '-',
                'category' => $s->category,
                'category_label' => $s->category_label,
                'comment' => $s->comment,
                'status' => $s->status,
                'status_label' => $s->status_label,
                'admin_note' => $s->admin_note,
                'created_at' => $s->created_at->format('Y/m/d H:i'),
            ]);

        return response()->json(['items' => $suggestions]);
    }

    public function update(Request $request, Suggestion $suggestion)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(Suggestion::STATUSES))],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $suggestion->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', '対応状況を更新しました');
    }
}
