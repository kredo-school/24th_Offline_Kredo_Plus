@extends('layouts.app')

@section('content')
<!-- x-data で「今どのタブ（メニュー）を開いているか」を管理します -->
@php
    $categoryFormHasError = $errors->addMain->any() || $errors->addSub->any() || $errors->editMain->any() || $errors->editSub->any();
    $categoryFormMode = $errors->addSub->any() ? 'addSub' : ($errors->editMain->any() ? 'editMain' : ($errors->editSub->any() ? 'editSub' : 'addMain'));
@endphp
<div x-data="{ currentTab: '{{ session('accountCreated') || $errors->default->any() ? 'accounts' : ($categoryFormHasError || session('categoryAdminNotice') ? 'posts' : 'dashboard') }}' }" class="flex min-h-screen bg-slate-100">

    <!-- 1. 左側：サイドバー -->
    <aside class="w-60 bg-slate-800 text-white p-6 shrink-0 hidden md:block">
        <h1 class="text-xl font-bold mb-6">MENU</h1>
        <nav class="space-y-2">
            
            <!-- ダッシュボード -->
            <button @click="currentTab = 'dashboard'" 
                    :class="currentTab === 'dashboard' ? 'bg-brand-blue text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-left">
                <span class="material-symbols-outlined">dashboard</span>
                <span>ダッシュボード</span>
            </button>

            <!-- ユーザー管理 -->
            <button @click="currentTab = 'users'" 
                    :class="currentTab === 'users' ? 'bg-brand-blue text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-left">
                <span class="material-symbols-outlined">user_attributes</span>
                <span>ユーザー管理</span>
            </button>

            <!-- 留学情報管理 -->
            <button @click="currentTab = 'posts'"
                    :class="currentTab === 'posts' ? 'bg-brand-blue text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-left">
                <span class="material-symbols-outlined">description</span>
                <span>留学情報管理</span>
            </button>

            <!-- お知らせ送信機能 -->
            <button @click="currentTab = 'notice'" 
                    :class="currentTab === 'notice' ? 'bg-brand-blue text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-left">
                <span class="material-symbols-outlined">notifications</span>
                <span>お知らせ送信</span>
            </button>

            <!-- サービス使用状況分析 -->
            <button @click="currentTab = 'analytics'" 
                    :class="currentTab === 'analytics' ? 'bg-brand-blue text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-left">
                <span class="material-symbols-outlined">analytics</span>
                <span>アクティブ分析</span>
            </button>

            <!-- 目安箱 -->
            <button @click="currentTab = 'suggestions'" 
                    :class="currentTab === 'suggestions' ? 'bg-brand-blue text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-left">
                <span class="material-symbols-outlined !text-2xl leading-none">local_post_office</span>
                <span>目安箱</span>
            </button>

        </nav>
    </aside>

    <!-- 2. 右側：メインコンテンツエリア -->
    <main class="flex-1 p-8">

        <!-- ① ダッシュボードの中身 -->
        <div x-show="currentTab === 'dashboard'" x-cloak class="space-y-8">
            
            <!-- ヘッダーエリア -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">ダッシュボード</h2>
                    <p class="text-sm text-slate-500 mt-1">本日の各セクション稼働状況とシステム概要です。</p>
                </div>
                <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full border border-emerald-200/80 shadow-sm self-start sm:self-auto">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    システム正常稼働中
                </div>
            </div>

            <!-- 1. 本日のパフォーマンス・サマリー -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-indigo-500">space_dashboard</span>
                            本日のパフォーマンス・サマリー
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">本日の主要アクティビティ指標です。</p>
                    </div>
                    
                    <button type="button" 
                            @click="currentTab = 'analytics'" 
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200/80 rounded-xl transition self-start sm:self-auto">
                        <span>アクティブ分析を見る</span>
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </button>
                </div>

                @php
                    $totalUsersCount = $totalUsersCount ?? ($users ?? collect())->count() ?: 1;
                    $dailyCards = [
                        ['title' => 'アクティブユーザー', 'val' => number_format($dailyActiveCount ?? 0), 'unit' => '名', 'border' => 'border-rose-200/60', 'dot' => 'bg-rose-500', 'sub' => 'アクティブ率: '.($dauRate ?? 0).'%'],
                        ['title' => '英語学習 利用者数', 'val' => number_format($dailyEnglishUsers ?? 0), 'unit' => '名', 'border' => 'border-amber-200/60', 'dot' => 'bg-amber-400', 'sub' => '利用率: '.($dailyEnglishRate ?? 0).'%'],
                        ['title' => '留学情報 投稿数', 'val' => number_format($dailyPostsCount ?? 0), 'unit' => '件', 'border' => 'border-lime-200/60', 'dot' => 'bg-lime-500', 'sub' => '投稿率: '.(!empty($dailyActiveCount) ? round(($dailyPostsCount / max($dailyActiveCount, 1)) * 100, 1) : 0).'%'],
                        ['title' => 'シャワーレビュー数', 'val' => number_format($dailyShowerCount ?? 0), 'unit' => '件', 'border' => 'border-sky-200/60', 'dot' => 'bg-sky-500', 'sub' => '投稿率: '.round(($dailyShowerCount ?? 0) / max($totalUsersCount, 1) * 100, 1).'%'],
                    ];
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($dailyCards as $card)
                        <div class="bg-slate-50/70 p-4 rounded-xl border {{ $card['border'] }} flex flex-col justify-between">
                            <div class="flex items-start justify-between">
                                <span class="text-xs font-bold text-slate-500 flex items-center gap-1.5">
                                    @if(!empty($card['dot']))
                                        <span class="w-2 h-2 rounded-full {{ $card['dot'] }}"></span>
                                    @endif
                                    {{ $card['title'] }}
                                </span>
                            </div>
                            <div class="my-2.5 flex items-baseline gap-1">
                                <span class="text-2xl font-black text-slate-800 tracking-tight">{{ $card['val'] }}</span>
                                <span class="text-xs font-bold text-slate-400">{{ $card['unit'] }}</span>
                            </div>
                            <div class="text-[11px] text-slate-400 border-t border-slate-200/60 pt-2">
                                {{ $card['sub'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 2. 下部グリッドエリア（2/3 : 1/3 構成） -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                <!-- 左〜中央エリア（2カラム分） -->
                <div class="lg:col-span-2 space-y-6">
                    {{-- 故障シャワーの管理 --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col justify-between h-full">
                        <div>
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-600 text-base">shower</span>
                                    故障中のシャワー
                                    <span class="text-xs font-normal text-slate-400">
                                        ({{ $brokenShowers->count() }}件)
                                    </span>
                                </h3>
                            </div>

                            <div class="space-y-2 max-h-[220px] overflow-y-auto pr-1">
                                @forelse ($brokenShowers as $report)
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-sky-50 border border-sky-200/60 text-xs">
                                        <div>
                                            <span class="font-bold text-slate-800">
                                                {{ $report->gender === 'male' ? '男子寮' : '女子寮' }}
                                                {{ $report->shower_number }}番
                                            </span>

                                            <span class="text-slate-400 ml-2">
                                                {{ $report->created_at->diffForHumans() }}
                                            </span>

                                            @if ($report->comment)
                                                <p class="text-slate-500 mt-1">
                                                    {{ $report->comment }}
                                                </p>
                                            @endif
                                        </div>

                                        <form action="{{ route('admin.shower.malfunctions.fix', [$report->gender, $report->shower_number]) }}"
                                            method="POST">
                                            @csrf

                                            <button type="submit"
                                                    class="px-3 py-1.5 bg-slate-900 text-white font-bold rounded-lg hover:bg-slate-800 transition">
                                                修理完了
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-xs text-slate-400">
                                        現在、故障中のシャワーはありません。
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100">
                            <a href="{{ route('admin.shower.malfunctions.index') }}"
                            class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200/80 rounded-xl transition">
                                <span>故障履歴を見る</span>
                                <span class="material-symbols-outlined text-base">chevron_right</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- 上段：お知らせ ＆ 最新のご意見（2並列） -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- 最新のお知らせ -->
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col justify-between h-full">
                            <div>
                                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-amber-500 text-base">campaign</span>
                                        最新のお知らせ
                                    </h3>
                                </div>

                                <div class="space-y-2">
                                    @forelse(($notices ?? ($announcements ?? collect()))->take(2) as $notice)
                                        <div class="p-2.5 rounded-xl bg-slate-50/70 border border-slate-100 hover:bg-slate-50 transition">
                                            <div class="flex items-center justify-between text-[11px] text-slate-400 mb-0.5">
                                                <span class="font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md text-[10px]">お知らせ</span>
                                                <span>
                                                    @if(!empty($notice->created_at))
                                                        {{ \Carbon\Carbon::parse($notice->created_at)->format('Y.m.d') }}
                                                    @elseif(!empty($notice['sent_at']))
                                                        {{ $notice['sent_at'] }}
                                                    @endif
                                                </span>
                                            </div>
                                            <h4 class="text-xs font-bold text-slate-800 line-clamp-1">{{ is_array($notice) ? ($notice['title'] ?? '') : ($notice->title ?? $notice->subject ?? '') }}</h4>
                                        </div>
                                    @empty
                                        <div class="text-center py-6 text-xs text-slate-400">
                                            新しいお知らせはありません。
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100">
                                <button type="button" 
                                        @click="currentTab = 'notice'" 
                                        class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200/80 rounded-xl transition">
                                    <span>お知らせを見る</span>
                                    <span class="material-symbols-outlined text-base">chevron_right</span>
                                </button>
                            </div>
                        </div>

                        <!-- 最新のご意見（目安箱） -->
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col justify-between h-full">
                            <div>
                                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-rose-500 text-base">mark_as_unread</span>
                                        最新のご意見
                                    </h3>
                                </div>

                                <div class="space-y-3">
                                    @forelse(($pendingFeedbacks ?? ($feedbacks ?? collect()))->where('status', 'pending')->take(2) as $item)
                                        @php
                                            $createdAt = is_array($item) ? ($item['created_at'] ?? null) : ($item->created_at ?? null);
                                            $category  = is_array($item) ? ($item['category'] ?? 'ご意見') : ($item->category ?? 'ご意見');
                                            $title     = is_array($item) ? ($item['title'] ?? '') : ($item->title ?? $item->subject ?? '');
                                            $body      = is_array($item) ? ($item['content'] ?? '') : ($item->content ?? $item->body ?? '');
                                        @endphp
                                        
                                        <div class="p-3 rounded-xl bg-slate-50/70 border border-slate-100 hover:bg-slate-50 transition">
                                            <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1">
                                                <span class="font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md text-[10px]">
                                                    未対応・{{ $category }}
                                                </span>
                                                <span>
                                                    @if(!empty($createdAt))
                                                        {{ \Carbon\Carbon::parse($createdAt)->format('Y.m.d') }}
                                                    @endif
                                                </span>
                                            </div>
                                            @if(!empty($title))
                                                <h4 class="text-xs font-bold text-slate-800 line-clamp-1">{{ $title }}</h4>
                                            @endif
                                            <p class="text-[11px] text-slate-500 line-clamp-2 mt-0.5">{{ $body }}</p>
                                        </div>
                                    @empty
                                        <div class="text-center py-6 text-xs text-slate-400">
                                            未対応の意見はありません。
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            
                            <div class="mt-4 pt-3 border-t border-slate-100">
                                <button type="button" 
                                @click="currentTab = 'suggestions'" 
                                class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200/80 rounded-xl transition">
                                <span>目安箱を見る</span>
                                <span class="material-symbols-outlined text-base">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>
                    

                    <!-- 下段：アクティブユーザー上位3名 -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col justify-between h-full">
                        <div>
                            <!-- ヘッダー（タイトル ＆ 右寄せボタン） -->
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-500 text-base">military_tech</span>
                                    アクティブユーザー上位3名
                                </h3>
                                <button type="button" 
                                        @click="currentTab = 'users'" 
                                        class="inline-flex items-center gap-1 text-xs font-bold text-slate-600 hover:text-slate-900 transition">
                                    <span>ユーザーを見る</span>
                                    <span class="material-symbols-outlined text-base">chevron_right</span>
                                </button>
                            </div>

                            @php
                                $topList = $topActiveUsers ?? ($users ?? collect())->sortByDesc('total_xp')->take(3);
                            @endphp

                            <!-- ユーザーリスト（縦3列） -->
                            <div class="space-y-2.5">
                                @forelse($topList as $index => $topUser)
                                    @php
                                        $userName   = is_array($topUser) ? ($topUser['name'] ?? 'ユーザー') : ($topUser->name ?? 'ユーザー');
                                        $userDorm   = is_array($topUser) ? ($topUser['dorm'] ?? '寮未設定') : ($topUser->dorm ?? '寮未設定');
                                        $lastActive = is_array($topUser) 
                                            ? ($topUser['last_active_at'] ?? $topUser['updated_at'] ?? null) 
                                            : ($topUser->last_active_at ?? $topUser->updated_at ?? null);

                                        $rankBg = match($loop->index) {
                                            0 => 'bg-amber-500', // 1位: 金
                                            1 => 'bg-slate-400', // 2位: 銀
                                            2 => 'bg-amber-700', // 3位: 銅
                                            default => 'bg-slate-300',
                                        };
                                    @endphp
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50/70 border border-slate-100">
                                        <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full font-bold text-xs text-white shadow-sm {{ $rankBg }}">
                                                {{ $loop->iteration }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-800 truncate">{{ $userName }}</p>
                                                <p class="text-[10px] text-slate-400 truncate">{{ $userDorm }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="block text-[9px] text-slate-400">最終アクティブ</span>
                                            <span class="text-[10px] font-semibold text-slate-600">
                                                @if(!empty($lastActive))
                                                    {{ \Carbon\Carbon::parse($lastActive)->diffForHumans() }}
                                                @else
                                                    --
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-xs text-slate-400">
                                        データがありません。
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>

                <!-- 右カラム：管理者伝言板（縦に独立） -->
                <div class="lg:col-span-1 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col justify-between h-full min-h-[360px]">
                    <div>
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-indigo-500 text-base">forum</span>
                                    管理者伝言板
                                </h3>
                                <p class="text-[11px] text-slate-400 mt-0.5">チーム内での共有・申し送り事項です。</p>
                            </div>
                        </div>

                        <div class="space-y-2 max-h-[260px] overflow-y-auto pr-1">
                            @forelse($adminMessages ?? [] as $msg)
                                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100/80 space-y-1">
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="font-bold text-slate-700 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            {{ $msg->user->name ?? $msg->author_name ?? '管理者' }}
                                        </span>
                                        <span class="text-slate-400 text-[10px]">
                                            {{ $msg->created_at instanceof \Carbon\Carbon ? $msg->created_at->diffForHumans() : '' }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-600 leading-relaxed whitespace-pre-wrap break-words">{{ $msg->message ?? $msg->content ?? '' }}</p>
                                </div>
                            @empty
                                <div class="text-center py-6 text-[11px] text-slate-400">
                                    現在、伝言はありません。
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <form action="{{ route('admin.messages.store') }}" method="POST" class="mt-3 pt-3 border-t border-slate-100 flex gap-1.5">
                        @csrf
                        <input type="text" name="message" placeholder="伝言を入力..." required
                            class="flex-1 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-[11px] font-medium text-slate-700 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <button type="submit" class="shrink-0 rounded-lg bg-slate-900 px-3 py-1.5 text-[11px] font-bold text-white transition hover:bg-slate-800">
                            投稿
                        </button>
                    </form>
                </div>

            </div>

        </div>

        <!-- ② ユーザー管理メインセクション -->
        <div x-show="currentTab === 'users'" x-cloak x-data="{ 
            searchQuery: '',
            selectedRole: 'all',
            sortBy: 'created_desc',
            currentPage: 1,
            perPage: 10,
            isCreateModalOpen: false,
            detailModalOpen: false,
            selectedUser: null,
            users: {{ \Illuminate\Support\Js::from($users ?? []) }},

            openDetail(user) {
                this.selectedUser = user;
                this.detailModalOpen = true;
            },

            get filteredUsers() {
                let list = this.users.filter(user => {
                    const query = this.searchQuery.toLowerCase().trim();
                    const matchQuery = !query || 
                        (user.name && user.name.toLowerCase().includes(query)) || 
                        (user.email && user.email.toLowerCase().includes(query)) ||
                        (user.dorm && user.dorm.toLowerCase().includes(query)) ||
                        (user.course && user.course.toLowerCase().includes(query));

                    const userRole = user.role || (user.role_id === 1 ? 'admin' : 'student');
                    const matchRole = this.selectedRole === 'all' || userRole === this.selectedRole;

                    return matchQuery && matchRole;
                });

                return list.sort((a, b) => {
                    if (this.sortBy === 'created_desc') return new Date(b.registered_at || b.created_at || 0) - new Date(a.registered_at || a.created_at || 0);
                    if (this.sortBy === 'created_asc') return new Date(a.registered_at || a.created_at || 0) - new Date(b.registered_at || b.created_at || 0);
                    if (this.sortBy === 'active_desc') return new Date(b.last_active || b.last_login_at || 0) - new Date(a.last_active || a.last_login_at || 0);
                    if (this.sortBy === 'active_asc') return new Date(a.last_active || a.last_login_at || 0) - new Date(b.last_active || b.last_login_at || 0);
                    return 0;
                });
            },

            get totalPages() {
                return Math.ceil(this.filteredUsers.length / this.perPage) || 1;
            },
            get paginatedUsers() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.filteredUsers.slice(start, start + this.perPage);
            },

            nextPage() { if (this.currentPage < this.totalPages) this.currentPage++; },
            prevPage() { if (this.currentPage > 1) this.currentPage--; }
        }"
        x-init="if ({{ session('accountCreated') || $errors->any() ? 'true' : 'false' }}) { isCreateModalOpen = true; }"
        @open-user-modal.window="openDetail($event.detail)">

            <!-- ヘッダーエリア -->
            <div class="flex flex-col gap-4 mb-6 xl:flex-row xl:items-center xl:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">ユーザー管理</h2>
                    <p class="mt-1 text-sm text-slate-500">登録留学生・管理者の利用状況やアクティビティを一元管理します。</p>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <!-- コントロール群（ロール切り替え・検索・ソート） -->
                    <div class="flex flex-wrap items-center gap-2 bg-white p-2 sm:p-2.5 rounded-2xl border border-slate-200/80 shadow-sm">
                        <!-- ロール絞り込みタブ -->
                        <div class="inline-flex rounded-lg bg-slate-100/80 p-1 text-xs font-semibold text-slate-600">
                            <button type="button" 
                                    @click="selectedRole = 'all'; currentPage = 1"
                                    :class="selectedRole === 'all' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-700'"
                                    class="rounded-md px-3 py-1.5 transition">
                                すべて
                            </button>
                            <button type="button" 
                                    @click="selectedRole = 'student'; currentPage = 1"
                                    :class="selectedRole === 'student' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-700'"
                                    class="rounded-md px-3 py-1.5 transition">
                                学生
                            </button>
                            <button type="button" 
                                    @click="selectedRole = 'admin'; currentPage = 1"
                                    :class="selectedRole === 'admin' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-700'"
                                    class="rounded-md px-3 py-1.5 transition">
                                管理者
                            </button>
                        </div>

                        <!-- 検索窓 -->
                        <div class="relative min-w-[160px] flex-1 sm:flex-initial">
                            <input type="text" 
                                x-model="searchQuery" 
                                @input="currentPage = 1" 
                                placeholder="名前・メールで検索..." 
                                class="w-full rounded-lg border border-slate-200 bg-slate-50/50 py-1.5 pl-8 pr-3 text-xs text-slate-700 placeholder:text-slate-400 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-slate-400">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- ソート -->
                        <div class="relative shrink-0">
                            <select x-model="sortBy" 
                                    @change="currentPage = 1" 
                                    class="appearance-none rounded-lg border border-slate-200 bg-white py-1.5 pl-3 pr-7 text-xs font-medium text-slate-700 hover:border-slate-300 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 cursor-pointer transition">
                                <option value="created_desc">作成日：新しい順</option>
                                <option value="created_asc">作成日：古い順</option>
                                <option value="active_desc">最終アクセス：最近順</option>
                                <option value="active_asc">最終アクセス：過去順</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-slate-400">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- 新規アカウント発行ボタン -->
                    <button type="button" 
                            @click="isCreateModalOpen = true" 
                            class="shrink-0 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition shadow-sm flex items-center justify-center gap-2 h-full">
                        <span>＋</span> 新規アカウント発行
                    </button>
                </div>
            </div>

            <!-- ユーザー一覧テーブル -->
            <div class="mb-10 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-xs font-bold uppercase text-slate-500">
                                <th class="py-3.5 px-4">ユーザー</th>
                                <th class="py-3.5 px-4">最終アクセス</th>
                                <th class="py-3.5 px-4">権限</th>
                                <th class="py-3.5 px-4">状態</th>
                                <th class="py-3.5 px-4">所属寮</th>
                                <th class="py-3.5 px-4">作成日</th>
                                <th class="py-3.5 px-4 text-right">操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            <template x-for="(user, index) in paginatedUsers" :key="user.id || index">
                                <tr class="transition hover:bg-slate-50/80">
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-full bg-slate-800 text-xs font-bold text-white">
                                                <template x-if="user.profile_photo_url || user.avatar_url">
                                                    <img :src="user.profile_photo_url || user.avatar_url" :alt="user.name" class="h-full w-full object-cover">
                                                </template>
                                                <template x-if="!user.profile_photo_url && !user.avatar_url">
                                                    <span x-text="user.name ? user.name.charAt(0) : '?'"></span>
                                                </template>
                                            </div>
                                            <div class="truncate">
                                                <p class="font-bold leading-tight text-slate-800" x-text="user.name || '名前未設定'"></p>
                                                <p class="mt-0.5 text-xs text-slate-400" x-text="user.email || ''"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-xs text-slate-500 whitespace-nowrap" x-text="user.last_active || user.last_login_at || '-'"></td>
                                    <td class="py-3.5 px-4 text-xs font-semibold text-slate-700 whitespace-nowrap" x-text="user.role === 'admin' || user.role_id === 1 ? '管理者' : '学生'"></td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold"
                                            :class="user.status === 'inactive' || user.is_active === false ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600'">
                                            <span class="h-1.5 w-1.5 rounded-full"
                                                :class="user.status === 'inactive' || user.is_active === false ? 'bg-rose-500' : 'bg-emerald-500'"></span>
                                            <span x-text="user.status === 'inactive' || user.is_active === false ? '停止中' : '正常'"></span>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <p class="text-xs font-semibold text-slate-700" x-text="user.dorm || '未設定'"></p>
                                        <p class="text-[11px] text-slate-400" x-text="user.course || ''"></p>
                                    </td>
                                    <td class="py-3.5 px-4 text-xs font-semibold text-slate-600 whitespace-nowrap" x-text="user.registered_at || user.created_at || '-'"></td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <button type="button"
                                                @click="openDetail(user)"
                                                class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 transition hover:bg-slate-200">
                                            詳細
                                        </button>
                                    </td>
                                </tr>
                            </template>

                            <template x-if="paginatedUsers.length === 0">
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-xs text-slate-400">
                                        条件に一致するユーザーが見つかりませんでした。
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- ページネーションフッター -->
                <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/50 px-4 py-3 text-xs font-medium text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        全 <span class="font-bold text-slate-800" x-text="filteredUsers.length"></span> 件中 
                        <span class="font-bold text-slate-800" x-text="filteredUsers.length > 0 ? (currentPage - 1) * perPage + 1 : 0"></span> - 
                        <span class="font-bold text-slate-800" x-text="Math.min(currentPage * perPage, filteredUsers.length)"></span> 件を表示
                    </div>

                    <div class="flex items-center gap-1.5 self-end sm:self-auto">
                        <button type="button" 
                                @click="prevPage()" 
                                :disabled="currentPage === 1"
                                :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-200 text-slate-700'"
                                class="rounded-lg bg-slate-100 px-3 py-1.5 font-bold transition">
                            前へ
                        </button>
                        <span class="px-2 font-bold text-slate-700">
                            <span x-text="currentPage"></span> / <span x-text="totalPages"></span>
                        </span>
                        <button type="button" 
                                @click="nextPage()" 
                                :disabled="currentPage >= totalPages"
                                :class="currentPage >= totalPages ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-200 text-slate-700'"
                                class="rounded-lg bg-slate-100 px-3 py-1.5 font-bold transition">
                            次へ
                        </button>
                    </div>
                </div>
            </div>

            <!-- ② 新規アカウント作成モーダル（最前面 z-[9999]） -->
            <div x-show="isCreateModalOpen" 
                 x-cloak 
                 class="fixed inset-0 z-[9999] overflow-y-auto"
                 aria-labelledby="modal-title" role="dialog" aria-modal="true">
                
                <div x-show="isCreateModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="isCreateModalOpen = false" 
                     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
                    <div x-show="isCreateModalOpen"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white p-6 text-left shadow-xl transition-all sm:p-8">

                        <button type="button" @click="isCreateModalOpen = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 transition">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-slate-800" id="modal-title">新規アカウント作成</h3>
                            <p class="mt-1 text-sm text-slate-500">管理者が新規アカウントを発行します。作成後、ログイン情報をユーザーへお伝えください。</p>
                        </div>

                        <div class="space-y-4">
                            @if (session('accountCreated'))
                                <div class="space-y-1 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-bold text-emerald-800">
                                    <p>✅ アカウントを作成しました。以下の情報を学生にお伝えください。</p>
                                    <p>ID（メールアドレス）: {{ session('accountCreated')['email'] }}</p>
                                    <p>初期パスワード: {{ session('accountCreated')['password'] }}</p>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="space-y-1 rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs font-bold text-rose-700">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">氏名 <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-medium transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">メールアドレス（ログインID） <span class="text-rose-500">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-medium transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">初期パスワード（8文字以上） <span class="text-rose-500">*</span></label>
                                    <input type="text" name="password" required minlength="8"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-medium transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">権限（ロール） <span class="text-rose-500">*</span></label>
                                    <select name="role" required
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-medium transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                                        <option value="student" {{ old('role') == 'student' || old('role') == 'user' ? 'selected' : '' }}>一般ユーザー（学生）</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>管理者</option>
                                    </select>
                                </div>

                                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                                    <button type="button" @click="isCreateModalOpen = false" class="rounded-xl border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-50">
                                        キャンセル
                                    </button>
                                    <button type="submit" class="rounded-xl bg-slate-900 py-2.5 px-6 text-xs font-bold text-white shadow-md transition hover:bg-slate-800">
                                        アカウントを作成する
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ユーザー詳細表示モーダル（最前面 z-[9999]） -->
            <div x-show="detailModalOpen" 
                 x-cloak 
                 class="fixed inset-0 z-[9999] overflow-y-auto"
                 aria-labelledby="modal-title" role="dialog" aria-modal="true">
                
                <!-- 背景オーバーレイ -->
                <div x-show="detailModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="detailModalOpen = false" 
                     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

                <!-- モーダル本体 -->
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
                    <div x-show="detailModalOpen"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white p-6 text-left shadow-xl transition-all sm:p-8">

                        <!-- 閉じるボタン -->
                        <button type="button" @click="detailModalOpen = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 transition">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <template x-if="selectedUser">
                            <div class="space-y-6">
                                <!-- 1. ヘッダー（アイコン・名前・権限・メール・ID） -->
                                <div class="flex items-center gap-4 border-b border-slate-100 pb-5">
                                    <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-full bg-slate-800 text-xl font-bold text-white shadow-md">
                                        <template x-if="selectedUser.avatar_url || selectedUser.avatar">
                                            <img :src="selectedUser.avatar_url || selectedUser.avatar" :alt="selectedUser.name" class="h-full w-full object-cover">
                                        </template>
                                        <template x-if="!selectedUser.avatar_url && !selectedUser.avatar">
                                            <span x-text="selectedUser.name ? selectedUser.name.charAt(0) : '?'"></span>
                                        </template>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-xl font-bold text-slate-800 truncate" x-text="selectedUser.name || '名前未設定'"></h3>
                                            <span class="rounded-md px-2 py-0.5 text-[10px] font-bold"
                                                  :class="selectedUser.role === 'admin' || selectedUser.role_id === 1 ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600'"
                                                  x-text="selectedUser.role === 'admin' || selectedUser.role_id === 1 ? '管理者' : '一般'"></span>
                                        </div>
                                        <p class="text-xs text-slate-500 truncate mt-0.5" x-text="selectedUser.email || '-'"></p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">ユーザーID: <span x-text="selectedUser.id"></span></p>
                                    </div>
                                </div>

                                <!-- 2. 基本情報 & 試験・卒業予定 -->
                                <div>
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">基本プロパティ</h4>
                                    <div class="grid grid-cols-2 gap-3 text-xs">
                                        <div class="rounded-xl bg-slate-50 p-3">
                                            <span class="block text-slate-400 mb-0.5">性別</span>
                                            <span class="font-bold text-slate-700" x-text="selectedUser.gender || '未設定'"></span>
                                        </div>
                                        <div class="rounded-xl bg-slate-50 p-3">
                                            <span class="block text-slate-400 mb-0.5">卒業予定日</span>
                                            <span class="font-bold text-slate-700" x-text="selectedUser.graduation_date || '未設定'"></span>
                                        </div>
                                        <div class="rounded-xl bg-slate-50 p-3">
                                            <span class="block text-slate-400 mb-0.5">TOEIC 試験予定日</span>
                                            <span class="font-bold text-slate-700" x-text="selectedUser.toeic_exam_date || '未登録'"></span>
                                        </div>
                                        <div class="rounded-xl bg-slate-50 p-3">
                                            <span class="block text-slate-400 mb-0.5">IELTS 試験予定日</span>
                                            <span class="font-bold text-slate-700" x-text="selectedUser.ielts_exam_date || '未登録'"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3. 英語学習の進捗・アクティビティ -->
                                <div>
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">英語学習アクティビティ</h4>
                                    <div class="grid grid-cols-3 gap-3 mb-3">
                                        <div class="rounded-xl bg-indigo-50 p-3 text-indigo-900">
                                            <span class="block text-[10px] text-indigo-500 font-bold">獲得 Total XP</span>
                                            <span class="text-base font-extrabold" x-text="(selectedUser.total_xp || 0).toLocaleString() + ' XP'"></span>
                                        </div>
                                        <div class="rounded-xl bg-amber-50 p-3 text-amber-900">
                                            <span class="block text-[10px] text-amber-500 font-bold">連続学習 Streak</span>
                                            <span class="text-base font-extrabold" x-text="(selectedUser.study_streak || 0) + ' 日'"></span>
                                        </div>
                                        <div class="rounded-xl bg-emerald-50 p-3 text-emerald-900">
                                            <span class="block text-[10px] text-emerald-500 font-bold">総学習時間</span>
                                            <span class="text-base font-extrabold" x-text="Math.floor((selectedUser.total_study_time || 0) / 60) + ' 時間'"></span>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center rounded-xl bg-slate-50 px-3 py-2.5 text-xs">
                                        <span class="text-slate-500 font-medium">最終学習日</span>
                                        <span class="font-bold text-slate-800" x-text="selectedUser.last_study_date || '未学習'"></span>
                                    </div>
                                </div>

                                <!-- 4. その他活動情報（目安箱・シャワー） -->
                                <div>
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">その他実績</h4>
                                    <div class="divide-y divide-slate-100 text-xs">
                                        <div class="flex justify-between py-2">
                                            <span class="text-slate-500">シャワー利用レポート件数</span>
                                            <span class="font-bold text-slate-800" x-text="selectedUser.shower_reports ? selectedUser.shower_reports.length : 0"></span>
                                        </div>
                                        <div class="flex justify-between py-2">
                                            <span class="text-slate-500">留学情報投稿数</span>
                                            <span class="font-bold text-slate-800" x-text="selectedUser.posts ? selectedUser.posts.length : 0"></span>
                                        </div>
                                        <div class="flex justify-between py-2">
                                            <span class="text-slate-500">目安箱への投函数</span>
                                            <span class="font-bold text-slate-800" x-text="selectedUser.suggestions ? selectedUser.suggestions.length : 0"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- フッター -->
                                <div class="pt-2 flex justify-end">
                                    <button type="button" @click="detailModalOpen = false" class="rounded-xl bg-slate-100 px-5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200 transition">
                                        閉じる
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- ④ 留学情報管理の中身 -->
        <script>
            // 背景色⇄文字色の相互サジェスト用(HSLで変換するのでどんな色を入れても破綻しない)
            function hexToRgb(hex) {
                hex = hex.replace('#', '');
                return { r: parseInt(hex.substr(0, 2), 16), g: parseInt(hex.substr(2, 2), 16), b: parseInt(hex.substr(4, 2), 16) };
            }
            function rgbToHex(r, g, b) {
                const c = (n) => Math.max(0, Math.min(255, Math.round(n))).toString(16).padStart(2, '0');
                return '#' + c(r) + c(g) + c(b);
            }
            function rgbToHsl(r, g, b) {
                r /= 255; g /= 255; b /= 255;
                const max = Math.max(r, g, b), min = Math.min(r, g, b);
                let h, s, l = (max + min) / 2;
                if (max === min) { h = s = 0; }
                else {
                    const d = max - min;
                    s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                    switch (max) {
                        case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                        case g: h = (b - r) / d + 2; break;
                        default: h = (r - g) / d + 4; break;
                    }
                    h /= 6;
                }
                return { h, s, l };
            }
            function hslToRgb(h, s, l) {
                let r, g, b;
                if (s === 0) { r = g = b = l; }
                else {
                    const hue2rgb = (p, q, t) => {
                        if (t < 0) t += 1;
                        if (t > 1) t -= 1;
                        if (t < 1 / 6) return p + (q - p) * 6 * t;
                        if (t < 1 / 2) return q;
                        if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6;
                        return p;
                    };
                    const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
                    const p = 2 * l - q;
                    r = hue2rgb(p, q, h + 1 / 3);
                    g = hue2rgb(p, q, h);
                    b = hue2rgb(p, q, h - 1 / 3);
                }
                return { r: r * 255, g: g * 255, b: b * 255 };
            }
            // 文字色 → それに合う薄い背景色を提案
            function suggestBgFromText(hex) {
                const { r, g, b } = hexToRgb(hex);
                const { h, s } = rgbToHsl(r, g, b);
                const rgb = hslToRgb(h, Math.min(s, 0.65), 0.86);
                return rgbToHex(rgb.r, rgb.g, rgb.b);
            }
            // 背景色 → それに合う濃い文字色を提案
            function suggestTextFromBg(hex) {
                const { r, g, b } = hexToRgb(hex);
                const { h, s } = rgbToHsl(r, g, b);
                const rgb = hslToRgb(h, Math.max(s, 0.55), 0.42);
                return rgbToHex(rgb.r, rgb.g, rgb.b);
            }
        </script>

        <div x-show="currentTab === 'posts'" x-cloak x-data="{
            mode: '{{ $categoryFormMode }}',
            mainCategories: {{ \Illuminate\Support\Js::from($adminMainCategories ?? []) }},
            categories: {{ \Illuminate\Support\Js::from($adminCategories ?? []) }},
            editMain: { id: '', key: '', name: '', description: '', hero_image: '', sub_count: 0 },
            editMainColor: '',
            editMainTextColor: '',
            editMainUseColor: false,
            addMainColor: '#2f5bfd',
            addMainTextColor: '#2f5bfd',
            addMainUseColor: false,
            forceDeleteMain: false,
            editCategory: { id: '', section: '', name: '', description: '', hero_image: '', post_count: 0 },
            editSubSection: '',
            forceDeleteSub: false,
            loadMain(id) {
                const m = this.mainCategories.find(x => x.id == id);
                this.forceDeleteMain = false;
                if (!m) { this.editMain = { id: '', key: '', name: '', description: '', hero_image: '', sub_count: 0 }; this.editMainColor = ''; this.editMainTextColor = ''; this.editMainUseColor = false; return; }
                this.editMain = { id: m.id, key: m.key, name: m.name, description: m.description || '', hero_image: m.hero_image || '', sub_count: m.sub_count || 0 };
                this.editMainColor = m.color || '';
                this.editMainTextColor = m.text_color || '';
                this.editMainUseColor = !!m.color || !!m.text_color;
            },
            loadCategory(id) {
                const c = this.categories.find(x => x.id == id);
                this.forceDeleteSub = false;
                this.editCategory = c ? { id: c.id, section: c.section, name: c.name, description: c.description || '', hero_image: c.hero_image || '', post_count: c.post_count || 0 } : { id: '', section: '', name: '', description: '', hero_image: '', post_count: 0 };
            },
        }">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-800">留学情報管理</h2>
                <p class="text-sm text-slate-500 mt-1">メインカテゴリー・サブカテゴリーの追加や編集を行います。</p>
            </div>

            @if (session('categoryAdminNotice'))
                @php $noticeIsError = session('categoryAdminNotice')['type'] === 'error'; @endphp
                <div class="max-w-2xl mb-6 p-4 rounded-xl text-xs font-bold {{ $noticeIsError ? 'bg-rose-50 border border-rose-200 text-rose-700' : 'bg-emerald-50 border border-emerald-200 text-emerald-800' }}">
                    {{ $noticeIsError ? '⚠️' : '✅' }} {{ session('categoryAdminNotice')['message'] }}
                </div>
            @endif

            <div class="flex flex-wrap gap-2 mb-6">
                <button type="button" @click="mode = 'addMain'" :class="mode === 'addMain' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'" class="px-4 py-2.5 rounded-xl text-xs font-bold transition">➕ 新規メインカテゴリー</button>
                <button type="button" @click="mode = 'addSub'" :class="mode === 'addSub' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'" class="px-4 py-2.5 rounded-xl text-xs font-bold transition">➕ 新規サブカテゴリー</button>
                <button type="button" @click="mode = 'editMain'" :class="mode === 'editMain' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'" class="px-4 py-2.5 rounded-xl text-xs font-bold transition">✏️ メインカテゴリー編集</button>
                <button type="button" @click="mode = 'editSub'" :class="mode === 'editSub' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'" class="px-4 py-2.5 rounded-xl text-xs font-bold transition">✏️ サブカテゴリー編集</button>
            </div>

            <!-- ① 新規メインカテゴリー -->
            <div x-show="mode === 'addMain'" x-cloak class="max-w-xl bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                @if ($errors->addMain->any())
                    <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs font-bold space-y-1">
                        @foreach ($errors->addMain->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.main-categories.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">識別キー(key) <span class="text-rose-500">*</span></label>
                        <input type="text" name="key" value="{{ old('key') }}" required placeholder="例: souvenir-shop"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">名前 <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ヒーロー画像</label>
                        <input type="file" name="hero_image" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">説明文</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label class="flex items-center gap-2 text-xs font-bold text-slate-700 mb-2">
                            <input type="checkbox" x-model="addMainUseColor" class="rounded border-slate-300">
                            カラーを手動で指定する
                        </label>
                        <p x-show="!addMainUseColor" class="text-[11px] text-slate-400">指定しない場合は自動で色が割り当てられます。</p>
                        <div x-show="addMainUseColor" x-cloak class="space-y-3">
                            <div class="flex items-center gap-3">
                                <input type="color" name="text_color" x-model="addMainTextColor" x-bind:disabled="!addMainUseColor"
                                       @input="addMainColor = suggestBgFromText(addMainTextColor)"
                                       class="w-12 h-10 rounded-lg border border-slate-200 cursor-pointer">
                                <div>
                                    <span class="block text-[11px] font-bold text-slate-500">文字色</span>
                                    <span class="text-xs font-mono text-slate-500" x-text="addMainTextColor"></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <input type="color" name="color" x-model="addMainColor" x-bind:disabled="!addMainUseColor"
                                       @input="addMainTextColor = suggestTextFromBg(addMainColor)"
                                       class="w-12 h-10 rounded-lg border border-slate-200 cursor-pointer">
                                <div>
                                    <span class="block text-[11px] font-bold text-slate-500">背景色</span>
                                    <span class="text-xs font-mono text-slate-500" x-text="addMainColor"></span>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400">どちらか一方を選ぶと、もう一方に合う色を自動で提案します(そのあと自由に上書きできます)。</p>
                        </div>
                    </div>
                    <button type="submit" class="w-full py-3 px-6 rounded-xl text-xs font-bold transition bg-slate-900 hover:bg-slate-800 text-white shadow-md">
                        メインカテゴリーを追加する
                    </button>
                </form>
            </div>

            <!-- ② 新規サブカテゴリー -->
            <div x-show="mode === 'addSub'" x-cloak class="max-w-xl bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                @if ($errors->addSub->any())
                    <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs font-bold space-y-1">
                        @foreach ($errors->addSub->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">所属メインカテゴリー <span class="text-rose-500">*</span></label>
                        <select name="section" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium">
                            <option value="">選択してください</option>
                            @foreach ($adminMainCategories ?? [] as $mc)
                                <option value="{{ $mc->key }}" {{ old('section') === $mc->key ? 'selected' : '' }}>{{ $mc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">名前 <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ヒーロー画像</label>
                        <input type="file" name="hero_image" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">説明文</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="w-full py-3 px-6 rounded-xl text-xs font-bold transition bg-slate-900 hover:bg-slate-800 text-white shadow-md">
                        サブカテゴリーを追加する
                    </button>
                </form>
            </div>

            <!-- ③ メインカテゴリー編集 -->
            <div x-show="mode === 'editMain'" x-cloak class="max-w-xl bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                @if ($errors->editMain->any())
                    <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs font-bold space-y-1">
                        @foreach ($errors->editMain->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">編集するメインカテゴリーを選択</label>
                    <select @change="loadMain($event.target.value)" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium">
                        <option value="">選択してください</option>
                        <template x-for="mc in mainCategories" :key="mc.id">
                            <option :value="mc.id" x-text="mc.name"></option>
                        </template>
                    </select>
                </div>

                <form method="POST" x-show="editMain.id" x-cloak :action="editMain.id ? '{{ url('admin/main-categories') }}/' + editMain.id : ''" enctype="multipart/form-data" class="space-y-4 pt-2 border-t border-slate-100">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">識別キー(key)</label>
                        <input type="text" :value="editMain.key" disabled class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-medium text-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">名前 <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="editMain.name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">現在のヒーロー画像</label>
                        <img :src="editMain.hero_image" x-show="editMain.hero_image" class="w-full h-32 object-cover rounded-xl border border-slate-200 mb-2">
                        <input type="file" name="hero_image" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">説明文</label>
                        <textarea name="description" x-model="editMain.description" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"></textarea>
                    </div>
                    <div>
                        <label class="flex items-center gap-2 text-xs font-bold text-slate-700 mb-2">
                            <input type="checkbox" x-model="editMainUseColor" class="rounded border-slate-300">
                            カラーを手動で指定する
                        </label>
                        <p x-show="!editMainUseColor" class="text-[11px] text-slate-400">指定しない場合は自動で色が割り当てられます。</p>
                        <div x-show="editMainUseColor" x-cloak class="space-y-3">
                            <div class="flex items-center gap-3">
                                <input type="color" name="text_color" x-model="editMainTextColor"
                                       @input="editMainColor = suggestBgFromText(editMainTextColor)"
                                       class="w-12 h-10 rounded-lg border border-slate-200 cursor-pointer">
                                <div>
                                    <span class="block text-[11px] font-bold text-slate-500">文字色</span>
                                    <span class="text-xs font-mono text-slate-500" x-text="editMainTextColor"></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <input type="color" name="color" x-model="editMainColor"
                                       @input="editMainTextColor = suggestTextFromBg(editMainColor)"
                                       class="w-12 h-10 rounded-lg border border-slate-200 cursor-pointer">
                                <div>
                                    <span class="block text-[11px] font-bold text-slate-500">背景色</span>
                                    <span class="text-xs font-mono text-slate-500" x-text="editMainColor"></span>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400">どちらか一方を選ぶと、もう一方に合う色を自動で提案します(そのあと自由に上書きできます)。</p>
                        </div>
                    </div>
                    <button type="submit" class="w-full py-3 px-6 rounded-xl text-xs font-bold transition bg-slate-900 hover:bg-slate-800 text-white shadow-md">
                        メインカテゴリーを更新する
                    </button>
                </form>

                <form method="POST" x-show="editMain.id" x-cloak :action="editMain.id ? '{{ url('admin/main-categories') }}/' + editMain.id : ''" class="space-y-2 pt-1" onsubmit="return confirm('削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="force" :value="forceDeleteMain ? '1' : '0'">
                    <p x-show="editMain.sub_count > 0" x-cloak class="text-[11px] text-rose-500 font-bold">
                        ⚠️ サブカテゴリーが <span x-text="editMain.sub_count"></span> 件あります。
                    </p>
                    <label x-show="editMain.sub_count > 0" x-cloak class="flex items-center gap-2 text-[11px] font-bold text-rose-600">
                        <input type="checkbox" x-model="forceDeleteMain" class="rounded border-rose-300">
                        中身ごと完全に削除する
                    </label>
                    <button type="submit" :disabled="editMain.sub_count > 0 && !forceDeleteMain" :class="(editMain.sub_count > 0 && !forceDeleteMain) ? 'opacity-40 cursor-not-allowed' : 'hover:bg-rose-50'" class="w-full py-2.5 px-6 rounded-xl text-xs font-bold transition bg-white border border-rose-200 text-rose-600">
                        🗑️ このメインカテゴリーを削除する
                    </button>
                </form>
            </div>

            <!-- ④ サブカテゴリー編集 -->
            <div x-show="mode === 'editSub'" x-cloak class="max-w-xl bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                @if ($errors->editSub->any())
                    <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs font-bold space-y-1">
                        @foreach ($errors->editSub->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">① 対象のメインカテゴリーを選択</label>
                    <select x-model="editSubSection" @change="editCategory = { id: '', section: '', name: '', description: '', hero_image: '', post_count: 0 }; forceDeleteSub = false;" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium">
                        <option value="">選択してください</option>
                        <template x-for="mc in mainCategories" :key="mc.id">
                            <option :value="mc.key" x-text="mc.name"></option>
                        </template>
                    </select>
                </div>
                <div x-show="editSubSection" x-cloak>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">② 編集するサブカテゴリーを選択</label>
                    <select @change="loadCategory($event.target.value)" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium">
                        <option value="">選択してください</option>
                        <template x-for="c in categories.filter(c => c.section === editSubSection)" :key="c.id">
                            <option :value="c.id" x-text="c.name"></option>
                        </template>
                    </select>
                </div>

                <form method="POST" x-show="editCategory.id" x-cloak :action="editCategory.id ? '{{ url('admin/categories') }}/' + editCategory.id : ''" enctype="multipart/form-data" class="space-y-4 pt-2 border-t border-slate-100">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="section" x-model="editCategory.section">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">名前 <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="editCategory.name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">現在のヒーロー画像</label>
                        <img :src="editCategory.hero_image" x-show="editCategory.hero_image" class="w-full h-32 object-cover rounded-xl border border-slate-200 mb-2">
                        <input type="file" name="hero_image" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">説明文</label>
                        <textarea name="description" x-model="editCategory.description" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3 px-6 rounded-xl text-xs font-bold transition bg-slate-900 hover:bg-slate-800 text-white shadow-md">
                        サブカテゴリーを更新する
                    </button>
                </form>

                <form method="POST" x-show="editCategory.id" x-cloak :action="editCategory.id ? '{{ url('admin/categories') }}/' + editCategory.id : ''" class="space-y-2 pt-1" onsubmit="return confirm('削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="force" :value="forceDeleteSub ? '1' : '0'">
                    <p x-show="editCategory.post_count > 0" x-cloak class="text-[11px] text-rose-500 font-bold">
                        ⚠️ 投稿が <span x-text="editCategory.post_count"></span> 件あります。
                    </p>
                    <label x-show="editCategory.post_count > 0" x-cloak class="flex items-center gap-2 text-[11px] font-bold text-rose-600">
                        <input type="checkbox" x-model="forceDeleteSub" class="rounded border-rose-300">
                        投稿ごと削除する
                    </label>
                    <button type="submit" :disabled="editCategory.post_count > 0 && !forceDeleteSub" :class="(editCategory.post_count > 0 && !forceDeleteSub) ? 'opacity-40 cursor-not-allowed' : 'hover:bg-rose-50'" class="w-full py-2.5 px-6 rounded-xl text-xs font-bold transition bg-white border border-rose-200 text-rose-600">
                        🗑️ このサブカテゴリーを削除する
                    </button>
                </form>
            </div>
        </div>

        <!-- ⑤ お知らせ送信の中身 -->
        <div x-show="currentTab === 'notice'" x-cloak x-data="noticeAdmin()">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">お知らせ管理</h2>
                    <p class="text-sm text-slate-500 mt-1">留学生のアプリ内通知へ一斉配信した履歴の確認や新規送信を行います。</p>
                </div>
                <button @click="isModalOpen = true; sentSuccess = false; errorMessage = '';" 
                        class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                    <span>＋</span> お知らせを新規作成
                </button>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                <h3 class="font-bold text-slate-800 text-base flex items-center gap-2 border-b border-slate-100 pb-4">
                    <span class="material-symbols-outlined">data_table</span> 最近の配信履歴
                </h3>

                <div class="space-y-3">
                    <template x-if="sentHistory.length === 0">
                        <p class="text-xs text-slate-400 text-center py-6">配信履歴がまだありません。</p>
                    </template>
                    <template x-for="item in sentHistory" :key="item.id">
                        <div class="p-4 bg-slate-50 hover:bg-slate-100/80 rounded-xl border border-slate-200/80 transition space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 bg-slate-200 text-slate-700 text-xs font-bold rounded-lg" x-text="item.category"></span>
                                <span class="text-xs text-slate-400" x-text="item.sent_at"></span>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm" x-text="item.title"></h4>
                            <p class="text-xs text-slate-600 whitespace-pre-line leading-relaxed" x-text="item.content"></p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- 新規作成モーダル -->
            <div x-show="isModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div @click.outside="if(!isSending) isModalOpen = false" class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-xl max-h-[90vh] overflow-y-auto p-6 space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                            <span>✏️</span> お知らせの新規作成
                        </h3>
                        <button @click="isModalOpen = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold flex items-center justify-center transition">✕</button>
                    </div>

                    <template x-if="sentSuccess">
                        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-800 text-xs font-bold animate-pulse">
                            <span class="text-lg">🎉</span>
                            <span>お知らせの配信が完了しました！</span>
                        </div>
                    </template>

                    <template x-if="errorMessage">
                        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-3 text-rose-700 text-xs font-bold">
                            <span class="text-lg">⚠️</span>
                            <div class="space-y-1">
                                <p class="font-bold">お知らせの送信に失敗しました。</p>
                                <p class="font-normal font-mono text-[11px] opacity-90" x-text="errorMessage"></p>
                            </div>
                        </div>
                    </template>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">お知らせタイトル <span class="text-rose-500">*</span></label>
                            <input type="text" x-model="title" placeholder="例: 重要なお知らせ" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">配信カテゴリ</label>
                            <select x-model="category" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700">
                                <option value="重要">🚨 重要なお知らせ</option>
                                <option value="イベント">🎉 イベント・交流会</option>
                                <option value="生活">🏠 寮・生活情報</option>
                                <option value="学習">📖 英語学習</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">お知らせ本文 <span class="text-rose-500">*</span></label>
                            <textarea x-model="content" rows="6" placeholder="配信内容を入力..." class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium resize-none"></textarea>
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-3">
                            <button @click="isModalOpen = false" type="button" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">キャンセル</button>
                            <button @click="sendNotice()" :disabled="!title.trim() || !content.trim() || isSending" :class="title.trim() && content.trim() && !isSending ? 'bg-slate-900 hover:bg-slate-800 text-white shadow-md' : 'bg-slate-200 text-slate-400 cursor-not-allowed'" class="px-6 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                                <template x-if="isSending">
                                    <span class="inline-block animate-spin">🌀</span>
                                </template>
                                <span x-text="isSending ? '配信中...' : 'お知らせを一斉配信する 📢'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function noticeAdmin() {
            return {
                isModalOpen: false,
                title: '',
                category: '重要',
                content: '',
                isSending: false,
                sentSuccess: false,
                errorMessage: '',
                sentHistory: @json($notices ?? []),

                async sendNotice() {
                    if (!this.title.trim() || !this.content.trim()) return;
                    this.isSending = true;
                    this.errorMessage = '';

                    try {
                        const response = await fetch('{{ route("admin.notices.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                title: this.title,
                                category: this.category,
                                content: this.content
                            })
                        });

                        if (!response.ok) {
                            const resData = await response.json().catch(() => null);
                            const msg = resData?.message || `サーバーエラー (Status: ${response.status})`;
                            throw new Error(msg);
                        }

                        const data = await response.json();
                        this.sentHistory.unshift(data.notice);
                        this.isSending = false;
                        this.sentSuccess = true;
                        this.title = '';
                        this.content = '';

                        setTimeout(() => {
                            this.sentSuccess = false;
                            this.isModalOpen = false;
                        }, 1200);

                    } catch (error) {
                        console.error('送信エラー:', error);
                        this.errorMessage = error.message;
                        this.isSending = false;
                    }
                }
            };
        }
        </script>

        <!-- ⑥ アクティブ分析の中身 -->
        <div x-show="currentTab === 'analytics'" x-cloak x-data="{ showChartModal: false, selectedFeature: '' }" class="space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">アクティブ分析</h2>
                    <p class="text-sm text-slate-500 mt-1">ユーザーの利用率、データ推移からサービス改善のヒントを得ることができます。</p>
                </div>
            </div>

            <div x-data="{ summaryPeriod: 'daily' }" class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 pb-3">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sky-500">space_dashboard</span>
                        パフォーマンス・サマリー
                    </h3>
                    
                    <div class="inline-flex p-1 bg-slate-100 rounded-xl overflow-x-auto max-w-full">
                        <template x-for="(label, key) in { daily: '今日 (リアルタイム)', weekly: '週間 (7日間)', monthly: '月間 (30日間)', yearly: '年次 (12ヶ月)' }" :key="key">
                            <button @click="summaryPeriod = key" 
                                    :class="summaryPeriod === key ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                    class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all whitespace-nowrap"
                                    x-text="label">
                            </button>
                        </template>
                    </div>
                </div>

                @php
                    $periods = [
                        'daily' => [
                            ['title' => 'アクティブユーザー', 'val' => number_format($dailyActiveCount ?? 0), 'unit' => '名', 'border' => 'border-rose-200/60', 'dot' => 'bg-rose-500', 'sub' => 'アクティブ率: '.($dauRate ?? 0).'%', 'feat' => 'DAU (今日)', 'btnColor' => 'text-rose-500 hover:text-rose-600 hover:bg-rose-50'],
                            ['title' => '英語学習 利用者数', 'val' => number_format($dailyEnglishUsers ?? 0), 'unit' => '名', 'border' => 'border-amber-200/60', 'dot' => 'bg-amber-400', 'sub' => '利用率: '.($dailyEnglishRate ?? 0).'%', 'feat' => '英語学習機能 (今日)', 'btnColor' => 'text-amber-500 hover:text-amber-600 hover:bg-amber-50'],
                            ['title' => '留学情報 投稿数', 'val' => number_format($dailyPostsCount ?? 0), 'unit' => '件', 'border' => 'border-lime-200/60', 'dot' => 'bg-lime-500', 'sub' => '投稿率: '.(!empty($dailyActiveCount) ? round(($dailyPostsCount / $dailyActiveCount) * 100, 1) : 0).'%', 'feat' => '留学情報投稿 (今日)', 'btnColor' => 'text-lime-600 hover:text-lime-700 hover:bg-lime-50'],
                            ['title' => 'シャワーレビュー数', 'val' => number_format($dailyShowerCount ?? 0), 'unit' => '件', 'border' => 'border-sky-200/60', 'dot' => 'bg-sky-500', 'sub' => '投稿率: '.(!empty($totalUsersCount) ? round(($dailyShowerCount / $totalUsersCount) * 100, 1) : 0).'%', 'feat' => 'シャワーレビュー (今日)', 'btnColor' => 'text-sky-500 hover:text-sky-600 hover:bg-sky-50'],
                        ],
                        'weekly' => [
                            ['title' => 'アクティブユーザー', 'val' => number_format($weeklyActiveCount ?? 0), 'unit' => '名', 'border' => 'border-rose-200/60', 'dot' => 'bg-rose-500', 'sub' => 'アクティブ率: '.($wauRate ?? 0).'%', 'feat' => 'WAU (週間)', 'btnColor' => 'text-rose-500 hover:text-rose-600 hover:bg-rose-50'],
                            ['title' => '英語学習 利用者数', 'val' => number_format($weeklyEnglishUsers ?? 0), 'unit' => '名', 'border' => 'border-amber-200/60', 'dot' => 'bg-amber-400', 'sub' => '利用率: '.($weeklyEnglishRate ?? 0).'%', 'feat' => '英語学習機能 (週間)', 'btnColor' => 'text-amber-500 hover:text-amber-600 hover:bg-amber-50'],
                            ['title' => '留学情報 投稿数', 'val' => number_format($weeklyPostsCount ?? 0), 'unit' => '件', 'border' => 'border-lime-200/60', 'dot' => 'bg-lime-500', 'sub' => '投稿率: '.(!empty($weeklyActiveCount) ? round(($weeklyPostsCount / $weeklyActiveCount) * 100, 1) : 0).'%', 'feat' => '留学情報投稿 (週間)', 'btnColor' => 'text-lime-600 hover:text-lime-700 hover:bg-lime-50'],
                            ['title' => 'シャワーレビュー数', 'val' => number_format($weeklyShowerCount ?? 0), 'unit' => '件', 'border' => 'border-sky-200/60', 'dot' => 'bg-sky-500', 'sub' => '投稿率: '.(!empty($totalUsersCount) ? round(($weeklyShowerCount / $totalUsersCount) * 100, 1) : 0).'%', 'feat' => 'シャワーレビュー (週間)', 'btnColor' => 'text-sky-500 hover:text-sky-600 hover:bg-sky-50'],
                        ],
                        'monthly' => [
                            ['title' => 'アクティブユーザー', 'val' => number_format($monthlyActiveCount ?? 0), 'unit' => '名', 'border' => 'border-rose-200/60', 'dot' => 'bg-rose-500', 'sub' => 'アクティブ率: '.($mauRate ?? 0).'%', 'feat' => 'MAU (月間)', 'btnColor' => 'text-rose-500 hover:text-rose-600 hover:bg-rose-50'],
                            ['title' => '英語学習 利用者数', 'val' => number_format($monthlyEnglishUsers ?? 0), 'unit' => '名', 'border' => 'border-amber-200/60', 'dot' => 'bg-amber-400', 'sub' => '利用率: '.($monthlyEnglishRate ?? 0).'%', 'feat' => '英語学習機能 (月間)', 'btnColor' => 'text-amber-500 hover:text-amber-600 hover:bg-amber-50'],
                            ['title' => '留学情報 投稿数', 'val' => number_format($monthlyPostsCount ?? 0), 'unit' => '件', 'border' => 'border-lime-200/60', 'dot' => 'bg-lime-500', 'sub' => '投稿率: '.(!empty($monthlyActiveCount) ? round(($monthlyPostsCount / $monthlyActiveCount) * 100, 1) : 0).'%', 'feat' => '留学情報投稿 (月間)', 'btnColor' => 'text-lime-600 hover:text-lime-700 hover:bg-lime-50'],
                            ['title' => 'シャワーレビュー数', 'val' => number_format($monthlyShowerCount ?? 0), 'unit' => '件', 'border' => 'border-sky-200/60', 'dot' => 'bg-sky-500', 'sub' => '投稿率: '.(!empty($totalUsersCount) ? round(($monthlyShowerCount / $totalUsersCount) * 100, 1) : 0).'%', 'feat' => 'シャワーレビュー (月間)', 'btnColor' => 'text-sky-500 hover:text-sky-600 hover:bg-sky-50'],
                        ],
                        'yearly' => [
                            ['title' => 'アクティブユーザー', 'val' => number_format($yearlyActiveCount ?? 0), 'unit' => '名', 'border' => 'border-rose-200/60', 'dot' => 'bg-rose-500', 'sub' => 'アクティブ率: '.($yauRate ?? $retentionRate ?? 0).'%', 'feat' => 'YAU (年次)', 'btnColor' => 'text-rose-500 hover:text-rose-600 hover:bg-rose-50'],
                            ['title' => '英語学習 利用者数', 'val' => number_format($yearlyEnglishUsers ?? 0), 'unit' => '名', 'border' => 'border-amber-200/60', 'dot' => 'bg-amber-400', 'sub' => '利用率: '.($yearlyEnglishRate ?? 0).'%', 'feat' => '英語学習機能 (年次)', 'btnColor' => 'text-amber-500 hover:text-amber-600 hover:bg-amber-50'],
                            ['title' => '留学情報 投稿数', 'val' => number_format($yearlyPostsCount ?? 0), 'unit' => '件', 'border' => 'border-lime-200/60', 'dot' => 'bg-lime-500', 'sub' => '投稿率: '.(!empty($yearlyActiveCount) ? round(($yearlyPostsCount / $yearlyActiveCount) * 100, 1) : 0).'%', 'feat' => '留学情報投稿 (年次)', 'btnColor' => 'text-lime-600 hover:text-lime-700 hover:bg-lime-50'],
                            ['title' => 'シャワーレビュー数', 'val' => number_format($yearlyShowerCount ?? 0), 'unit' => '件', 'border' => 'border-sky-200/60', 'dot' => 'bg-sky-500', 'sub' => '投稿率: '.(!empty($totalUsersCount) ? round(($yearlyShowerCount / $totalUsersCount) * 100, 1) : 0).'%', 'feat' => 'シャワーレビュー (年次)', 'btnColor' => 'text-sky-500 hover:text-sky-600 hover:bg-sky-50'],
                        ],
                    ];
                @endphp

                @foreach($periods as $pKey => $cards)
                    <div x-show="summaryPeriod === '{{ $pKey }}'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($cards as $card)
                            <div class="bg-white p-5 rounded-2xl border {{ $card['border'] }} shadow-sm hover:shadow-md transition flex flex-col justify-between">
                                <div class="flex items-start justify-between">
                                    <span class="text-xs font-bold text-slate-400 flex items-center gap-1">
                                        @if(!empty($card['dot']))
                                            <span class="w-2 h-2 rounded-full {{ $card['dot'] }}"></span>
                                        @endif
                                        {{ $card['title'] }}
                                    </span>
                                    <button @click="showChartModal = true; selectedFeature = '{{ $card['feat'] }}'" class="p-1.5 {{ $card['btnColor'] }} rounded-lg transition">
                                        <span class="material-symbols-outlined text-base">show_chart</span>
                                    </button>
                                </div>
                                <div class="my-3 flex items-baseline gap-1">
                                    <span class="text-3xl font-black text-slate-800 tracking-tight">{{ $card['val'] }}</span>
                                    <span class="text-sm font-bold text-slate-400">{{ $card['unit'] }}</span>
                                </div>
                                <div class="text-[11px] text-slate-400 border-t border-slate-100 pt-2.5">
                                    {{ $card['sub'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 pb-3">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sky-500">graph_1</span>
                    機能別分析
                </h3>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
                <!-- 左側 機能別の利用率とアクティブ貢献度 -->
                <section class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div>
                                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-red-500 text-xl" style="color: #ef4444;">bar_chart</span>
                                    機能別の利用率とアクティブ貢献度
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">どの機能がユーザーの定着を牽引しているかを示す分析</p>
                            </div>
                            <button class="text-xs text-slate-500 hover:text-slate-800 font-bold flex items-center gap-1 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg transition">
                                <span>全データ一覧</span>
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </button>
                        </div>

                        <div class="divide-y divide-slate-100">
                            <!-- 機能1: 英語学習 -->
                            <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-3 w-48">
                                    <div class="p-2.5 rounded-xl border flex items-center justify-center" style="background-color: #fef9c3; border-color: #fde047;">
                                        <span class="material-symbols-outlined !text-2xl leading-none" style="color: #ca8a04;">menu_book</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800">英語学習機能</div>
                                        <div class="text-[11px] text-slate-400">利用ユーザー: {{ number_format($weeklyEnglishUsers ?? 0) }}名</div>
                                    </div>
                                </div>
                                <div class="flex-1 max-w-md space-y-1">
                                    <div class="flex justify-between text-xs font-bold">
                                        <span class="text-slate-500">全体利用率</span>
                                        <span class="font-extrabold" style="color: #ca8a04;">{{ $weeklyEnglishRate ?? 0 }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full" style="width: {{ $weeklyEnglishRate ?? 0 }}%; background-color: #facc15;"></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 text-right justify-between sm:justify-end">
                                    <div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase">ユーザーアクセス貢献度</div>
                                        <span class="inline-block px-2 py-0.5 font-bold text-xs rounded-md border" style="background-color: #fef9c3; color: #854d0e; border-color: #fde047;">高 (極めて効果的)</span>
                                    </div>
                                    <button @click="showChartModal = true; selectedFeature = '英語学習機能 (詳細分析)'" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition">
                                        <span class="material-symbols-outlined text-base">chevron_right</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- ⑦ 目安箱の中身 -->
        <div
            x-show="currentTab === 'suggestions'"
            x-cloak
            x-data="{
                statusFilter: 'all',
                items: [],
                editingNote: {},

                get filteredItems() {
                    if (this.statusFilter === 'all') return this.items;
                    return this.items.filter(item => item.status === this.statusFilter);
                },

                async load() {
                    const response = await fetch('{{ route('admin.suggestions.data') }}');
                    const data = await response.json();
                    this.items = data.items;
                    data.items.forEach(item => { this.editingNote[item.id] = item.admin_note ?? ''; });
                },

                async updateStatus(item, newStatus) {
                    const response = await fetch(`/admin/suggestions/${item.id}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ status: newStatus, admin_note: this.editingNote[item.id] }),
                    });

                    if (response.ok) {
                        item.status = newStatus;
                        item.status_label = {{ Js::from(\App\Models\Suggestion::STATUSES) }}[newStatus];
                    }
                },
            }"
            x-init="load()"
        >
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">目安箱</h2>
                    <p class="text-sm text-slate-500 mt-1">留学生から寄せられたご意見・お困りごとを確認・対応します。</p>
                </div>

                <div class="flex items-center gap-1 bg-slate-200/80 p-1 rounded-xl text-xs font-bold">
                    <button @click="statusFilter = 'all'"
                        :class="statusFilter === 'all' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        class="px-3 py-1.5 rounded-lg transition">すべて</button>
                    @foreach (\App\Models\Suggestion::STATUSES as $value => $label)
                        <button @click="statusFilter = '{{ $value }}'"
                            :class="statusFilter === '{{ $value }}' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                            class="px-3 py-1.5 rounded-lg transition">{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            <div class="space-y-4">
                <template x-for="item in filteredItems" :key="item.id">
                    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[11px] font-bold rounded" x-text="item.category_label"></span>
                                <span class="text-xs text-slate-400" x-text="item.user_name"></span>
                                <span class="text-xs text-slate-400" x-text="item.created_at"></span>
                            </div>
                            <span
                                class="px-2 py-0.5 text-[11px] font-bold rounded"
                                :class="{
                                    'bg-red-100 text-red-700': item.status === 'pending',
                                    'bg-amber-100 text-amber-700': item.status === 'in_progress',
                                    'bg-emerald-100 text-emerald-700': item.status === 'resolved',
                                }"
                                x-text="item.status_label"
                            ></span>
                        </div>

                        <p class="text-sm text-slate-700 whitespace-pre-line mb-4" x-text="item.comment"></p>

                        <div class="flex flex-col gap-2">
                            <div class="flex gap-2 items-center">
                                <select
                                    x-model="item.status"
                                    @change="updateStatus(item, item.status)"
                                    class="text-xs border border-slate-200 rounded-lg px-2 py-1.5"
                                >
                                    @foreach (\App\Models\Suggestion::STATUSES as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <textarea
                                x-model="editingNote[item.id]"
                                @blur="updateStatus(item, item.status)"
                                rows="2"
                                placeholder="対応メモ(内部用)"
                                class="text-xs border border-slate-200 rounded-lg px-2 py-1.5 resize-none"
                            ></textarea>
                        </div>
                    </div>
                </template>

                <template x-if="filteredItems.length === 0">
                    <p class="text-center text-slate-400 text-sm py-12">該当する投稿がありません</p>
                </template>
            </div>
        </div>

    </main>
</div>
@endsection