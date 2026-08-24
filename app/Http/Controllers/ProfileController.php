<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\CalendarNote;
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

        return view('profile.edit', [
            'user' => $user,
            'calendar' => $calendar,
            'selectedDate' => $selectedDate,
            'selectedNotes' => $selectedNotes,
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
