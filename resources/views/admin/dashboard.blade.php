@extends('layouts.app')

@section('content')
<!-- x-data で「今どのタブ（メニュー）を開いているか」を管理 (初期値: 'dashboard') -->
<div x-data="{ currentTab: '{{ session('accountCreated') || $errors->any() ? 'accounts' : 'dashboard' }}' }" class="flex min-h-screen bg-slate-100">

    <!-- 1. 左側：サイドバー -->
    <aside class="w-64 bg-slate-900 text-white p-6 shrink-0 hidden md:block">
        <h1 class="text-xl font-bold mb-8 text-sky-400">システム管理</h1>
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

    <!-- 2. 右側：メインコンテンツエリア -->
    <main class="flex-1 p-8">

     <!-- ① ダッシュボードの中身 -->
<div x-show="currentTab === 'dashboard'">
    <!-- ヘッダーエリア -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">ダッシュボード</h2>
            <p class="text-sm text-slate-500 mt-1">本日の各セクション稼働状況とアクティビティ概要です。</p>
        </div>
        <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full border border-emerald-200/80 shadow-sm self-start sm:self-auto">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            システム正常稼働中
        </div>
    </div>

    <!-- サマリーテーブル -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 border-collapse">
                <thead class="bg-slate-50/80 text-xs font-bold uppercase text-slate-400 border-b border-slate-200/80">
                    <tr>
                        <th scope="col" class="py-4 px-6">項目</th>
                        <th scope="col" class="py-4 px-6">本日の数値</th>
                        <th scope="col" class="py-4 px-6">前日比</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <!-- 本日のアクティブユーザー数 (サイト閲覧アカウント数) -->
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-base shadow-xs">👥</span>
                                <div>
                                    <span class="font-bold text-slate-800 block">アクティブユーザー数</span>
                                    <span class="text-xs text-slate-400 font-normal">本日のアクティブアカウント</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-2xl font-black text-slate-800 tracking-tight">{{ number_format($todayActiveUsersCount ?? 0) }}</span>
                            <span class="text-xs text-slate-500 font-normal ml-0.5">名</span>
                        </td>
                        <td class="py-4 px-6">
                            @php $diff = $activeUsersDiff ?? 0; @endphp
                            @if($diff > 0)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100">
                                    ▲ +{{ $diff }}名
                                </span>
                            @elseif($diff < 0)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-md border border-rose-100">
                                    ▼ {{ $diff }}名
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md">
                                    ±0名
                                </span>
                            @endif
                        </td>
                    </tr>

                    <!-- 留学情報 投稿数 -->
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-xl bg-sky-50 border border-sky-100 text-sky-600 flex items-center justify-center text-base shadow-xs">💬</span>
                                <span class="font-bold text-slate-800">「留学情報」投稿数</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-2xl font-black text-slate-800 tracking-tight">{{ number_format($todayInfoUpdates ?? 0) }}</span>
                            <span class="text-xs text-slate-500 font-normal ml-0.5">件</span>
                        </td>
                        <td class="py-4 px-6">
                            @php $diff = $infoUpdatesDiff ?? 0; @endphp
                            @if($diff > 0)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100">
                                    ▲ +{{ $diff }}件
                                </span>
                            @elseif($diff < 0)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-md border border-rose-100">
                                    ▼ {{ $diff }}件
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md">
                                    ±0件
                                </span>
                            @endif
                        </td>
                    </tr>

                    <!-- 英語学習アクティブ数 -->
<tr class="hover:bg-slate-50/60 transition-colors">
    <td class="py-4 px-6">
        <div class="flex items-center gap-3">
            <span class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-base shadow-xs">📖</span>
            <div>
                <span class="font-bold text-slate-800 block">「英語学習」アクティブ数</span>
                </div>
        </div>
    </td>
    <td class="py-4 px-6">
        <span class="text-2xl font-black text-slate-800 tracking-tight">{{ number_format($todayStudyActiveUsersCount ?? 0) }}</span>
        <span class="text-xs text-slate-500 font-normal ml-0.5">名</span>
    </td>
    <td class="py-4 px-6">
        @php $diff = $studyActiveUsersDiff ?? 0; @endphp
        @if($diff > 0)
            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100">
                ▲ +{{ $diff }}名
            </span>
        @elseif($diff < 0)
            <span class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-md border border-rose-100">
                ▼ {{ $diff }}名
            </span>
        @else
            <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md">
                ±0名
            </span>
        @endif
    </td>
</tr>

                    <!-- シャワーレポート/レビュー数 -->
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-base shadow-xs">🚿</span>
                                <span class="font-bold text-slate-800">「シャワー環境」投稿数</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-2xl font-black text-slate-800 tracking-tight">{{ number_format($todayShowerUpdates ?? 0) }}</span>
                            <span class="text-xs text-slate-500 font-normal ml-0.5">件</span>
                        </td>
                        <td class="py-4 px-6">
                            @php $diff = $showerUpdatesDiff ?? 0; @endphp
                            @if($diff > 0)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100">
                                    ▲ +{{ $diff }}件
                                </span>
                            @elseif($diff < 0)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-md border border-rose-100">
                                    ▼ {{ $diff }}件
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md">
                                    ±0件
                                </span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
       <!-- ② ユーザー管理の中身 -->
<div x-show="currentTab === 'users'" x-cloak x-data="{ 
    searchQuery: '',
    users: {{ \Illuminate\Support\Js::from($users) }}
}">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">ユーザー管理</h2>
            <p class="text-sm text-slate-500 mt-1">登録留学生の利用状況やアクティビティを一元管理します。</p>
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
                        <th class="py-3.5 px-4">アカウント作成日</th>
                        <th class="py-3.5 px-4">最終アクティブ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    <template x-for="(user, index) in users" :key="user.id || index">
                        <tr class="hover:bg-slate-50/80 transition" 
                            x-show="!searchQuery.trim() || 
                                    (user.name && user.name.toLowerCase().includes(searchQuery.toLowerCase())) || 
                                    (user.email && user.email.toLowerCase().includes(searchQuery.toLowerCase()))">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-800 text-white font-bold flex items-center justify-center text-xs shrink-0"
                                         x-text="user.name ? user.name.charAt(0) : '?'"></div>
                                    <div>
                                        <p class="font-bold text-slate-800 leading-tight" x-text="user.name || '名前未設定'"></p>
                                        <p class="text-xs text-slate-400 mt-0.5" x-text="user.email || ''"></p>
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-4">
                                <p class="text-xs font-semibold text-slate-700" x-text="user.dorm || '未設定'"></p>
                                <p class="text-[11px] text-slate-400" x-text="user.course || '-'"></p>
                            </td>

                            <td class="py-4 px-4 text-xs text-slate-600 font-semibold" 
                                x-text="user.registered_at || '-'"></td>

                            <td class="py-4 px-4 text-xs text-slate-500" 
                                x-text="user.last_active || '-'"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
        <!-- ③ アカウント作成の中身 -->
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

        <!-- ④ ポスト管理の中身 -->
        <div x-show="currentTab === 'posts'" x-cloak x-data="{ 
            selectedPost: null,
            showOnlyReported: false,
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
                return this.posts.filter(post => !this.showOnlyReported || post.reported_count > 0);
            }
        }">
            <!-- ヘッダー＆フィルター領域 -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">ポスト管理</h2>
                    <p class="text-sm text-slate-500 mt-1">投稿された口コミやコミュニティ記事の確認・通報対応・表示制御を行います。</p>
                </div>

                <div class="flex items-center">
                    <!-- 通報ありのみチェックボックス -->
                    <label class="inline-flex items-center gap-2 cursor-pointer bg-white px-3.5 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 select-none hover:bg-slate-50 transition shadow-sm whitespace-nowrap shrink-0">
                        <input type="checkbox" x-model="showOnlyReported" class="w-4 h-4 rounded border-slate-300 text-rose-600 focus:ring-rose-200 accent-rose-600 cursor-pointer shrink-0">
                        <span class="whitespace-nowrap">⚠️ 通報</span>
                    </label>
                </div>
            </div>

            <!-- 投稿カード一覧 -->
            <div class="space-y-4 mb-8">
                <template x-for="post in filteredPosts" :key="post.id">
                    <div class="bg-white rounded-2xl p-5 border transition-all duration-200 shadow-sm hover:shadow-md"
                         :class="{
                             'border-rose-300 bg-rose-50/30': post.reported_count > 0,
                             'border-slate-200': post.reported_count === 0,
                             'opacity-60 bg-slate-50': post.status === 'hidden'
                         }">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-900 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-sm"
                                     x-text="post.user_name.charAt(0)"></div>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-bold text-slate-800 text-sm" x-text="post.user_name"></span>
                                        <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded-md text-[10px] font-bold text-slate-600" x-text="post.category"></span>
                                        <template x-if="post.status === 'hidden'">
                                            <span class="px-2 py-0.5 bg-slate-200 text-slate-700 rounded-md text-[10px] font-extrabold flex items-center gap-1">🔒 非表示中</span>
                                        </template>
                                    </div>
                                    <p class="text-[11px] text-slate-400 mt-0.5" x-text="post.created_at"></p>
                                </div>
                            </div>

                            <template x-if="post.reported_count > 0">
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-bold border border-rose-200 shrink-0 self-start sm:self-auto">
                                    <span>⚠️ 通報</span>
                                    <span x-text="post.reported_count + '件'"></span>
                                </div>
                            </template>
                        </div>

                        <div class="mb-4">
                            <h3 class="font-bold text-slate-800 text-base mb-1 hover:text-brand-blue transition cursor-pointer" 
                                @click="selectedPost = post; adminNotice = ''; noticeSent = false;" x-text="post.title"></h3>
                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed" x-text="post.content"></p>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                            <div class="flex items-center gap-4 text-slate-400 font-medium">
                                <span class="flex items-center gap-1 text-amber-600 font-bold bg-amber-50 px-2 py-1 rounded-md">
                                    <span>♡</span> <span x-text="post.likes"></span>
                                </span>
                                <span class="flex items-center gap-1 hover:text-slate-600 transition">
                                    <span>💬</span> <span x-text="post.comments_count + ' コメント'"></span>
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <button @click="post.status = (post.status === 'hidden' ? 'published' : 'hidden')"
                                        :class="post.status === 'hidden' ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                        class="px-3 py-1.5 rounded-xl font-bold transition">
                                    <span x-text="post.status === 'hidden' ? '再公開する' : '非表示'"></span>
                                </button>

                                <button @click="selectedPost = post; adminNotice = ''; noticeSent = false;"
                                        class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition shadow-sm">
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
                        <p class="text-xs text-slate-400 mt-1">フィルターの選択を変更してみてください。</p>
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
                     class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 space-y-5">
                    
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <span class="px-2.5 py-1 bg-brand-blue/10 text-brand-blue font-bold text-xs rounded-lg" x-text="selectedPost?.category"></span>
                            <span class="text-xs text-slate-400" x-text="selectedPost?.created_at"></span>
                        </div>
                        <button @click="selectedPost = null" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold flex items-center justify-center transition">✕</button>
                    </div>

                    <template x-if="selectedPost?.reported_count > 0">
                        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl space-y-1.5">
                            <div class="flex items-center justify-between text-rose-700 font-bold text-xs">
                                <span class="flex items-center gap-1">⚠️ 通報理由リスト</span>
                                <span class="bg-rose-200/60 px-2 py-0.5 rounded-full" x-text="selectedPost?.reported_count + '件の通報'"></span>
                            </div>
                            <ul class="list-disc list-inside text-xs text-rose-800 space-y-1 pt-1">
                                <template x-for="reason in selectedPost?.report_reasons" :key="reason">
                                    <li x-text="reason"></li>
                                </template>
                            </ul>
                        </div>
                    </template>

                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-slate-800 leading-snug" x-text="selectedPost?.title"></h3>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line" x-text="selectedPost?.content"></p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl text-xs border border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-slate-900 text-white font-bold flex items-center justify-center text-[11px]"
                                 x-text="selectedPost?.user_name.charAt(0)"></div>
                            <div>
                                <span class="font-bold text-slate-800" x-text="selectedPost?.user_name"></span>
                                <span class="text-slate-400 ml-1" x-text="'(' + selectedPost?.user_email + ')'"></span>
                            </div>
                        </div>
                        <span class="text-slate-400 font-medium font-mono" x-text="'ID: #' + selectedPost?.id"></span>
                    </div>

                    <!-- 通知メッセージ入力域 -->
                    <div class="space-y-2 pt-2 border-t border-slate-100">
                        <h4 class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                            <span>📩</span> 投稿者へ通知メッセージを送信
                        </h4>
                        
                        <div class="space-y-2">
                            <textarea x-model="adminNotice" rows="2" 
                                      :placeholder="selectedPost?.user_name + ' さんへの修正連絡や非表示理由を入力...'"
                                      class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition resize-none"></textarea>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <template x-if="noticeSent">
                                        <span class="text-xs font-bold text-emerald-600 flex items-center gap-1 animate-pulse">
                                            <span>送信完了しました！</span>
                                        </span>
                                    </template>
                                </div>
                                <button @click="sendPostNotice()" 
                                        :disabled="!adminNotice.trim()"
                                        :class="adminNotice.trim() ? 'bg-brand-blue hover:bg-sky-600 text-white shadow-sm' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                                        class="px-4 py-1.5 text-xs font-bold rounded-lg transition">
                                    通知送信
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- モーダル下部操作ボタン -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <button @click="selectedPost.status = (selectedPost.status === 'hidden' ? 'published' : 'hidden')"
                                :class="selectedPost?.status === 'hidden' ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-rose-100 text-rose-700 hover:bg-rose-200'"
                                class="px-4 py-2 text-xs font-bold rounded-xl transition">
                            <span x-text="selectedPost?.status === 'hidden' ? '🔒 再公開する' : '🚫 非表示にする'"></span>
                        </button>

                        <button @click="selectedPost = null" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition">
                            閉じる
                        </button>
                    </div>

                </div>
            </div>
        </div>

      <!-- ⑤ お知らせ送信の中身 -->
<div x-show="currentTab === 'notice'" x-cloak x-data="noticeAdmin()">
    <!-- ヘッダー＆新規作成ボタン -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">お知らせ管理</h2>
            <p class="text-sm text-slate-500 mt-1">留学生のアプリ内通知へ一斉配信した履歴の確認や新規送信を行います。</p>
        </div>
        <button @click="isModalOpen = true; sentSuccess = false; errorMessage = '';" 
                class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition shadow-sm flex items-center justify-center gap-2 shrink-0 self-start sm:self-auto">
            <span>✏️</span> お知らせを新規作成
        </button>
    </div>

    <!-- メイン表示：最近の配信履歴 -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
        <h3 class="font-bold text-slate-800 text-base flex items-center gap-2 border-b border-slate-100 pb-4">
            <span>📜</span> 最近の配信履歴
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
    <div x-show="isModalOpen" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.outside="if(!isSending) isModalOpen = false" 
             class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-xl max-h-[90vh] overflow-y-auto p-6 space-y-5">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                    <span>✏️</span> お知らせの新規作成
                </h3>
                <button @click="isModalOpen = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold flex items-center justify-center transition">✕</button>
            </div>

            <!-- 成功時のアラート -->
            <template x-if="sentSuccess">
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-800 text-xs font-bold animate-pulse">
                    <span class="text-lg">🎉</span>
                    <span>お知らせの配信が完了しました！</span>
                </div>
            </template>

            <!-- 失敗時のエラーアラート（詳細を画面表示） -->
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
                    <input type="text" x-model="title" placeholder="例: 妖精さんについての重要なお知らせ"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-slate-900/20 focus:border-slate-800 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">配信カテゴリ</label>
                    <select x-model="category" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900/20">
                        <option value="重要">🚨 重要なお知らせ</option>
                        <option value="イベント">🎉 イベント・交流会</option>
                        <option value="生活">🏠 寮・生活情報</option>
                        <option value="学習">📖 英語学習</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">お知らせ本文 <span class="text-rose-500">*</span></label>
                    <textarea x-model="content" rows="6" placeholder="配信したい内容の詳細を入力してください..."
                              class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-slate-900/20 focus:border-slate-800 transition resize-none"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-3">
                    <button @click="isModalOpen = false" 
                            type="button" 
                            class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">
                        キャンセル
                    </button>
                    <button @click="sendNotice()" 
                            :disabled="!title.trim() || !content.trim() || isSending"
                            :class="title.trim() && content.trim() && !isSending ? 'bg-slate-900 hover:bg-slate-800 text-white shadow-md' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
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

<!-- Alpine.js コンポーネント用のJavaScript定義 -->
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
        sentHistory: @json($notices),

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

                // 履歴の先頭に追加
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

        <!-- ⑥ サービス使用状況分析の中身 -->
<div x-show="currentTab === 'analytics'" x-cloak class="space-y-10">
    
    <div>
        <h2 class="text-2xl font-bold text-slate-800">サービス使用状況分析</h2>
        <p class="text-sm text-slate-500 mt-1">週次・月次・年次の各期間におけるアクティブ率、英語学習機能の使用率、投稿数などの主要指標を確認します。</p>
    </div>

    <!-- ① 週次データ表示 -->
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> 週間サマリー (過去7日間)
            </h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- WAU -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                    <span>週間アクティブ率 (WAU)</span>
                    @if($wauDiff >= 0)
                        <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">+{{ $wauDiff }}%</span>
                    @else
                        <span class="text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">{{ $wauDiff }}%</span>
                    @endif
                </div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $wauRate }} %</p>
                <p class="text-[11px] text-slate-400">今週アクティブなユーザー: {{ number_format($weeklyActiveCount) }} 名</p>
            </div>

            <!-- 英語学習機能使用率 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2 border-l-4 border-l-emerald-500">
                <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                    <span>英語学習機能の週間使用率</span>
                    <span class="text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">WAU比</span>
                </div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $weeklyEnglishRate }} %</p>
                <p class="text-[11px] text-slate-400">WAUのうち {{ number_format($weeklyEnglishUsers) }} 名が今週利用</p>
            </div>

            <!-- 今週の投稿数 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                    <span>今週の投稿数</span>
                    @if($weeklyPostsDiff >= 0)
                        <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">+{{ $weeklyPostsDiff }}件</span>
                    @else
                        <span class="text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">{{ $weeklyPostsDiff }}件</span>
                    @endif
                </div>
                <p class="text-2xl font-extrabold text-slate-800">{{ number_format($weeklyPostsCount) }} 件</p>
                <p class="text-[11px] text-slate-400">前週比 {{ $weeklyPostsDiff >= 0 ? '+'.$weeklyPostsDiff : $weeklyPostsDiff }} 件</p>
            </div>

            <!-- 平均シャワーレビュー数 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                    <span>平均シャワーレビュー数</span>
                    <span class="text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">1人あたり</span>
                </div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $weeklyAvgShowerReviews }} 件</p>
                <p class="text-[11px] text-slate-400">今週の総レビュー数: {{ number_format($weeklyShowerCount) }} 件</p>
            </div>
        </div>
    </div>

    <!-- ② 月間データ表示 -->
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> 月間サマリー (過去30日間)
            </h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- MAU -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                    <span>月間アクティブ率 (MAU)</span>
                    <span class="text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">直近30日</span>
                </div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $mauRate }} %</p>
                <p class="text-[11px] text-slate-400">登録者 {{ number_format($totalUsersCount) }} 名中 {{ number_format($monthlyActiveCount) }} 名が利用</p>
            </div>

            <!-- 英語学習機能の月間使用率 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2 border-l-4 border-l-emerald-500">
                <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                    <span>英語学習機能の月間使用率</span>
                    <span class="text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">MAU比</span>
                </div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $monthlyEnglishRate }} %</p>
                <p class="text-[11px] text-slate-400">月間アクティブユーザーの {{ number_format($monthlyEnglishUsers) }} 名が活用</p>
            </div>

            <!-- 今月の月間投稿数 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                    <span>今月の月間投稿数</span>
                    @if($monthlyPostsDiff >= 0)
                        <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">+{{ $monthlyPostsDiff }}件</span>
                    @else
                        <span class="text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">{{ $monthlyPostsDiff }}件</span>
                    @endif
                </div>
                <p class="text-2xl font-extrabold text-slate-800">{{ number_format($monthlyPostsCount) }} 件</p>
                <p class="text-[11px] text-slate-400">口コミ・質問投稿の合計</p>
            </div>

            <!-- 平均シャワーレビュー数 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                    <span>平均シャワーレビュー数</span>
                    <span class="text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">1人あたり</span>
                </div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $monthlyAvgShowerReviews }} 件</p>
                <p class="text-[11px] text-slate-400">今月の総レビュー数: {{ number_format($monthlyShowerCount) }} 件</p>
            </div>
        </div>
    </div>

    <!-- ③ 年次データ表示 -->
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> 年次サマリー (過去12ヶ月)
            </h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 年間定着率 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                    <span>年間定着率 (Retention)</span>
                    <span class="text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">過去1年</span>
                </div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $retentionRate }} %</p>
                <p class="text-[11px] text-slate-400">年間累計アクティブ: {{ number_format($yearlyActiveCount) }} 名</p>
            </div>

            <!-- 英語学習機能の年間平均使用率 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2 border-l-4 border-l-emerald-500">
                <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                    <span>英語学習機能の年間平均使用率</span>
                    <span class="text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">過去1年</span>
                </div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $yearlyEnglishRate }} %</p>
                <p class="text-[11px] text-slate-400">年間利用者数: {{ number_format($yearlyEnglishUsers) }} 名</p>
            </div>

            <!-- 年間累計投稿数 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                    <span>年間累計投稿数</span>
                    <span class="text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">過去1年</span>
                </div>
                <p class="text-2xl font-extrabold text-slate-800">{{ number_format($yearlyPostsCount) }} 件</p>
                <p class="text-[11px] text-slate-400">月平均 {{ number_format(round($yearlyPostsCount / 12, 1)) }} 件</p>
            </div>

            <!-- 平均シャワーレビュー数 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                    <span>平均シャワーレビュー数</span>
                    <span class="text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">1人あたり</span>
                </div>
                <p class="text-2xl font-extrabold text-slate-800">{{ $yearlyAvgShowerReviews }} 件</p>
                <p class="text-[11px] text-slate-400">年間総レビュー数: {{ number_format($yearlyShowerCount) }} 件</p>
            </div>
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
                statuses: {{ Js::from(\App\Models\Suggestion::STATUSES) }},
                editingNote: {},

                get filteredItems() {
                    if (this.statusFilter === 'all') return this.items;
                    return this.items.filter(item => item.status === this.statusFilter);
                },

                async load() {
                    try {
                        const response = await fetch('{{ route('admin.suggestions.data') }}');
                        const data = await response.json();
                        this.items = data.items;
                        data.items.forEach(item => { this.editingNote[item.id] = item.admin_note ?? ''; });
                    } catch (e) {
                        console.error('Failed to load suggestions:', e);
                    }
                },

                async updateStatus(item, newStatus) {
                    try {
                        const response = await fetch(`/admin/suggestions/${item.id}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ status: newStatus, admin_note: this.editingNote[item.id] }),
                        });

                        if (response.ok) {
                            item.status = newStatus;
                            item.status_label = this.statuses[newStatus];
                        }
                    } catch (e) {
                        console.error('Failed to update status:', e);
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