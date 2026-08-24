@extends('layouts.app')

@section('title', 'マイプロフィール - Kredo Plus')

@section('content')
<div class="bg-slate-50 min-h-[calc(100vh-80px)] py-10 px-4 sm:px-6">
    <div class="max-w-4xl mx-auto space-y-8">

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
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-card border border-slate-100 flex flex-col sm:flex-row items-center sm:items-start gap-6">
            
            <!-- アバターアイコン -->
            <div class="relative group">
                @if ($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover shadow-md ring-4 ring-sky-50">
                @else
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-gradient-to-br from-sky-400 to-brand-blue text-white text-3xl sm:text-4xl font-extrabold flex items-center justify-center shadow-md ring-4 ring-sky-50">
                        {{ Str::of($user->name)->substr(0, 1)->upper() }}
                    </div>
                @endif
            </div>

            <!-- ユーザー詳細情報 -->
            <div class="flex-1 text-center sm:text-left space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="flex items-center justify-center sm:justify-start gap-2">
                            <h1 class="text-2xl font-extrabold text-slate-800">{{ $user->name }}</h1>
                            @if($user->isAdmin())
                                <span class="bg-indigo-100 text-indigo-700 text-xs font-extrabold px-2.5 py-0.5 rounded-full">管理者</span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-400 font-medium">{{ $user->email }}</p>
                    </div>

                    <!-- 操作ボタン群 -->
                    <div class="flex items-center justify-center gap-2 pt-2 sm:pt-0">
                        <!-- 編集モーダル開くボタン -->
                        <button onclick="openProfileModal()"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-blue hover:bg-blue-600 text-white text-xs font-bold rounded-xl shadow-sm transition-all hover:shadow">
                            <span class="material-symbols-outlined !text-base">edit</span>
                            プロフィール編集
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. 留学情報の投稿一覧（投稿した投稿／お気に入り／保存） -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-card border border-slate-100 space-y-6">
            <div class="flex items-center gap-2 text-brand-blue font-extrabold text-lg">
                <span class="material-symbols-outlined">grid_view</span>
                留学情報の投稿
            </div>

            <!-- タブ切り替え -->
            <div class="flex gap-2 bg-slate-50 p-1 rounded-xl max-w-lg">
                @foreach ([
                    'mine' => '投稿した投稿',
                    'liked' => 'お気に入り',
                    'saved' => '保存',
                ] as $tabKey => $tabLabel)
                    <a href="{{ route('profile.edit', array_filter(['post_tab' => $tabKey, 'month' => $calendar['month']->format('Y-m')])) }}"
                       class="flex-1 text-center py-2 text-xs font-bold rounded-lg transition-all {{ $postTab === $tabKey ? 'bg-white shadow-sm text-brand-blue' : 'text-slate-500 hover:text-slate-700' }}">
                        {{ $tabLabel }}
                        <span class="{{ $postTab === $tabKey ? 'text-slate-400' : 'text-slate-400' }}">({{ $postCounts[$tabKey] }})</span>
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
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach ($posts as $post)
                        <a href="{{ route('information.show', $post) }}"
                           class="block rounded-2xl overflow-hidden border border-slate-100 hover:shadow-md transition-all bg-white">
                            <div class="relative h-24 bg-slate-100">
                                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                @if ($post->category)
                                    <span class="absolute top-1.5 left-1.5 text-white text-[10px] font-bold px-2 py-0.5 rounded-full"
                                          style="background-color: {{ $post->category->color() }}">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="p-2.5">
                                <p class="text-xs font-bold text-slate-700 truncate">{{ $post->title }}</p>
                                <div class="flex items-center justify-between mt-1.5">
                                    <span class="text-[11px] text-slate-400 truncate">{{ $post->user->name ?? '不明なユーザー' }}</span>
                                    <span class="flex items-center gap-2 shrink-0 text-slate-400">
                                        <span class="flex items-center gap-0.5 text-[11px]">
                                            <i class="{{ $post->liked_by_me ? 'fa-solid' : 'fa-regular' }} fa-heart text-[#CE7043] text-[11px]"></i>
                                            {{ $post->likes_count }}
                                        </span>
                                        <i class="{{ $post->bookmarked_by_me ? 'fa-solid' : 'fa-regular' }} fa-bookmark text-[11px]"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- 3. マイカレンダー（卒業予定日・課題/イベントメモ） -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-card border border-slate-100 space-y-6">
            <div class="flex items-center gap-2 text-brand-blue font-extrabold text-lg">
                <span class="material-symbols-outlined">calendar_month</span>
                マイカレンダー
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- カレンダー本体 -->
                <div class="flex-1 flex justify-center">
                    <div class="w-full max-w-xs">
                        <div class="flex items-center justify-center gap-1 mb-3">
                            <a href="{{ route('profile.edit', ['month' => $calendar['prevMonth']]) }}"
                               class="p-1.5 rounded-full hover:bg-slate-50 transition-colors">
                                <span class="material-symbols-outlined text-slate-500">chevron_left</span>
                            </a>
                            <span class="text-sm font-bold text-slate-700 w-28 text-center">{{ $calendar['month']->format('Y年n月') }}</span>
                            <a href="{{ route('profile.edit', ['month' => $calendar['nextMonth']]) }}"
                               class="p-1.5 rounded-full hover:bg-slate-50 transition-colors">
                                <span class="material-symbols-outlined text-slate-500">chevron_right</span>
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
                                <span class="w-3 h-3 rounded bg-violet-500 inline-block"></span>
                                卒業予定日
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded bg-amber-100 inline-block"></span>
                                登録メモあり
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded bg-slate-50 ring-2 ring-brand-blue inline-block"></span>
                                今日
                            </span>
                        </div>
                    </div>
                </div>

                <!-- サイドパネル -->
                <div class="flex-1 flex flex-col gap-4 max-w-sm w-full mx-auto">

                    <!-- 卒業予定日 -->
                    <div class="bg-violet-50 border border-violet-100 rounded-2xl p-5">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-bold text-slate-700">卒業予定日</h3>
                            <span class="material-symbols-outlined text-violet-500 !text-lg">flag</span>
                        </div>
                        @if ($user->graduation_date)
                            <p class="text-xs text-slate-500 mb-1">📅 {{ $user->graduation_date->format('Y年n月j日') }}</p>
                            @php $daysLeft = now()->startOfDay()->diffInDays($user->graduation_date, false); @endphp
                            @if ($daysLeft > 0)
                                <p class="text-xl font-extrabold text-violet-600">残り {{ $daysLeft }} 日</p>
                            @elseif ($daysLeft === 0)
                                <p class="text-sm font-bold text-violet-600">本日が卒業予定日です</p>
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
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5">
                        @if ($selectedDate)
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-bold text-slate-700">
                                    {{ \Carbon\Carbon::parse($selectedDate)->format('n月j日') }} のメモ
                                </h3>
                                <a href="{{ route('profile.edit', ['month' => $calendar['month']->format('Y-m')]) }}"
                                   class="text-slate-400 hover:text-slate-600">
                                    <span class="material-symbols-outlined !text-lg">close</span>
                                </a>
                            </div>

                            @forelse ($selectedNotes as $note)
                                <div class="bg-white border border-slate-100 rounded-xl p-3 mb-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="text-sm font-bold text-slate-700">{{ $note->title }}</p>
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
                                        <p class="text-xs text-slate-500 mt-1 whitespace-pre-line">{{ $note->memo }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 mb-3">この日の予定・メモはまだありません。</p>
                            @endforelse

                            <form method="POST" action="{{ route('profile.calendar.notes.store') }}" class="mt-3 space-y-2">
                                @csrf
                                <input type="hidden" name="month" value="{{ $calendar['month']->format('Y-m') }}">
                                <input type="hidden" name="note_date" value="{{ $selectedDate }}">
                                <input type="text" name="title" placeholder="課題・イベント名" maxlength="100" required
                                       class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:border-brand-blue">
                                <textarea name="memo" placeholder="メモ（任意）" rows="2" maxlength="2000"
                                          class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:border-brand-blue"></textarea>
                                @error('title')
                                    <p class="text-xs text-rose-500 font-bold">{{ $message }}</p>
                                @enderror
                                <button type="submit"
                                        class="w-full py-2 bg-brand-blue hover:bg-blue-600 text-white rounded-lg text-xs font-bold transition-colors">
                                    この日に登録する
                                </button>
                            </form>
                        @else
                            <div class="flex flex-col items-center justify-center text-center py-6 gap-2">
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

<!-- 3. プロフィール編集用ポップアップ（モーダル） -->
<div id="edit-profile-modal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative space-y-6">

        <div class="flex justify-between items-center border-b border-slate-100 pb-4">
            <h3 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-blue">manage_accounts</span>
                プロフィール編集
            </h3>
            <button onclick="closeProfileModal()"
                    class="text-slate-400 hover:text-slate-600 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- タブ切り替え -->
        <div class="flex gap-2 bg-slate-50 p-1 rounded-xl">
            <button type="button" id="tab-btn-info" onclick="switchProfileTab('info')"
                    class="flex-1 py-2 text-xs font-bold rounded-lg transition-all">
                基本情報
            </button>
            <button type="button" id="tab-btn-password" onclick="switchProfileTab('password')"
                    class="flex-1 py-2 text-xs font-bold rounded-lg transition-all">
                パスワード変更
            </button>
        </div>

        <!-- 基本情報タブ -->
        <div id="tab-info">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('patch')

                <!-- プロフィール写真 -->
                <div class="flex items-center gap-4">
                    <img id="photo-preview" alt="{{ $user->name }}"
                         src="{{ $user->avatar_url }}"
                         class="w-16 h-16 rounded-full object-cover bg-slate-100 {{ $user->avatar_url ? '' : 'hidden' }}">
                    <div id="photo-preview-placeholder"
                         class="w-16 h-16 rounded-full bg-gradient-to-br from-sky-400 to-brand-blue text-white text-xl font-extrabold flex items-center justify-center {{ $user->avatar_url ? 'hidden' : '' }}">
                        {{ Str::of($user->name)->substr(0, 1)->upper() }}
                    </div>
                    <div>
                        <label class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl cursor-pointer transition-all">
                            <span class="material-symbols-outlined !text-base">photo_camera</span>
                            写真を変更
                            <input type="file" name="photo" accept="image/png,image/jpeg,image/webp" class="hidden" onchange="previewProfilePhoto(this)">
                        </label>
                        <p class="text-[11px] text-slate-400 mt-1 font-medium">JPEG・PNG・WebP / 2MBまで</p>
                        @error('photo')
                            <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- アカウント名 -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">お名前</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue" required>
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- メールアドレス -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">メールアドレス</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue" required>
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button"
                            onclick="closeProfileModal()"
                            class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-all">
                        キャンセル
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 text-xs font-bold bg-brand-blue hover:bg-blue-600 text-white rounded-xl shadow-sm hover:shadow transition-all">
                        変更を保存
                    </button>
                </div>
            </form>
        </div>

        <!-- パスワード変更タブ -->
        <div id="tab-password" class="hidden">
            <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('put')

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">現在のパスワード</label>
                    <input type="password" name="current_password" autocomplete="current-password"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue" required>
                    @error('current_password', 'updatePassword')
                        <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">新しいパスワード</label>
                    <input type="password" name="password" autocomplete="new-password"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue" required>
                    @error('password', 'updatePassword')
                        <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">新しいパスワード（確認）</label>
                    <input type="password" name="password_confirmation" autocomplete="new-password"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue" required>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button"
                            onclick="closeProfileModal()"
                            class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-all">
                        キャンセル
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 text-xs font-bold bg-brand-blue hover:bg-blue-600 text-white rounded-xl shadow-sm hover:shadow transition-all">
                        パスワードを更新
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- モーダル操作用スクリプト -->
<script>
    function openProfileModal(tab = 'info') {
        document.getElementById('edit-profile-modal').classList.remove('hidden');
        switchProfileTab(tab);
    }

    function closeProfileModal() {
        document.getElementById('edit-profile-modal').classList.add('hidden');
    }

    function switchProfileTab(tab) {
        const isInfo = tab === 'info';
        document.getElementById('tab-info').classList.toggle('hidden', !isInfo);
        document.getElementById('tab-password').classList.toggle('hidden', isInfo);

        const infoBtn = document.getElementById('tab-btn-info');
        const passwordBtn = document.getElementById('tab-btn-password');
        infoBtn.classList.toggle('bg-white', isInfo);
        infoBtn.classList.toggle('shadow-sm', isInfo);
        infoBtn.classList.toggle('text-brand-blue', isInfo);
        infoBtn.classList.toggle('text-slate-500', !isInfo);
        passwordBtn.classList.toggle('bg-white', !isInfo);
        passwordBtn.classList.toggle('shadow-sm', !isInfo);
        passwordBtn.classList.toggle('text-brand-blue', !isInfo);
        passwordBtn.classList.toggle('text-slate-500', isInfo);
    }

    function previewProfilePhoto(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.getElementById('photo-preview');
            img.src = e.target.result;
            img.classList.remove('hidden');
            document.getElementById('photo-preview-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }

    document.addEventListener('DOMContentLoaded', function () {
        switchProfileTab('info');
        @if ($errors->updatePassword->isNotEmpty())
            openProfileModal('password');
        @elseif ($errors->any())
            openProfileModal('info');
        @endif
    });
</script>
@endsection