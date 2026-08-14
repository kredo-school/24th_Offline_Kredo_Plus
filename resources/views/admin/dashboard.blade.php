@extends('layouts.app')

@section('content')
<!-- x-data で「今どのタブ（メニュー）を開いているか」を管理します (初期値: 'dashboard') -->
<div x-data="{ currentTab: '{{ session('accountCreated') || $errors->any() ? 'accounts' : 'dashboard' }}' }" class="flex min-h-screen bg-slate-100">

    <!-- 1. 左側：サイドバー -->
    <aside class="w-64 bg-slate-900 text-white p-6 shrink-0 hidden md:block">
        <h1 class="text-xl font-bold mb-8 text-sky-400">Kredo Admin</h1>
        <nav class="space-y-2">
            
            <!-- ダッシュボード -->
            <button @click="currentTab = 'dashboard'" 
                    :class="currentTab === 'dashboard' ? 'bg-brand-blue text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-left">
                <span>📊</span> ダッシュボード
            </button>

            <!-- ユーザー管理 -->
            <button @click="currentTab = 'users'" 
                    :class="currentTab === 'users' ? 'bg-brand-blue text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-left">
                <span>👥</span> ユーザー管理
            </button>

            <!-- アカウント作成 -->
            <button @click="currentTab = 'accounts'"
                    :class="currentTab === 'accounts' ? 'bg-brand-blue text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-left">
                <span>🔑</span> アカウント作成
            </button>

            <!-- ポスト管理 -->
            <button @click="currentTab = 'posts'"
                    :class="currentTab === 'posts' ? 'bg-brand-blue text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-left">
                <span>📝</span> ポスト管理
            </button>

            <!-- お知らせ送信機能 -->
            <button @click="currentTab = 'notice'" 
                    :class="currentTab === 'notice' ? 'bg-brand-blue text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-left">
                <span>📢</span> お知らせ送信機能
            </button>

            <!-- サービス使用状況分析 -->
            <button @click="currentTab = 'analytics'" 
                    :class="currentTab === 'analytics' ? 'bg-brand-blue text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-left">
                <span>📈</span> サービス使用状況分析
            </button>

            <!-- 目安箱 -->
            <button @click="currentTab = 'suggestions'" 
                    :class="currentTab === 'suggestions' ? 'bg-brand-blue text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-left">
                <span>📮</span> 目安箱
            </button>

        </nav>
    </aside>

    <!-- 2. 右側：メインコンテンツエリア（切り替え表示領域） -->
    <main class="flex-1 p-8">

        <!-- ① ダッシュボードの中身 -->
        <div x-show="currentTab === 'dashboard'">
            <!-- ヘッダーエリア -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">ダッシュボード</h2>
                    <p class="text-sm text-slate-500 mt-1">Kredo Plusの現在の稼働状況と最新アクティビティです。</p>
                </div>
                <span class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    システム正常稼働中
                </span>
            </div>

            <!-- クイックサマリーカード（4列） -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between text-slate-400 mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider">総ユーザー数</span>
                        <span class="p-2 bg-slate-100 rounded-xl text-lg">👥</span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-slate-800">128</span>
                        <span class="text-xs font-bold text-emerald-600">▲ +12</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between text-slate-400 mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider">今日のシャワー利用</span>
                        <span class="p-2 bg-sky-50 rounded-xl text-lg">🚿</span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-slate-800">42</span>
                        <span class="text-xs text-slate-400">回更新</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between text-slate-400 mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider">英語学習（今日）</span>
                        <span class="p-2 bg-amber-50 rounded-xl text-lg">📖</span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-slate-800">85</span>
                        <span class="text-xs text-slate-400">レッスン完了</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between text-slate-400 mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider">レストラン掲載数</span>
                        <span class="p-2 bg-rose-50 rounded-xl text-lg">🍕</span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-slate-800">24</span>
                        <span class="text-xs text-slate-400">店舗</span>
                    </div>
                </div>
            </div>

            <!-- 機能別クイックステータス & 最新アクティビティ -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span>🚿</span> 寮シャワー使用状況サマリー
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div class="p-4 bg-sky-50 rounded-xl border border-sky-100 flex justify-between items-center">
                                <div>
                                    <p class="text-xs font-bold text-sky-600">男子寮</p>
                                    <p class="text-lg font-bold text-slate-800 mt-0.5">{{ $maleFull ? '満室' : '空きあり' }}</p>
                                </div>
                                <span class="px-2.5 py-1 {{ $maleFull ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }} text-xs font-bold rounded-full">
                                    {{ $maleFull ? '混雑中' : '快適' }}
                                </span>
                            </div>
                            <div class="p-4 bg-rose-50 rounded-xl border border-rose-100 flex justify-between items-center">
                                <div>
                                    <p class="text-xs font-bold text-rose-600">女子寮</p>
                                    <p class="text-lg font-bold text-slate-800 mt-0.5">{{ $femaleFull ? '満室' : '空きあり' }}</p>
                                </div>
                                <span class="px-2.5 py-1 {{ $femaleFull ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }} text-xs font-bold rounded-full">
                                    {{ $femaleFull ? '混雑中' : '快適' }}
                                </span>
                            </div>
                        </div>

                        {{-- ↓ここから故障報告の情報 --}}
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-bold text-slate-700">⚠️ 故障中のシャワー ({{ $brokenShowers->count() }}件)</p>
                                <a href="{{ route('admin.shower.malfunctions.index') }}" class="text-xs font-semibold text-brand-blue hover:underline">詳細・履歴を見る →</a>
                            </div>

                            @forelse ($brokenShowers as $report)
                                <div class="flex items-center justify-between p-3 bg-amber-50 rounded-xl border border-amber-100 text-xs mb-2">
                                    <div>
                                        <span class="font-bold text-slate-800">
                                            {{ $report->gender === 'male' ? '男子寮' : '女子寮' }} {{ $report->shower_number }}番
                                        </span>
                                        <span class="text-slate-400 ml-2">{{ $report->created_at->diffForHumans() }}</span>
                                        @if ($report->comment)
                                            <p class="text-slate-500 mt-1">{{ $report->comment }}</p>
                                        @endif
                                    </div>
                                    <form action="{{ route('admin.shower.malfunctions.fix', [$report->gender, $report->shower_number]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-slate-900 text-white font-bold rounded-lg hover:bg-slate-800 transition">
                                            修理完了
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400">現在、故障中のシャワーはありません。</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                <span>📝</span> 最近の投稿アクティビティ
                            </h3>
                            <button @click="currentTab = 'posts'" class="text-xs font-semibold text-brand-blue hover:underline">すべて見る →</button>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center text-xs">K</div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">Kannaさんが「IT Parkおすすめカフェ」を投稿しました</p>
                                        <p class="text-xs text-slate-400">10分前・レストラン/カフェ</p>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-slate-500">承認済み</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 font-bold flex items-center justify-center text-xs">M</div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">MateoさんがTOEIC Part 5 スライドを完了しました</p>
                                        <p class="text-xs text-slate-400">25分前・英語学習</p>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-slate-500">自動更新</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <h3 class="font-bold text-slate-800 mb-4">クイック操作</h3>
                        <div class="space-y-2">
                            <button @click="currentTab = 'notice'" class="w-full py-2.5 px-4 bg-slate-900 text-white rounded-xl text-xs font-semibold hover:bg-slate-800 transition text-left flex items-center justify-between">
                                <span>📢 お知らせを新規配信</span>
                                <span>→</span>
                            </button>
                            <button @click="currentTab = 'users'" class="w-full py-2.5 px-4 bg-slate-100 text-slate-700 rounded-xl text-xs font-semibold hover:bg-slate-200 transition text-left flex items-center justify-between">
                                <span>👥 新規ユーザーの確認</span>
                                <span>→</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ② ユーザー管理の中身 -->
        <div x-show="currentTab === 'users'" x-cloak x-data="{ 
            selectedUser: null, 
            searchQuery: '',
            directMessage: '',
            messageSent: false,
            sendAdminMessage() {
                if (!this.directMessage.trim()) return;
                this.messageSent = true;
                setTimeout(() => {
                    this.messageSent = false;
                    this.directMessage = '';
                }, 3000);
            },
            users: [
                {
                    id: 1,
                    name: 'Kanna Yamada',
                    email: 'kanna@example.com',
                    dorm: '女子寮 (Female)',
                    course: 'IT&英語コース',
                    registered_at: '2026/05/10',
                    last_active: '5分前',
                    active_hours: '124時間',
                    post_count: 18,
                    likes_received: 142,
                    is_popular: true,
                    reported_count: 0,
                    status: 'active',
                    posts: [
                        { title: 'IT Parkおすすめカフェ「Abaca」', category: 'レストラン', date: '2026/07/20', likes: 45 },
                        { title: 'TOEIC Part 5 勉強ノート共有', category: '英語学習', date: '2026/07/15', likes: 32 }
                    ]
                },
                {
                    id: 2,
                    name: 'Mateo Sato',
                    email: 'mateo@example.com',
                    dorm: '男子寮 (Male)',
                    course: 'TOEIC集中コース',
                    registered_at: '2026/06/01',
                    last_active: '2時間前',
                    active_hours: '45時間',
                    post_count: 3,
                    likes_received: 8,
                    is_popular: false,
                    reported_count: 2,
                    report_reason: '不適切な言葉遣いによる通報（2026/07/02）',
                    status: 'warning',
                    posts: [
                        { title: 'ナイトマーケットの混雑状況', category: '生活情報', date: '2026/07/02', likes: 2 }
                    ]
                },
                {
                    id: 3,
                    name: 'Ren Suzuki',
                    email: 'ren@example.com',
                    dorm: '男子寮 (Male)',
                    course: 'ビジネス英語コース',
                    registered_at: '2026/04/15',
                    last_active: '1日前',
                    active_hours: '210時間',
                    post_count: 25,
                    likes_received: 310,
                    is_popular: true,
                    reported_count: 0,
                    status: 'active',
                    posts: [
                        { title: 'カレンデリアでの注文のコツまとめ', category: 'レストラン', date: '2026/07/18', likes: 88 }
                    ]
                }
            ]
        }">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">ユーザー管理</h2>
                    <p class="text-sm text-slate-500 mt-1">登録留学生の利用状況、アクティビティ、通報状況を一元管理します。</p>
                </div>
                
                <div class="relative w-full sm:w-72">
                    <input type="text" x-model="searchQuery" placeholder="名前・メールで検索..." 
                           class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                    <span class="absolute left-3 top-2.5 text-slate-400 text-sm">🔍</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase font-bold">
                                <th class="py-3.5 px-4">ユーザー</th>
                                <th class="py-3.5 px-4">所属/寮</th>
                                <th class="py-3.5 px-4">ステータス / 特徴</th>
                                <th class="py-3.5 px-4">最終アクティブ</th>
                                <th class="py-3.5 px-4 text-center">通報歴</th>
                                <th class="py-3.5 px-4 text-right">操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            <template x-for="user in users.filter(u => u.name.toLowerCase().includes(searchQuery.toLowerCase()) || u.email.toLowerCase().includes(searchQuery.toLowerCase()))" :key="user.id">
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-slate-800 text-white font-bold flex items-center justify-center text-xs shrink-0"
                                                 x-text="user.name.charAt(0)"></div>
                                            <div>
                                                <p class="font-bold text-slate-800 leading-tight" x-text="user.name"></p>
                                                <p class="text-xs text-slate-400 mt-0.5" x-text="user.email"></p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-4 px-4">
                                        <p class="text-xs font-semibold text-slate-700" x-text="user.dorm"></p>
                                        <p class="text-[11px] text-slate-400" x-text="user.course"></p>
                                    </td>

                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <template x-if="user.is_popular">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-bold rounded-md">
                                                    ⭐ 人気投稿者
                                                </span>
                                            </template>
                                            <template x-if="user.reported_count > 0">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[11px] font-bold rounded-md">
                                                    ⚠️ 要注意 (通報<span x-text="user.reported_count"></span>件)
                                                </span>
                                            </template>
                                            <template x-if="!user.is_popular && user.reported_count === 0">
                                                <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 text-slate-600 text-[11px] font-medium rounded-md">
                                                    一般
                                                </span>
                                            </template>
                                        </div>
                                    </td>

                                    <td class="py-4 px-4 text-xs text-slate-500" x-text="user.last_active"></td>

                                    <td class="py-4 px-4 text-center">
                                        <span :class="user.reported_count > 0 ? 'text-rose-600 font-extrabold' : 'text-slate-400'" 
                                              x-text="user.reported_count + ' 件'"></span>
                                    </td>

                                    <td class="py-4 px-4 text-right">
                                        <button @click="selectedUser = user; directMessage = ''; messageSent = false;" 
                                                class="px-3 py-1.5 bg-slate-100 hover:bg-brand-blue hover:text-white text-slate-700 text-xs font-bold rounded-lg transition">
                                            詳細を見る
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ユーザー詳細モーダル -->
            <div x-show="selectedUser" x-cloak 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                
                <div @click.outside="selectedUser = null" 
                     class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 space-y-6">
                    
                    <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-slate-900 text-white font-extrabold flex items-center justify-center text-lg"
                                 x-text="selectedUser?.name.charAt(0)"></div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-xl font-bold text-slate-800" x-text="selectedUser?.name"></h3>
                                    <template x-if="selectedUser?.is_popular">
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-extrabold rounded-md">⭐ 人気ユーザー</span>
                                    </template>
                                </div>
                                <p class="text-xs text-slate-400 mt-0.5" x-text="selectedUser?.email"></p>
                            </div>
                        </div>
                        <button @click="selectedUser = null" class="text-slate-400 hover:text-slate-600 text-xl font-bold p-1">✕</button>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-center">
                            <p class="text-[11px] font-bold text-slate-400">総アクティブ時間</p>
                            <p class="text-lg font-extrabold text-slate-800 mt-0.5" x-text="selectedUser?.active_hours"></p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-center">
                            <p class="text-[11px] font-bold text-slate-400">総投稿数</p>
                            <p class="text-lg font-extrabold text-slate-800 mt-0.5" x-text="selectedUser?.post_count + ' 件'"></p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-center">
                            <p class="text-[11px] font-bold text-slate-400">総獲得いいね</p>
                            <p class="text-lg font-extrabold text-amber-600 mt-0.5" x-text="selectedUser?.likes_received + ' ♡'"></p>
                        </div>
                    </div>

                    <template x-if="selectedUser?.reported_count > 0">
                        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl">
                            <div class="flex items-center gap-2 text-rose-700 font-bold text-xs mb-1">
                                <span>⚠️ 通報履歴あり</span>
                                <span class="px-1.5 py-0.5 bg-rose-200 text-rose-800 rounded text-[10px]" x-text="selectedUser?.reported_count + '件'"></span>
                            </div>
                            <p class="text-xs text-rose-800" x-text="selectedUser?.report_reason"></p>
                        </div>
                    </template>

                    <div class="space-y-2 text-xs">
                        <h4 class="font-bold text-slate-800 text-sm">基本情報</h4>
                        <div class="grid grid-cols-2 gap-2 bg-slate-50 p-3 rounded-xl">
                            <div><span class="text-slate-400">滞在寮:</span> <span class="font-semibold text-slate-700" x-text="selectedUser?.dorm"></span></div>
                            <div><span class="text-slate-400">受講コース:</span> <span class="font-semibold text-slate-700" x-text="selectedUser?.course"></span></div>
                            <div><span class="text-slate-400">登録日:</span> <span class="font-semibold text-slate-700" x-text="selectedUser?.registered_at"></span></div>
                            <div><span class="text-slate-400">最終ログイン:</span> <span class="font-semibold text-slate-700" x-text="selectedUser?.last_active"></span></div>
                        </div>
                    </div>

                    <div class="space-y-2 pt-2 border-t border-slate-100">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-slate-800 text-sm flex items-center gap-1.5">
                                <span>💬</span> 運営ダイレクトメッセージ
                            </h4>
                            <span class="text-[11px] text-slate-400">ユーザーのアプリ内通知に直接届きます</span>
                        </div>
                        
                        <div class="space-y-2">
                            <textarea x-model="directMessage" rows="2" 
                                      :placeholder="selectedUser?.name + ' さんへ個別のメッセージ（注意喚起・サポート等）を入力...'"
                                      class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition resize-none"></textarea>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <template x-if="messageSent">
                                        <span class="text-xs font-bold text-emerald-600 flex items-center gap-1 animate-pulse">
                                            <span>順調に送信されました！メッセージをユーザーへ配信しました。</span>
                                        </span>
                                    </template>
                                </div>
                                <button @click="sendAdminMessage()" 
                                        :disabled="!directMessage.trim()"
                                        :class="directMessage.trim() ? 'bg-brand-blue hover:bg-sky-600 text-white shadow-sm' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                                        class="px-4 py-1.5 text-xs font-bold rounded-lg transition flex items-center gap-1.5">
                                    <span>送信する</span>
                                    <span>📩</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 pt-2 border-t border-slate-100">
                        <h4 class="font-bold text-slate-800 text-sm">投稿履歴</h4>
                        <div class="space-y-2 max-h-36 overflow-y-auto">
                            <template x-for="post in selectedUser?.posts" :key="post.title">
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl text-xs">
                                    <div>
                                        <span class="px-2 py-0.5 bg-white border border-slate-200 rounded text-[10px] font-bold text-slate-600 mr-2" x-text="post.category"></span>
                                        <span class="font-bold text-slate-800" x-text="post.title"></span>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-400">
                                        <span x-text="post.date"></span>
                                        <span class="text-amber-600 font-bold" x-text="'♡ ' + post.likes"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <button class="px-4 py-2 bg-rose-100 text-rose-700 hover:bg-rose-200 text-xs font-bold rounded-xl transition">
                            アカウント凍結
                        </button>
                        <button @click="selectedUser = null" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition">
                            閉じる
                        </button>
                    </div>

                </div>
            </div>

        </div>

        <!-- ⑥ アカウント作成の中身 -->
        <div x-show="currentTab === 'accounts'" x-cloak>
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-800">アカウント作成</h2>
                <p class="text-sm text-slate-500 mt-1">管理者が学生アカウントを発行します。作成後、IDとパスワードを学生へ個別にお伝えください。</p>
            </div>

            <div class="max-w-xl bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-6">

                @if (session('accountCreated'))
                    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-bold space-y-1">
                        <p>✅ アカウントを作成しました。以下の情報を学生にお伝えください。</p>
                        <p>ID（メールアドレス）: {{ session('accountCreated')['email'] }}</p>
                        <p>初期パスワード: {{ session('accountCreated')['password'] }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs font-bold space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">氏名 <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">メールアドレス（ログインID） <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">初期パスワード（8文字以上） <span class="text-rose-500">*</span></label>
                        <input type="text" name="password" required minlength="8"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                    </div>

                    <button type="submit"
                            class="w-full py-3 px-6 rounded-xl text-xs font-bold transition bg-slate-900 hover:bg-slate-800 text-white shadow-md">
                        アカウントを作成する
                    </button>
                </form>
            </div>
        </div>

        <!-- ③ ポスト管理の中身 -->
        <div x-show="currentTab === 'posts'" x-cloak x-data="{ 
            selectedPost: null,
            searchQuery: '',
            selectedCategory: 'all',
            selectedFilter: 'all',
            adminNotice: '',
            noticeSent: false,
            sendPostNotice() {
                if (!this.adminNotice.trim()) return;
                this.noticeSent = true;
                setTimeout(() => {
                    this.noticeSent = false;
                    this.adminNotice = '';
                }, 3000);
            },
            posts: [
                {
                    id: 101,
                    title: 'IT Parkおすすめカフェ「Abaca」のWiFi環境',
                    content: 'IT Park内にあるAbaca Baking Companyは電源も豊富でコンセント席が多く、作業に最適です！コーヒーも美味しいですが、週末の午後は混雑するので午前中がおすすめ。',
                    category: 'レストラン',
                    user_name: 'Kanna Yamada',
                    user_email: 'kanna@example.com',
                    created_at: '2026/07/20 14:30',
                    likes: 45,
                    comments_count: 8,
                    reported_count: 0,
                    report_reasons: [],
                    status: 'published'
                },
                {
                    id: 102,
                    title: '深夜の騒音について（男子寮3F）',
                    content: '昨日の夜1時過ぎまで3Fのラウンジで大声で話しているグループがいてうるさかったです。ルールを守ってください。',
                    category: '生活情報',
                    user_name: 'Mateo Sato',
                    user_email: 'mateo@example.com',
                    created_at: '2026/07/21 02:15',
                    likes: 3,
                    comments_count: 12,
                    reported_count: 3,
                    report_reasons: ['個人攻撃・誹謗中傷の可能性', '不適切な表現'],
                    status: 'reported'
                },
                {
                    id: 103,
                    title: 'カレンデリアでの注文のコツまとめ',
                    content: 'ローカル食堂（カレンデリア）で指差し注文する時のコツをまとめました！お腹を壊さないためのスープの選び方も解説しています。',
                    category: 'レストラン',
                    user_name: 'Ren Suzuki',
                    user_email: 'ren@example.com',
                    created_at: '2026/07/18 19:00',
                    likes: 88,
                    comments_count: 15,
                    reported_count: 0,
                    report_reasons: [],
                    status: 'published'
                }
            ],
            get filteredPosts() {
                return this.posts.filter(post => {
                    const matchesSearch = post.title.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                          post.content.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                          post.user_name.toLowerCase().includes(this.searchQuery.toLowerCase());
                    const matchesCategory = this.selectedCategory === 'all' || post.category === this.selectedCategory;
                    const matchesFilter = this.selectedFilter === 'all' || 
                                         (this.selectedFilter === 'reported' && post.reported_count > 0) ||
                                         (this.selectedFilter === 'hidden' && post.status === 'hidden');
                    return matchesSearch && matchesCategory && matchesFilter;
                });
            }
        }">

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">ポスト管理</h2>
                    <p class="text-sm text-slate-500 mt-1">投稿された口コミやコミュニティ記事の確認・通報対応・表示制御を行います。</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <select x-model="selectedCategory" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/20">
                        <option value="all">すべてのカテゴリ</option>
                        <option value="レストラン">レストラン</option>
                        <option value="生活情報">生活情報</option>
                        <option value="英語学習">英語学習</option>
                    </select>

                    <select x-model="selectedFilter" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/20">
                        <option value="all">すべてのステータス</option>
                        <option value="reported">⚠️ 通報ありのみ</option>
                        <option value="hidden">🔒 非表示のみ</option>
                    </select>

                    <div class="relative w-full sm:w-60">
                        <input type="text" x-model="searchQuery" placeholder="キーワード・投稿者検索..." 
                               class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                        <span class="absolute left-3 top-2 text-slate-400 text-xs">🔍</span>
                    </div>
                </div>
            </div>

            <div class="space-y-4 mb-8">
                <template x-for="post in filteredPosts" :key="post.id">
                    <div class="bg-white rounded-2xl p-5 border transition shadow-sm hover:shadow-md"
                         :class="post.reported_count > 0 ? 'border-rose-200 bg-rose-50/20' : 'border-slate-200'">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-800 text-white font-bold flex items-center justify-center text-xs shrink-0"
                                     x-text="post.user_name.charAt(0)"></div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-800 text-sm" x-text="post.user_name"></span>
                                        <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-[10px] font-bold text-slate-600" x-text="post.category"></span>
                                        <template x-if="post.status === 'hidden'">
                                            <span class="px-2 py-0.5 bg-slate-200 text-slate-700 rounded text-[10px] font-extrabold">🔒 非表示中</span>
                                        </template>
                                    </div>
                                    <p class="text-[11px] text-slate-400" x-text="post.created_at"></p>
                                </div>
                            </div>

                            <template x-if="post.reported_count > 0">
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold border border-rose-200 shrink-0 self-start sm:self-auto">
                                    <span>⚠️ 通報</span>
                                    <span x-text="post.reported_count + '件'"></span>
                                </div>
                            </template>
                        </div>

                        <div class="mb-4">
                            <h3 class="font-bold text-slate-800 text-base mb-1" x-text="post.title"></h3>
                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed" x-text="post.content"></p>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                            <div class="flex items-center gap-4 text-slate-400 font-medium">
                                <span class="flex items-center gap-1 text-amber-600 font-bold">
                                    <span>♡</span> <span x-text="post.likes"></span>
                                </span>
                                <span class="flex items-center gap-1">
                                    <span>💬</span> <span x-text="post.comments_count + ' コメント'"></span>
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <button @click="post.status = (post.status === 'hidden' ? 'published' : 'hidden')"
                                        :class="post.status === 'hidden' ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                        class="px-3 py-1.5 rounded-lg font-bold transition">
                                    <span x-text="post.status === 'hidden' ? '再公開する' : '非表示にする'"></span>
                                </button>

                                <button @click="selectedPost = post; adminNotice = ''; noticeSent = false;"
                                        class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-lg transition">
                                    詳細・管理
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="filteredPosts.length === 0">
                    <div class="text-center py-12 bg-white rounded-2xl border border-slate-200">
                        <p class="text-2xl mb-2">🔍</p>
                        <p class="text-sm font-bold text-slate-600">該当する投稿が見つかりませんでした</p>
                        <p class="text-xs text-slate-400 mt-1">検索条件やフィルターを変更してみてください。</p>
                    </div>
                </template>
            </div>

            <!-- 投稿詳細・管理モーダル -->
            <div x-show="selectedPost" x-cloak 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                
                <div @click.outside="selectedPost = null" 
                     class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 space-y-6">
                    
                    <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-1 bg-brand-blue/10 text-brand-blue font-bold text-xs rounded-lg" x-text="selectedPost?.category"></span>
                            <span class="text-xs text-slate-400" x-text="selectedPost?.created_at"></span>
                        </div>
                        <button @click="selectedPost = null" class="text-slate-400 hover:text-slate-600 text-xl font-bold p-1">✕</button>
                    </div>

                    <template x-if="selectedPost?.reported_count > 0">
                        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl space-y-1.5">
                            <div class="flex items-center justify-between text-rose-700 font-bold text-xs">
                                <span class="flex items-center gap-1">⚠️ 寄せられた通報理由</span>
                                <span x-text="selectedPost?.reported_count + '件の通報'"></span>
                            </div>
                            <ul class="list-disc list-inside text-xs text-rose-800 space-y-1">
                                <template x-for="reason in selectedPost?.report_reasons" :key="reason">
                                    <li x-text="reason"></li>
                                </template>
                            </ul>
                        </div>
                    </template>

                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-slate-800" x-text="selectedPost?.title"></h3>
                        <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line p-4 bg-slate-50 rounded-xl border border-slate-100" x-text="selectedPost?.content"></p>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl text-xs">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-slate-800 text-white font-bold flex items-center justify-center text-[11px]"
                                 x-text="selectedPost?.user_name.charAt(0)"></div>
                            <div>
                                <span class="font-bold text-slate-800" x-text="selectedPost?.user_name"></span>
                                <span class="text-slate-400 ml-1" x-text="'(' + selectedPost?.user_email + ')'"></span>
                            </div>
                        </div>
                        <span class="text-slate-400 font-medium" x-text="'投稿ID: #' + selectedPost?.id"></span>
                    </div>

                    <div class="space-y-2 pt-2 border-t border-slate-100">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                <span>📩</span> 投稿者への削除理由・注意メッセージ送信
                            </h4>
                        </div>
                        
                        <div class="space-y-2">
                            <textarea x-model="adminNotice" rows="2" 
                                      :placeholder="selectedPost?.user_name + ' さんへこの投稿に関する注意事項や修正・削除理由を送信...'"
                                      class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition resize-none"></textarea>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <template x-if="noticeSent">
                                        <span class="text-xs font-bold text-emerald-600 flex items-center gap-1 animate-pulse">
                                            <span>順調に送信されました！投稿者へ通知を送信しました。</span>
                                        </span>
                                    </template>
                                </div>
                                <button @click="sendPostNotice()" 
                                        :disabled="!adminNotice.trim()"
                                        :class="adminNotice.trim() ? 'bg-brand-blue hover:bg-sky-600 text-white' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                                        class="px-4 py-1.5 text-xs font-bold rounded-lg transition">
                                    通知を送信
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <button @click="selectedPost.status = (selectedPost.status === 'hidden' ? 'published' : 'hidden')"
                                :class="selectedPost?.status === 'hidden' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-700 hover:bg-rose-200'"
                                class="px-4 py-2 text-xs font-bold rounded-xl transition">
                            <span x-text="selectedPost?.status === 'hidden' ? '🔒 非表示を解除（再公開）' : '🚫 この投稿を非表示にする'"></span>
                        </button>

                        <button @click="selectedPost = null" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition">
                            閉じる
                        </button>
                    </div>

                </div>
            </div>

        </div>

        <!-- ④ お知らせ送信の中身 -->
        <div x-show="currentTab === 'notice'" x-cloak x-data="{
            title: '',
            category: '重要',
            target: 'all',
            content: '',
            sendPush: true,
            isSending: false,
            sentSuccess: false,
            sentHistory: [
                {
                    id: 1,
                    title: '【重要】週末の寮内Wi-Fiメンテナンスのお知らせ',
                    category: '重要',
                    target: '全員',
                    sent_at: '2026/07/20 10:00',
                    content: '今週末の土曜日 深夜2:00〜5:00にかけて、寮内ネットワーク機器の定期メンテナンスを行います。期間中はWi-Fiが一時的に切断されますのでご注意ください。',
                    push_sent: true
                },
                {
                    id: 2,
                    title: '今週金曜日にウェルカムパーティを開催します🎉',
                    category: 'イベント',
                    target: '全員',
                    sent_at: '2026/07/15 17:30',
                    content: '新入生を歓迎する夕食交流会をラウンジで開催します！持ち寄り大歓迎です。みんなで楽しみましょう！',
                    push_sent: true
                }
            ],
            sendNotice() {
                if (!this.title.trim() || !this.content.trim()) return;
                this.isSending = true;
                
                setTimeout(() => {
                    this.sentHistory.unshift({
                        id: Date.now(),
                        title: this.title,
                        category: this.category,
                        target: this.target === 'all' ? '全員' : (this.target === 'male_dorm' ? '男子寮生のみ' : '女子寮生のみ'),
                        sent_at: 'たった今',
                        content: this.content,
                        push_sent: this.sendPush
                    });

                    this.isSending = false;
                    this.sentSuccess = true;

                    this.title = '';
                    this.content = '';

                    setTimeout(() => {
                        this.sentSuccess = false;
                    }, 4000);
                }, 800);
            }
        }">
            
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-800">お知らせ送信機能</h2>
                <p class="text-sm text-slate-500 mt-1">留学生のアプリ内通知やプッシュ通知へ、全体・対象別のお知らせを一斉配信します。</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-7 bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-6">
                    <h3 class="font-bold text-slate-800 text-base flex items-center gap-2 border-b border-slate-100 pb-4">
                        <span>✏️</span> お知らせの新規作成
                    </h3>

                    <template x-if="sentSuccess">
                        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-800 text-xs font-bold animate-pulse">
                            <span class="text-lg">🎉</span>
                            <span>お知らせの配信が完了しました！留学生のアプリへ正常に通知されました。</span>
                        </div>
                    </template>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">お知らせタイトル <span class="text-rose-500">*</span></label>
                            <input type="text" x-model="title" placeholder="例: 【重要】週末の寮内Wi-Fiメンテナンスについて"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">配信カテゴリ</label>
                                <select x-model="category" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/20">
                                    <option value="重要">🚨 重要なお知らせ</option>
                                    <option value="イベント">🎉 イベント・交流会</option>
                                    <option value="生活">🏠 寮・生活情報</option>
                                    <option value="学習">📖 英語学習</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">配信対象</label>
                                <select x-model="target" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/20">
                                    <option value="all">👥 ユーザー全員 (全留学生)</option>
                                    <option value="male_dorm">👨 男子寮生のみ</option>
                                    <option value="female_dorm">👩 女子寮生のみ</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">お知らせ本文 <span class="text-rose-500">*</span></label>
                            <textarea x-model="content" rows="6" placeholder="配信したい内容の詳細を入力してください..."
                                      class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition resize-none"></textarea>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-base">🔔</span>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">スマホへの即時プッシュ通知</p>
                                    <p class="text-[11px] text-slate-400">端末の通知センターへポップアップを配信します</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" x-model="sendPush" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-blue"></div>
                            </label>
                        </div>

                        <div class="pt-2">
                            <button @click="sendNotice()" 
                                    :disabled="!title.trim() || !content.trim() || isSending"
                                    :class="title.trim() && content.trim() && !isSending ? 'bg-slate-900 hover:bg-slate-800 text-white shadow-md' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                                    class="w-full py-3 px-6 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                                <template x-if="isSending">
                                    <span class="inline-block animate-spin">🌀</span>
                                </template>
                                <span x-text="isSending ? '配信中...' : 'お知らせを一斉配信する 📢'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-slate-900 p-5 rounded-3xl shadow-lg border border-slate-800 text-white space-y-3">
                        <div class="flex items-center justify-between text-[11px] text-slate-400 border-b border-slate-800 pb-2">
                            <span class="font-bold flex items-center gap-1">📱 アプリ内表示イメージ</span>
                            <span>プレビュー</span>
                        </div>

                        <div class="bg-slate-800/80 p-4 rounded-2xl border border-slate-700 space-y-2">
                            <div class="flex items-center justify-between">
                                <span :class="{
                                    'bg-rose-500/20 text-rose-300 border-rose-500/30': category === '重要',
                                    'bg-amber-500/20 text-amber-300 border-amber-500/30': category === 'イベント',
                                    'bg-sky-500/20 text-sky-300 border-sky-500/30': category === '生活',
                                    'bg-emerald-500/20 text-emerald-300 border-emerald-500/30': category === '学習'
                                }" class="px-2 py-0.5 border text-[10px] font-bold rounded-md" x-text="category"></span>

                                <span class="text-[10px] text-slate-400">たった今</span>
                            </div>

                            <h4 class="font-bold text-sm text-slate-100 leading-snug" x-text="title.trim() ? title : '（タイトルがここに表示されます）'"></h4>
                            <p class="text-xs text-slate-300 line-clamp-3 leading-relaxed" x-text="content.trim() ? content : '（本文がここに表示されます）'"></p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <h3 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2">
                            <span>📜</span> 最近の配信履歴
                        </h3>

                        <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
                            <template x-for="item in sentHistory" :key="item.id">
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <span class="px-2 py-0.5 bg-slate-200 text-slate-700 text-[10px] font-bold rounded" x-text="item.category"></span>
                                        <span class="text-[11px] text-slate-400" x-text="item.sent_at"></span>
                                    </div>
                                    <p class="font-bold text-slate-800 leading-snug" x-text="item.title"></p>
                                    <div class="flex items-center justify-between text-[11px] text-slate-400 pt-1">
                                        <span x-text="'対象: ' + item.target"></span>
                                        <template x-if="item.push_sent">
                                            <span class="text-sky-600 font-bold flex items-center gap-0.5">🔔 プッシュ済</span>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- ⑤ サービス使用状況分析の中身 -->
        <div x-show="currentTab === 'analytics'" x-cloak x-data="{ range: 'month', subTab: 'all' }">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">サービス使用状況分析</h2>
                    <p class="text-sm text-slate-500 mt-1">留学生の利用動向やKPIの推移を過去データと比較・分析します。</p>
                </div>
                
                <div class="flex items-center gap-1 bg-slate-200/80 p-1 rounded-xl text-xs font-bold self-start sm:self-auto">
                    <button @click="range = 'week'" 
                            :class="range === 'week' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                            class="px-3 py-1.5 rounded-lg transition">今週（前週比）</button>
                    <button @click="range = 'month'" 
                            :class="range === 'month' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                            class="px-3 py-1.5 rounded-lg transition">今月（前月比）</button>
                    <button @click="range = 'all'" 
                            :class="range === 'all' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                            class="px-3 py-1.5 rounded-lg transition">全期間（推移）</button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between text-slate-400 mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider">DAU (1日アクティブ)</span>
                        <span class="p-2 bg-slate-100 rounded-xl text-base">👥</span>
                    </div>
                    <p class="text-3xl font-extrabold text-slate-800">64 <span class="text-xs font-normal text-slate-500">人</span></p>
                    <div class="mt-3 flex items-center gap-1.5 text-xs font-bold">
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-md">▲ +15%</span>
                        <span class="text-slate-400 font-normal" x-text="range === 'week' ? '前週比' : (range === 'month' ? '前月比' : '昨期比')"></span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between text-slate-400 mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider">MAU (月間アクティブ)</span>
                        <span class="p-2 bg-sky-50 rounded-xl text-base">🗓️</span>
                    </div>
                    <p class="text-3xl font-extrabold text-slate-800">112 <span class="text-xs font-normal text-slate-500">人</span></p>
                    <div class="mt-3 flex items-center gap-1.5 text-xs font-bold">
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-md">▲ +8%</span>
                        <span class="text-slate-400 font-normal" x-text="range === 'week' ? '前週比' : (range === 'month' ? '前月比' : '昨期比')"></span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between text-slate-400 mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider">シャワー情報更新</span>
                        <span class="p-2 bg-sky-50 rounded-xl text-base">🚿</span>
                    </div>
                    <p class="text-3xl font-extrabold text-slate-800">342 <span class="text-xs font-normal text-slate-500">回</span></p>
                    <div class="mt-3 flex items-center gap-1.5 text-xs font-bold">
                        <span class="px-2 py-0.5 bg-rose-100 text-rose-700 rounded-md">▼ -5%</span>
                        <span class="text-slate-400 font-normal" x-text="range === 'week' ? '前週比' : (range === 'month' ? '前月比' : '昨期比')"></span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between text-slate-400 mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider">学習コンテンツ完了</span>
                        <span class="p-2 bg-amber-50 rounded-xl text-base">📖</span>
                    </div>
                    <p class="text-3xl font-extrabold text-slate-800">1,240 <span class="text-xs font-normal text-slate-500">回</span></p>
                    <div class="mt-3 flex items-center gap-1.5 text-xs font-bold">
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-md">▲ +24%</span>
                        <span class="text-slate-400 font-normal" x-text="range === 'week' ? '前週比' : (range === 'month' ? '前月比' : '昨期比')"></span>
                    </div>
                </div>

            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="font-bold text-slate-800 mb-4">アクティブユーザー推移グラフ</h3>
                <div class="h-64 bg-slate-50 rounded-xl border border-dashed border-slate-300 flex items-center justify-center text-slate-400 text-xs font-bold">
                    📊 チャート表示エリア（Chart.js / ApexCharts 連動可能）
                </div>
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