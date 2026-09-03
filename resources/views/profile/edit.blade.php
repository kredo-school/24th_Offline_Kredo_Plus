@extends('layouts.app')

@section('title', 'マイプロフィール - Kredo Plus')

@section('content')
<div class="bg-slate-50 min-h-[calc(100vh-80px)] py-8 px-4 sm:px-6">
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- フラッシュメッセージ -->
        @if (session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                <span class="text-sm font-bold">
                    @switch(session('status'))
                        @case('profile-updated')
                            プロフィールを更新しました！
                            @break
                        @case('password-updated')
                            パスワードを更新しました！
                            @break
                        @default
                            {{ session('status') }}
                    @endswitch
                </span>
            </div>
        @endif

        <!-- 1. プロフィールカード（ヘッダー） -->
        <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-100 flex items-center gap-4 sm:gap-6">
            
            <!-- アバターアイコン -->
            <div class="relative shrink-0">
                @if ($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover shadow-sm ring-2 ring-sky-50">
                @else
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-sky-400 to-brand-blue text-white text-2xl sm:text-3xl font-extrabold flex items-center justify-center shadow-sm ring-2 ring-sky-50">
                        {{ Str::of($user->name)->substr(0, 1)->upper() }}
                    </div>
                @endif
            </div>

            <!-- ユーザー詳細情報 -->
            <div class="flex-1 min-w-0 space-y-1">
                <div class="flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight leading-none truncate">
                                {{ $user->name }}
                            </h1>
                            @if($user->isAdmin())
                                <span class="bg-indigo-50 text-indigo-600 border border-indigo-100 text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0">
                                    管理者
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400 font-medium mt-1 truncate">{{ $user->email }}</p>
                    </div>

                    <!-- 操作ボタン群 -->
                    <div class="flex items-center gap-2 shrink-0">
                        <button onclick="openProfileModal()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-brand-blue hover:bg-blue-600 text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                            <span class="material-symbols-outlined !text-base">edit</span>
                            プロフィール編集
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. 留学情報の投稿一覧（投稿した投稿／お気に入り／保存） -->
        <div id="post-list" class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-100 space-y-5 scroll-mt-24">
            <div class="flex items-center gap-2 text-brand-blue font-extrabold text-base sm:text-lg">
                <span class="material-symbols-outlined">grid_view</span>
                留学情報の投稿
            </div>

            <!-- タブ切り替え -->
            <div class="flex gap-1.5 bg-slate-50 p-1 rounded-xl max-w-md">
                @foreach ([
                    'mine' => '投稿した投稿',
                    'liked' => 'お気に入り',
                    'saved' => '保存',
                ] as $tabKey => $tabLabel)
                    <a href="{{ route('profile.edit', array_filter(['post_tab' => $tabKey, 'month' => $calendar['month']->format('Y-m')])) }}#post-list"
                       class="flex-1 text-center py-1.5 text-xs font-bold rounded-lg transition-all {{ $postTab === $tabKey ? 'bg-white shadow-sm text-brand-blue' : 'text-slate-500 hover:text-slate-700' }}">
                        {{ $tabLabel }}
                        <span class="text-slate-400">({{ $postCounts[$tabKey] }})</span>
                    </a>
                @endforeach
            </div>

            <!-- 投稿グリッド -->
            @if ($posts->isEmpty())
                <div class="flex flex-col items-center justify-center text-center py-10 gap-2">
                    <span class="material-symbols-outlined text-slate-300 !text-4xl">
                        @if ($postTab === 'liked') favorite
                        @elseif ($postTab === 'saved') bookmark
                        @else post_add
                        @endif
                    </span>
                    <p class="text-sm text-slate-400">
                        @switch($postTab)
                            @case('liked')
                                まだいいねした投稿がありません。
                                @break
                            @case('saved')
                                まだ保存した投稿がありません。
                                @break
                            @default
                                まだ投稿がありません。
                        @endswitch
                    </p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3.5 sm:gap-4">
                    @foreach ($posts as $post)
                        <a href="#" data-post-id="{{ $post->id }}" onclick="event.preventDefault(); openPostModal({{ $post->id }})"
                           class="post-card block rounded-xl overflow-hidden border border-slate-100 hover:shadow-md transition-all bg-white">
                            <div class="relative h-24 sm:h-28 bg-slate-100">
                                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                @if ($post->category)
                                    <span class="absolute top-1.5 left-1.5 text-[10px] font-bold px-2 py-0.5 rounded-full"
                                          style="background:{{ $post->category->backgroundColor() }}; color:{{ $post->category->textColor() }}">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="p-2.5">
                                <p class="text-xs font-bold text-slate-700 truncate">{{ $post->title }}</p>
                                <div class="flex items-center justify-between mt-1.5">
                                    <span class="text-[11px] text-slate-400 truncate">{{ $post->user->name ?? '不明なユーザー' }}</span>
                                    <span class="flex items-center gap-2 shrink-0 text-slate-400">
                                        <span class="post-card-like flex items-center gap-0.5 text-[11px]">
                                            <i class="{{ $post->liked_by_me ? 'fa-solid' : 'fa-regular' }} fa-heart text-[#CE7043] text-[11px]"></i>
                                            <span class="post-card-like-count">{{ $post->likes_count }}</span>
                                        </span>
                                        <i class="post-card-bookmark {{ $post->bookmarked_by_me ? 'fa-solid' : 'fa-regular' }} fa-bookmark text-[11px]"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if ($posts->hasPages())
                    <div class="pt-2 flex items-center justify-between gap-3 text-xs text-slate-400 font-medium">
                        <span>
                            {{ $posts->firstItem() }}–{{ $posts->lastItem() }} / 全 {{ $posts->total() }} 件
                        </span>
                        <div class="flex items-center gap-1.5">
                            @if ($posts->onFirstPage())
                                <span class="px-3 py-1.5 rounded-lg bg-slate-50 text-slate-300 cursor-not-allowed">前へ</span>
                            @else
                                <a href="{{ $posts->previousPageUrl() }}#post-list"
                                   class="px-3 py-1.5 rounded-lg bg-slate-50 text-slate-600 font-bold hover:bg-slate-100 transition-all">前へ</a>
                            @endif
                            <span class="px-2 text-slate-500 font-bold">{{ $posts->currentPage() }} / {{ $posts->lastPage() }}</span>
                            @if ($posts->hasMorePages())
                                <a href="{{ $posts->nextPageUrl() }}#post-list"
                                   class="px-3 py-1.5 rounded-lg bg-slate-50 text-slate-600 font-bold hover:bg-slate-100 transition-all">次へ</a>
                            @else
                                <span class="px-3 py-1.5 rounded-lg bg-slate-50 text-slate-300 cursor-not-allowed">次へ</span>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <!-- 3. マイカレンダー（卒業予定日・課題/イベントメモ） -->
        <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-100 space-y-5">
            <div class="flex items-center gap-2 text-brand-blue font-extrabold text-base sm:text-lg">
                <span class="material-symbols-outlined">calendar_month</span>
                マイカレンダー
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- カレンダー本体 -->
                <div class="flex-1 flex justify-center">
                    <div class="w-full max-w-xs">
                        <div class="flex items-center justify-center gap-1 mb-3">
                            <a href="{{ route('profile.edit', ['month' => $calendar['prevMonth']]) }}"
                               class="p-1 rounded-full hover:bg-slate-50 transition-colors">
                                <span class="material-symbols-outlined text-slate-500 !text-xl">chevron_left</span>
                            </a>
                            <span class="text-sm font-bold text-slate-700 w-28 text-center">{{ $calendar['month']->format('Y年n月') }}</span>
                            <a href="{{ route('profile.edit', ['month' => $calendar['nextMonth']]) }}"
                               class="p-1 rounded-full hover:bg-slate-50 transition-colors">
                                <span class="material-symbols-outlined text-slate-500 !text-xl">chevron_right</span>
                            </a>
                        </div>

                        <div class="grid grid-cols-7 gap-1.5 mb-1.5">
                            @foreach (['日', '月', '火', '水', '木', '金', '土'] as $dow)
                                <div class="text-center text-[11px] text-slate-400 font-bold">{{ $dow }}</div>
                            @endforeach
                        </div>

                        <div class="space-y-1.5">
                            @foreach ($calendar['weeks'] as $week)
                                <div class="grid grid-cols-7 gap-1.5">
                                    @foreach ($week as $cell)
                                        @php $dateStr = $cell['date']->format('Y-m-d'); @endphp
                                        @if ($cell['inMonth'])
                                            <a href="{{ route('profile.edit', array_filter(['month' => $calendar['month']->format('Y-m'), 'date' => $dateStr])) }}"
                                               class="aspect-square rounded-lg flex items-center justify-center text-xs relative transition-colors
                                                   {{ $cell['isGraduation']
                                                       ? 'bg-violet-500 text-white font-bold'
                                                       : ($cell['hasNote'] ? 'bg-amber-100 text-slate-700 font-bold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100') }}
                                                   {{ $cell['isToday'] ? 'ring-2 ring-brand-blue' : '' }}
                                                   {{ $selectedDate === $dateStr ? 'ring-2 ring-rose-400' : '' }}">
                                                {{ $cell['date']->day }}
                                            </a>
                                        @else
                                            <div class="aspect-square rounded-lg flex items-center justify-center text-xs text-slate-300">
                                                {{ $cell['date']->day }}
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-4 text-[11px] text-slate-500">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded bg-violet-500 inline-block"></span>
                                卒業予定日
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded bg-amber-100 inline-block"></span>
                                登録メモあり
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded bg-slate-50 ring-2 ring-brand-blue inline-block"></span>
                                今日
                            </span>
                        </div>
                    </div>
                </div>

                <!-- サイドパネル -->
                <div class="flex-1 flex flex-col gap-4 max-w-sm w-full mx-auto">

                    <!-- 卒業予定日 -->
                    <div class="bg-violet-50/80 border border-violet-100 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-1.5">
                            <h3 class="text-xs font-bold text-slate-700">卒業予定日</h3>
                            <span class="material-symbols-outlined text-violet-500 !text-base">flag</span>
                        </div>
                        @if ($user->graduation_date)
                            <p class="text-xs text-slate-500 mb-1">{{ $user->graduation_date->format('Y年n月j日') }}</p>
                            @php $daysLeft = now()->startOfDay()->diffInDays($user->graduation_date, false); @endphp
                            @if ($daysLeft > 0)
                                <p class="text-lg font-extrabold text-violet-600">残り {{ $daysLeft }} 日</p>
                            @elseif ($daysLeft === 0)
                                <p class="text-xs font-bold text-violet-600">本日が卒業予定日です</p>
                            @else
                                <p class="text-xs text-slate-400">卒業予定日を過ぎています</p>
                            @endif
                        @else
                            <p class="text-xs text-slate-400 mb-1">未設定</p>
                        @endif
                        <form method="POST" action="{{ route('profile.graduation-date') }}" class="mt-3 flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="month" value="{{ $calendar['month']->format('Y-m') }}">
                            <input type="date" name="graduation_date" value="{{ $user->graduation_date?->format('Y-m-d') }}"
                                   class="flex-1 min-w-0 text-xs border border-slate-200 rounded-lg px-2 py-1.5 bg-white focus:outline-none focus:border-brand-blue">
                            <button type="submit"
                                    class="px-3 py-1.5 bg-violet-500 hover:bg-violet-600 text-white rounded-lg text-xs font-bold shrink-0 transition-colors">
                                保存
                            </button>
                            <button type="submit"
                                    onclick="if (!confirm('卒業予定日を取り消しますか？')) return false; this.form.graduation_date.value = '';"
                                    class="px-3 py-1.5 border border-violet-200 text-violet-600 rounded-lg text-xs font-bold shrink-0 hover:bg-violet-100 transition-colors">
                                取り消し
                            </button>
                        </form>
                    </div>

                    <!-- 日付ごとのメモ -->
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                        @if ($selectedDate)
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-xs font-bold text-slate-700">
                                    {{ \Carbon\Carbon::parse($selectedDate)->format('n月j日') }} のメモ
                                </h3>
                                <a href="{{ route('profile.edit', ['month' => $calendar['month']->format('Y-m')]) }}"
                                   class="text-slate-400 hover:text-slate-600">
                                    <span class="material-symbols-outlined !text-base">close</span>
                                </a>
                            </div>

                            @forelse ($selectedNotes as $note)
                                <div class="bg-white border border-slate-100 rounded-lg p-2.5 mb-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="text-xs font-bold text-slate-700">{{ $note->title }}</p>
                                        <form method="POST" action="{{ route('profile.calendar.notes.destroy', $note) }}"
                                              onsubmit="return confirm('このメモを削除しますか？')">
                                            @csrf
                                            @method('delete')
                                            <input type="hidden" name="month" value="{{ $calendar['month']->format('Y-m') }}">
                                            <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors">
                                                <span class="material-symbols-outlined !text-base">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                    @if ($note->memo)
                                        <p class="text-[11px] text-slate-500 mt-1 whitespace-pre-line">{{ $note->memo }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 mb-2">この日の予定・メモはまだありません。</p>
                            @endforelse

                            <form method="POST" action="{{ route('profile.calendar.notes.store') }}" class="mt-2 space-y-2">
                                @csrf
                                <input type="hidden" name="month" value="{{ $calendar['month']->format('Y-m') }}">
                                <input type="hidden" name="note_date" value="{{ $selectedDate }}">
                                <input type="text" name="title" placeholder="課題・イベント名" maxlength="100" required
                                       class="w-full text-xs border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-brand-blue">
                                <textarea name="memo" placeholder="メモ（任意）" rows="2" maxlength="2000"
                                          class="w-full text-xs border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-brand-blue"></textarea>
                                @error('title')
                                    <p class="text-xs text-rose-500 font-bold">{{ $message }}</p>
                                @enderror
                                <button type="submit"
                                        class="w-full py-1.5 bg-brand-blue hover:bg-blue-600 text-white rounded-lg text-xs font-bold transition-colors">
                                    この日に登録する
                                </button>
                            </form>
                        @else
                            <div class="flex flex-col items-center justify-center text-center py-5 gap-1.5">
                                <span class="material-symbols-outlined text-slate-300 !text-3xl">edit_calendar</span>
                                <p class="text-xs text-slate-400">カレンダーの日付をクリックすると、<br>課題やイベントを登録・確認できます。</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- 4. プロフィール編集用ポップアップ（モーダル） -->
<div id="edit-profile-modal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] p-5 sm:p-6 shadow-2xl relative space-y-5 overflow-y-auto">

        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-blue">manage_accounts</span>
                プロフィール編集
            </h3>
            <button onclick="closeProfileModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- タブ切り替え -->
        <div class="flex gap-1.5 bg-slate-50 p-1 rounded-xl">
            <button type="button" id="tab-btn-info" onclick="switchProfileTab('info')"
                    class="flex-1 py-1.5 text-xs font-bold rounded-lg transition-all">
                基本情報
            </button>
            <button type="button" id="tab-btn-preference" onclick="switchProfileTab('preference')"
                    class="flex-1 py-1.5 text-xs font-bold rounded-lg transition-all">
                シャワーの好み
            </button>
            <button type="button" id="tab-btn-password" onclick="switchProfileTab('password')"
                    class="flex-1 py-1.5 text-xs font-bold rounded-lg transition-all">
                パスワード変更
            </button>
        </div>

        <!-- 基本情報タブ -->
        <div id="tab-info" class="min-h-[20rem]">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('patch')

                <!-- プロフィール写真 -->
                <div class="flex items-center gap-4">
                    <img id="photo-preview" alt="{{ $user->name }}"
                         src="{{ $user->avatar_url }}"
                         class="w-14 h-14 rounded-full object-cover bg-slate-100 {{ $user->avatar_url ? '' : 'hidden' }}">
                    <div id="photo-preview-placeholder"
                         class="w-14 h-14 rounded-full bg-gradient-to-br from-sky-400 to-brand-blue text-white text-lg font-extrabold flex items-center justify-center {{ $user->avatar_url ? 'hidden' : '' }}">
                        {{ Str::of($user->name)->substr(0, 1)->upper() }}
                    </div>
                    <div>
                        <label class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl cursor-pointer transition-all">
                            <span class="material-symbols-outlined !text-base">photo_camera</span>
                            写真を変更
                            <input type="file" name="photo" accept="image/png,image/jpeg,image/webp" class="hidden" onchange="previewProfilePhoto(this)">
                        </label>
                        <p class="text-[10px] text-slate-400 mt-1 font-medium">JPEG・PNG・WebP / 2MBまで</p>
                        @error('photo')
                            <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- アカウント名 -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">お名前</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue" required>
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- メールアドレス -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">メールアドレス</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue" required>
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" onclick="closeProfileModal()"
                            class="px-4 py-2 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-all">
                        キャンセル
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-xs font-bold bg-brand-blue hover:bg-blue-600 text-white rounded-xl shadow-sm transition-all">
                        変更を保存
                    </button>
                </div>
            </form>
        </div>

        <!-- シャワーの好みタブ -->
        <div id="tab-preference" class="hidden space-y-4 min-h-[20rem]">
            <form action="{{ route('shower.preference.update') }}" method="POST" class="space-y-4">
                @csrf
                {{-- 選択中の色は /shower/male のモーダルと統一（温度=各色、水圧=青系グラデーション）。
                     Tailwind の動的 arbitrary class は JIT で拾えないため、CSS変数 + 下部の <style> で着色する。 --}}
                <div>
                    <p class="mb-2 text-xs font-bold text-slate-600">温度</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach (['冷たい' => '#60a5fa', 'ぬるい' => '#34d399', '温かい' => '#fbbf24', '熱い' => '#ef4444'] as $label => $color)
                            <label class="cursor-pointer">
                                <input type="radio" name="temperature" value="{{ $label }}" class="peer hidden pref-input"
                                    {{ auth()->user()->preferred_temperature_label === $label ? 'checked' : '' }}>
                                <span class="pref-chip flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-500 transition-all hover:bg-slate-100"
                                      style="--pref-color: {{ $color }};">
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="mb-2 text-xs font-bold text-slate-600">水圧</p>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach (['弱い' => '#93c5fd', '普通' => '#3b82f6', '強い' => '#1e3a8a'] as $label => $color)
                            <label class="cursor-pointer">
                                <input type="radio" name="pressure" value="{{ $label }}" class="peer hidden pref-input"
                                    {{ auth()->user()->preferred_pressure_label === $label ? 'checked' : '' }}>
                                <span class="pref-chip flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-500 transition-all hover:bg-slate-100"
                                      style="--pref-color: {{ $color }};">
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" onclick="closeProfileModal()"
                            class="px-4 py-2 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-all">
                        キャンセル
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-xs font-bold bg-brand-blue hover:bg-blue-600 text-white rounded-xl shadow-sm transition-all">
                        設定を保存
                    </button>
                </div>
            </form>
        </div>

        <!-- パスワード変更タブ -->
        <div id="tab-password" class="hidden space-y-4 min-h-[20rem]">
            <form action="{{ route('password.update') }}" method="POST" class="space-y-3">
                @csrf
                @method('put')

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">現在のパスワード</label>
                    <input type="password" name="current_password"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">新しいパスワード</label>
                    <input type="password" name="password"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">新しいパスワード（確認）</label>
                    <input type="password" name="password_confirmation"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue" required>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" onclick="closeProfileModal()"
                            class="px-4 py-2 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-all">
                        キャンセル
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-xs font-bold bg-brand-blue hover:bg-blue-600 text-white rounded-xl shadow-sm transition-all">
                        パスワードを変更
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<style>
    /* シャワーの好み: 選択中のチップを /shower/male のモーダルと同じ配色で着色する */
    #edit-profile-modal .pref-input:checked + .pref-chip {
        background-color: var(--pref-color);
        border-color: var(--pref-color);
        color: #fff;
    }
</style>

<script>
    function openProfileModal() {
        document.getElementById('edit-profile-modal').classList.remove('hidden');
        switchProfileTab('info');
    }

    function closeProfileModal() {
        document.getElementById('edit-profile-modal').classList.add('hidden');
    }

    function switchProfileTab(tab) {
        const tabs = ['info', 'preference', 'password'];
        tabs.forEach(t => {
            const el = document.getElementById(`tab-${t}`);
            const btn = document.getElementById(`tab-btn-${t}`);
            if (t === tab) {
                el.classList.remove('hidden');
                btn.className = 'flex-1 py-1.5 text-xs font-bold rounded-lg transition-all bg-white shadow-sm text-brand-blue';
            } else {
                el.classList.add('hidden');
                btn.className = 'flex-1 py-1.5 text-xs font-bold rounded-lg transition-all text-slate-500 hover:text-slate-700';
            }
        });
    }

    function previewProfilePhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('photo-preview');
                const placeholder = document.getElementById('photo-preview-placeholder');
                img.src = e.target.result;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection