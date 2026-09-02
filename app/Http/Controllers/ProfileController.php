<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\CalendarNote;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the specified user's profile.
     */
    public function show(Request $request, User $user): View|RedirectResponse
    {
        // 自分のIDが渡された場合は自身の編集画面へリダイレクト
        if ($user->id === $request->user()?->id) {
            return redirect()->route('profile.edit');
        }

        $calendar = $this->buildCalendar($user, $request->query('month'));

        // 他人のプロフィール閲覧時は、本人の投稿（mine）のみに限定する
        $postTab = 'mine';

        $currentUserId = $request->user()?->id;

        $posts = $this->buildPostQuery($user, $postTab)
            ->with([
                'user:id,name,avatar',
                'category',
                'earthLocation',
                'comments' => fn ($q) => $q->with('user:id,name')->oldest(),
                'likes' => fn ($q) => $q->where('user_id', $currentUserId),
                'bookmarks' => fn ($q) => $q->where('user_id', $currentUserId),
            ])
            ->withCount('likes')
            ->latest()
            ->limit(12)
            ->get();

        $categoryColors = Category::all()
            ->mapWithKeys(fn ($c) => [$c->name => ['bg' => $c->backgroundColor(), 'text' => $c->textColor()]]);

        return view('profile.partials.show', [
            'user' => $user,
            'calendar' => $calendar,
            'posts' => $posts,
            'categoryColors' => $categoryColors,
        ]);
    }
    
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $calendar = $this->buildCalendar($user, $request->query('month'));

        $selectedDate = $request->query('date');
        $selectedNotes = $selectedDate
            ? $user->calendarNotes()->whereDate('note_date', $selectedDate)->oldest()->get()
            : collect();

        $postTab = $request->query('post_tab', 'mine');
        if (!in_array($postTab, ['mine', 'liked', 'saved'], true)) {
            $postTab = 'mine';
        }

        $postCounts = [
            'mine' => $user->posts()->count(),
            'liked' => $user->likes()->count(),
            'saved' => $user->bookmarks()->count(),
        ];

        $posts = $this->buildPostQuery($user, $postTab)
            ->with([
                'user:id,name,avatar',
                'category',
                'earthLocation',
                'comments' => fn ($q) => $q->with('user:id,name')->oldest(),
                'likes' => fn ($q) => $q->where('user_id', $user->id),
                'bookmarks' => fn ($q) => $q->where('user_id', $user->id),
            ])
            ->withCount('likes')
            ->latest()
            ->limit(12)
            ->get();

        // バッジ・タグの色(背景+文字)をカテゴリー名ごとに引けるようにしておく(JSのモーダル側で使用)
        $categoryColors = Category::all()
            ->mapWithKeys(fn ($c) => [$c->name => ['bg' => $c->backgroundColor(), 'text' => $c->textColor()]]);

        return view('profile.edit', [
            'user' => $user,
            'calendar' => $calendar,
            'selectedDate' => $selectedDate,
            'selectedNotes' => $selectedNotes,
            'postTab' => $postTab,
            'postCounts' => $postCounts,
            'posts' => $posts,
            'categoryColors' => $categoryColors,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->safe()->except('photo'));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('photo')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $request->file('photo')->store('avatars', 'public');
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * 卒業予定日の登録・更新・取り消し（生徒本人が設定する）
     */
    public function updateGraduationDate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'graduation_date' => ['nullable', 'date'],
            'month' => ['nullable', 'string'],
        ]);

        $request->user()->update(['graduation_date' => $validated['graduation_date'] ?? null]);

        return Redirect::route('profile.edit', array_filter(['month' => $validated['month'] ?? null]));
    }

    /**
     * マイカレンダーへの課題・イベントメモの登録
     */
    public function storeCalendarNote(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'note_date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:100'],
            'memo' => ['nullable', 'string', 'max:2000'],
            'month' => ['nullable', 'string'],
        ]);

        $request->user()->calendarNotes()->create([
            'note_date' => $validated['note_date'],
            'title' => $validated['title'],
            'memo' => $validated['memo'] ?? null,
        ]);

        return Redirect::route('profile.edit', array_filter([
            'month' => $validated['month'] ?? null,
            'date' => $validated['note_date'],
        ]));
    }

    /**
     * マイカレンダーのメモ削除
     */
    public function destroyCalendarNote(Request $request, CalendarNote $calendarNote): RedirectResponse
    {
        abort_unless($calendarNote->user_id === $request->user()->id, 403);

        $noteDate = $calendarNote->note_date->format('Y-m-d');
        $calendarNote->delete();

        return Redirect::route('profile.edit', array_filter([
            'month' => $request->input('month'),
            'date' => $noteDate,
        ]));
    }

    /**
     * プロフィール画面の「投稿一覧」タブ切り替え用のクエリを組み立てる。
     * mine: 自分が投稿した投稿 / liked: いいねした投稿 / saved: 保存した投稿
     */
    private function buildPostQuery(User $user, string $postTab)
    {
        return match ($postTab) {
            'liked' => Post::whereHas('likes', fn ($q) => $q->where('user_id', $user->id)),
            'saved' => Post::whereHas('bookmarks', fn ($q) => $q->where('user_id', $user->id)),
            default => Post::where('user_id', $user->id),
        };
    }

    /**
     * プロフィール画面のマイカレンダー用データを組み立てる。
     * 卒業予定日と、その月に登録されたメモの有無を各セルにマークする。
     *
     * @return array{month: Carbon, weeks: array, prevMonth: string, nextMonth: string}
     */
    private function buildCalendar(User $user, ?string $monthParam): array
    {
        $month = (is_string($monthParam) && preg_match('/^\d{4}-\d{2}$/', $monthParam))
            ? Carbon::createFromFormat('Y-m-d', $monthParam . '-01')
            : Carbon::now();
        $month->startOfMonth();

        $noteDates = $user->calendarNotes()
            ->whereYear('note_date', $month->year)
            ->whereMonth('note_date', $month->month)
            ->pluck('note_date')
            ->map(fn ($d) => $d->format('Y-m-d'))
            ->unique()
            ->all();

        $graduationDate = $user->graduation_date?->format('Y-m-d');

        $firstCell = $month->copy()->startOfWeek(Carbon::SUNDAY);
        $lastCell = $month->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

        $weeks = [];
        $week = [];
        $cursor = $firstCell->copy();

        while ($cursor->lte($lastCell)) {
            $dateStr = $cursor->format('Y-m-d');

            $week[] = [
                'date' => $cursor->copy(),
                'inMonth' => $cursor->month === $month->month,
                'isToday' => $cursor->isToday(),
                'isGraduation' => $graduationDate === $dateStr,
                'hasNote' => in_array($dateStr, $noteDates, true),
            ];

            if ($cursor->dayOfWeek === Carbon::SATURDAY) {
                $weeks[] = $week;
                $week = [];
            }

            $cursor->addDay();
        }

        return [
            'month' => $month,
            'weeks' => $weeks,
            'prevMonth' => $month->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $month->copy()->addMonth()->format('Y-m'),
        ];
    }
}