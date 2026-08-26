@extends('layouts.app')

@section('content')
<!-- x-data で「今どのタブ（メニュー）を開いているか」を管理します (初期値: 'dashboard') -->
@php
    $categoryFormHasError = $errors->addMain->any() || $errors->addSub->any() || $errors->editMain->any() || $errors->editSub->any();
    $categoryFormMode = $errors->addSub->any() ? 'addSub' : ($errors->editMain->any() ? 'editMain' : ($errors->editSub->any() ? 'editSub' : 'addMain'));
@endphp
<div x-data="{ currentTab: '{{ session('accountCreated') || $errors->default->any() ? 'accounts' : ($categoryFormHasError || session('categoryAdminNotice') ? 'posts' : 'dashboard') }}' }" class="flex min-h-screen bg-slate-100">

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

            <!-- 留学情報管理(旧ポスト管理。myu担当) -->
            <button @click="currentTab = 'posts'"
                    :class="currentTab === 'posts' ? 'bg-brand-blue text-white shadow' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-left">
                <span>🗂️</span> 留学情報管理
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
                                    <template x-if="user.avatar_url">
                                        <img :src="user.avatar_url" :alt="user.name"
                                             class="w-9 h-9 rounded-full object-cover shrink-0">
                                    </template>
                                    <template x-if="!user.avatar_url">
                                        <div class="w-9 h-9 rounded-full bg-brand-blue text-white font-bold flex items-center justify-center text-xs shrink-0"
                                             x-text="user.name ? user.name.charAt(0).toUpperCase() : '?'"></div>
                                    </template>
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

        <!-- ④ 留学情報管理の中身(myu担当。メイン/サブカテゴリーの追加・編集) -->
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
            mainCategories: {{ Illuminate\Support\Js::from($adminMainCategories) }},
            categories: {{ Illuminate\Support\Js::from($adminCategories) }},
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
                <div class="max-w-2xl mb-6 p-4 rounded-xl text-xs font-bold
                            {{ $noticeIsError ? 'bg-rose-50 border border-rose-200 text-rose-700' : 'bg-emerald-50 border border-emerald-200 text-emerald-800' }}">
                    {{ $noticeIsError ? '⚠️' : '✅' }} {{ session('categoryAdminNotice')['message'] }}
                </div>
            @endif

            <!-- モード切り替えボタン -->
            <div class="flex flex-wrap gap-2 mb-6">
                <button type="button" @click="mode = 'addMain'"
                        :class="mode === 'addMain' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition">➕ 新規メインカテゴリー</button>
                <button type="button" @click="mode = 'addSub'"
                        :class="mode === 'addSub' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition">➕ 新規サブカテゴリー</button>
                <button type="button" @click="mode = 'editMain'"
                        :class="mode === 'editMain' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition">✏️ メインカテゴリー編集</button>
                <button type="button" @click="mode = 'editSub'"
                        :class="mode === 'editSub' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition">✏️ サブカテゴリー編集</button>
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
                        <input type="text" name="key" value="{{ old('key') }}" required placeholder="例: souvenir-shop（半角英数字とハイフンのみ）"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                        <p class="text-[11px] text-slate-400 mt-1">URLや内部処理で使う識別子です。後から変更できません。</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">名前 <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ヒーロー画像</label>
                        <input type="file" name="hero_image" accept="image/*"
                               class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">説明文</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">{{ old('description') }}</textarea>
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
                        <select name="section" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                            <option value="">選択してください</option>
                            @foreach ($adminMainCategories as $mc)
                                <option value="{{ $mc->key }}" {{ old('section') === $mc->key ? 'selected' : '' }}>{{ $mc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">名前 <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ヒーロー画像</label>
                        <input type="file" name="hero_image" accept="image/*"
                               class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">説明文</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">{{ old('description') }}</textarea>
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
                    <select @change="loadMain($event.target.value)"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                        <option value="">選択してください</option>
                        <template x-for="mc in mainCategories" :key="mc.id">
                            <option :value="mc.id" x-text="mc.name"></option>
                        </template>
                    </select>
                </div>

                <form method="POST" x-show="editMain.id" x-cloak
                      :action="editMain.id ? '{{ url('admin/main-categories') }}/' + editMain.id : ''"
                      enctype="multipart/form-data" class="space-y-4 pt-2 border-t border-slate-100">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">識別キー(key)</label>
                        <input type="text" :value="editMain.key" disabled
                               class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-medium text-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">名前 <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="editMain.name" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">現在のヒーロー画像</label>
                        <img :src="editMain.hero_image" x-show="editMain.hero_image" class="w-full h-32 object-cover rounded-xl border border-slate-200 mb-2">
                        <input type="file" name="hero_image" accept="image/*"
                               class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                        <p class="text-[11px] text-slate-400 mt-1">新しい画像を選んだ時だけ差し替えられます。</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">説明文</label>
                        <textarea name="description" x-model="editMain.description" rows="3"
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition"></textarea>
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

                <form method="POST" x-show="editMain.id" x-cloak
                      :action="editMain.id ? '{{ url('admin/main-categories') }}/' + editMain.id : ''"
                      class="space-y-2 pt-1"
                      onsubmit="return this.force.value === '1'
                          ? (confirm('サブカテゴリーごと完全に削除されます。よろしいですか？') && confirm('最終確認です。この操作は取り消せません。本当に削除しますか？'))
                          : confirm('本当にこのメインカテゴリーを削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="force" :value="forceDeleteMain ? '1' : '0'">
                    <p x-show="editMain.sub_count > 0" x-cloak class="text-[11px] text-rose-500 font-bold">
                        ⚠️ このメインカテゴリーには現在 <span x-text="editMain.sub_count"></span> 件のサブカテゴリーがあります。
                    </p>
                    <label x-show="editMain.sub_count > 0" x-cloak class="flex items-center gap-2 text-[11px] font-bold text-rose-600">
                        <input type="checkbox" x-model="forceDeleteMain" class="rounded border-rose-300">
                        中身(サブカテゴリー・投稿)ごと完全に削除する
                    </label>
                    <button type="submit"
                            :disabled="editMain.sub_count > 0 && !forceDeleteMain"
                            :class="(editMain.sub_count > 0 && !forceDeleteMain) ? 'opacity-40 cursor-not-allowed' : 'hover:bg-rose-50'"
                            class="w-full py-2.5 px-6 rounded-xl text-xs font-bold transition bg-white border border-rose-200 text-rose-600">
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
                    <select x-model="editSubSection"
                            @change="editCategory = { id: '', section: '', name: '', description: '', hero_image: '', post_count: 0 }; forceDeleteSub = false;"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                        <option value="">選択してください</option>
                        <template x-for="mc in mainCategories" :key="mc.id">
                            <option :value="mc.key" x-text="mc.name"></option>
                        </template>
                    </select>
                </div>
                <div x-show="editSubSection" x-cloak>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">② 編集するサブカテゴリーを選択</label>
                    <select @change="loadCategory($event.target.value)"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                        <option value="">選択してください</option>
                        <template x-for="c in categories.filter(c => c.section === editSubSection)" :key="c.id">
                            <option :value="c.id" x-text="c.name"></option>
                        </template>
                    </select>
                </div>

                <form method="POST" x-show="editCategory.id" x-cloak
                      :action="editCategory.id ? '{{ url('admin/categories') }}/' + editCategory.id : ''"
                      enctype="multipart/form-data" class="space-y-4 pt-2 border-t border-slate-100">
                    @csrf
                    @method('PATCH')
                    {{-- 所属メインカテゴリーは上の①②で選択済みなので、ここでは表示せずそのまま送信するだけ --}}
                    <input type="hidden" name="section" x-model="editCategory.section">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">名前 <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="editCategory.name" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">現在のヒーロー画像</label>
                        <img :src="editCategory.hero_image" x-show="editCategory.hero_image" class="w-full h-32 object-cover rounded-xl border border-slate-200 mb-2">
                        <input type="file" name="hero_image" accept="image/*"
                               class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                        <p class="text-[11px] text-slate-400 mt-1">新しい画像を選んだ時だけ差し替えられます。</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">説明文</label>
                        <textarea name="description" x-model="editCategory.description" rows="3"
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3 px-6 rounded-xl text-xs font-bold transition bg-slate-900 hover:bg-slate-800 text-white shadow-md">
                        サブカテゴリーを更新する
                    </button>
                </form>

                <form method="POST" x-show="editCategory.id" x-cloak
                      :action="editCategory.id ? '{{ url('admin/categories') }}/' + editCategory.id : ''"
                      class="space-y-2 pt-1"
                      onsubmit="return this.force.value === '1'
                          ? (confirm('投稿ごと完全に削除されます。よろしいですか？') && confirm('最終確認です。この操作は取り消せません。本当に削除しますか？'))
                          : confirm('本当にこのサブカテゴリーを削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="force" :value="forceDeleteSub ? '1' : '0'">
                    <p x-show="editCategory.post_count > 0" x-cloak class="text-[11px] text-rose-500 font-bold">
                        ⚠️ このサブカテゴリーには現在 <span x-text="editCategory.post_count"></span> 件の投稿があります。
                    </p>
                    <label x-show="editCategory.post_count > 0" x-cloak class="flex items-center gap-2 text-[11px] font-bold text-rose-600">
                        <input type="checkbox" x-model="forceDeleteSub" class="rounded border-rose-300">
                        中身(投稿)ごと完全に削除する
                    </label>
                    <button type="submit"
                            :disabled="editCategory.post_count > 0 && !forceDeleteSub"
                            :class="(editCategory.post_count > 0 && !forceDeleteSub) ? 'opacity-40 cursor-not-allowed' : 'hover:bg-rose-50'"
                            class="w-full py-2.5 px-6 rounded-xl text-xs font-bold transition bg-white border border-rose-200 text-rose-600">
                        🗑️ このサブカテゴリーを削除する
                    </button>
                </form>
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