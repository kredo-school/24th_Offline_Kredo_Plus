<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Kredo Plus'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;800&family=Poppins:wght@600;700;800&family=Caveat:wght@600;700&family=IBM+Plex+Mono:wght@500;600&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('css/english.css') }}">

    @stack('styles')

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/english/app.js', 'resources/js/app.js'])
</head>

<body class="font-sans bg-white text-slate-800 antialiased min-h-screen flex flex-col" x-data="{ mobileOpen: false }">

    @hasSection('intro')
        <!-- Opening -->
        <div id="intro" aria-hidden="true">
            <svg width="120" height="100" viewBox="0 0 48 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="iRibbonBlue" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#4f7df0" />
                        <stop offset="100%" stop-color="#2b52c7" />
                    </linearGradient>
                    <linearGradient id="iRibbonRed" x1="1" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f0654a" />
                        <stop offset="100%" stop-color="#d94427" />
                    </linearGradient>
                    <linearGradient id="iRibbonGreen" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#8fce54" />
                        <stop offset="100%" stop-color="#5eab35" />
                    </linearGradient>
                </defs>
                <path class="intro-ribbon intro-ribbon-blue"
                    d="M9 3 C13 2, 16 2.5, 17.5 4 L13.5 37 C9.5 36.5, 7 35, 5.5 33.5 Z" fill="url(#iRibbonBlue)" />
                <path class="intro-ribbon intro-ribbon-red"
                    d="M13 22 L31 3 C34 2, 36.5 3.5, 37.5 6.5 L16.5 25.5 C14.5 24.8, 13.4 23.6, 13 22 Z"
                    fill="url(#iRibbonRed)" />
                <path class="intro-ribbon intro-ribbon-green"
                    d="M13.5 23.5 L32 32.5 C32.5 35.5, 31 37.8, 28.5 38.5 L12 27 C12.2 25.5, 12.7 24.4, 13.5 23.5 Z"
                    fill="url(#iRibbonGreen)" />
                <path class="intro-plus" d="M40.5 6.5h3.5v4h4v3.5h-4v4h-3.5v-4h-4V10.5h4z" fill="#f5b52e" />
            </svg>
            <div class="intro-word font-display font-extrabold text-4xl sm:text-5xl tracking-tight">
                <span class="w1 wordmark-kredo">Kredo</span> <span class="w2 wordmark-plus">Plus</span>
            </div>
            <p class="intro-tag text-sm text-slate-500 font-medium tracking-wide">IT × 英語で人生の選択肢を広げる</p>
        </div>
    @endif

    <!-- Header -->
    <header class="w-full bg-white sticky top-0 z-[1050] shadow-[0_1px_0_rgba(15,23,42,0.06)]">
        <div class="max-w-[1140px] mx-auto flex items-center justify-between px-6 py-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                <svg width="40" height="34" viewBox="0 0 48 40" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="transition-transform duration-300 group-hover:rotate-[-4deg]">
                    <defs>
                        <linearGradient id="ribbonBlue" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#4f7df0" />
                            <stop offset="100%" stop-color="#2b52c7" />
                        </linearGradient>
                        <linearGradient id="ribbonRed" x1="1" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#f0654a" />
                            <stop offset="100%" stop-color="#d94427" />
                        </linearGradient>
                        <linearGradient id="ribbonGreen" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#8fce54" />
                            <stop offset="100%" stop-color="#5eab35" />
                        </linearGradient>
                    </defs>
                    <path d="M9 3 C13 2, 16 2.5, 17.5 4 L13.5 37 C9.5 36.5, 7 35, 5.5 33.5 Z"
                        fill="url(#ribbonBlue)" />
                    <path d="M13 22 L31 3 C34 2, 36.5 3.5, 37.5 6.5 L16.5 25.5 C14.5 24.8, 13.4 23.6, 13 22 Z"
                        fill="url(#ribbonRed)" />
                    <path
                        d="M13.5 23.5 L32 32.5 C32.5 35.5, 31 37.8, 28.5 38.5 L12 27 C12.2 25.5, 12.7 24.4, 13.5 23.5 Z"
                        fill="url(#ribbonGreen)" />
                    <path d="M40.5 6.5h3.5v4h4v3.5h-4v4h-3.5v-4h-4V10.5h4z" fill="#f5b52e" />
                </svg>
                <span class="font-display font-extrabold text-2xl tracking-tight">
                    <span class="wordmark-kredo">Kredo</span> <span class="wordmark-plus">Plus</span>
                </span>
            </a>
            <div class="flex items-center gap-5">
                <nav class="hidden sm:flex items-center gap-4 pr-4 mr-1 border-r border-slate-200">
                    <a href="{{ route('shower.entry') }}" aria-label="シャワー情報"
                        class="flex flex-col items-center gap-0.5 transition-colors {{ request()->routeIs('shower.*') ? 'text-brand-blue' : 'text-slate-400 hover:text-brand-blue' }}">
                        <span class="material-symbols-outlined !text-2xl leading-none">shower</span>
                        <span class="text-[11px] font-bold whitespace-nowrap">シャワー情報</span>
                    </a>
                    <a href="{{ route('english.hub') }}" aria-label="英語学習"
                        class="flex flex-col items-center gap-0.5 transition-colors {{ request()->routeIs('english.*') ? 'text-brand-yellow' : 'text-slate-400 hover:text-brand-yellow' }}">
                        <span class="material-symbols-outlined !text-2xl leading-none">menu_book</span>
                        <span class="text-[11px] font-bold whitespace-nowrap">英語学習</span>
                    </a>
                    <a href="{{ route('carinderia.index') }}" aria-label="留学情報"
                        class="flex flex-col items-center gap-0.5 transition-colors {{ request()->routeIs('carinderia.*') ? 'text-brand-green' : 'text-slate-400 hover:text-brand-green' }}">
                        <span class="material-symbols-outlined !text-2xl leading-none">flight</span>
                        <span class="text-[11px] font-bold whitespace-nowrap">留学情報</span>
                    </a>
                    <a href="{{ route('suggestion') }}" aria-label="目安箱"
                        class="flex flex-col items-center gap-0.5 transition-colors {{ request()->routeIs('suggestion') ? 'text-brand-red' : 'text-slate-400 hover:text-brand-red' }} ">
                        <span class="material-symbols-outlined !text-2xl leading-none">local_post_office</span>
                        <span class="text-[11px] font-bold whitespace-nowrap">目安箱</span>
                    </a>
                    @if (auth()->user()?->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" aria-label="Admin画面"
                            class="flex flex-col items-center gap-0.5 transition-colors {{ request()->routeIs('admin.*') ? 'text-indigo-600' : 'text-slate-400 hover:text-indigo-600' }}">
                            <span class="material-symbols-outlined !text-2xl leading-none">database</span>
                            <span class="text-[11px] font-bold whitespace-nowrap">システム管理</span>
                        </a>
                    @endif
                </nav>

                {{-- 通知 --}}
<div
    class="relative flex items-center"
    x-data="{
        open: false,
        notifications: [],
        unreadCount: 0,

        async load() {
            try {
                const response = await fetch('{{ route('notifications.data') }}');
                const data = await response.json();
                this.notifications = data.notifications.map(n => ({ ...n, expanded: false }));
                this.unreadCount = data.unread_count;
            } catch (e) {
                console.error('Failed to load notifications:', e);
            }
        },

        async toggle() {
            this.open = !this.open;

            if (this.open && this.unreadCount > 0) {
                try {
                    await fetch('{{ route('notifications.mark-seen') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                        },
                    });
                    this.unreadCount = 0;
                } catch (e) {
                    console.error('Failed to mark notifications as seen:', e);
                }
            }
        },

        {{-- カテゴリーごとの色設定 --}}
        getBadgeClass(category) {
            switch(category) {
                case '重要':
                    return 'bg-red-50 text-red-600 border-red-200';
                case '留学情報':
                    return 'bg-lime-50 text-lime-700 border-lime-200';
                case '英語学習':
                    return 'bg-amber-50 text-amber-700 border-amber-200';
                case 'シャワー':
                case 'シャワー機能':
                    return 'bg-sky-50 text-sky-600 border-sky-200';
                default:
                    return 'bg-slate-50 text-slate-600 border-slate-200';
            }
        }
    }"
    x-init="load()"
    @click.outside="open = false"
>

    {{-- 通知ボタン --}}
    <button
        @click="toggle()"
        class="relative p-1.5 text-slate-500 hover:text-brand-blue hover:bg-slate-50 rounded-full transition-colors focus:outline-none"
        aria-label="通知"
    >
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
            <path
                d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
            <path
                d="M13.7 21a2 2 0 01-3.4 0"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
            />
        </svg>

        <span
            x-show="unreadCount > 0"
            class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white"
        ></span>
    </button>

    {{-- 通知パネル --}}
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        class="absolute right-0 top-12 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50"
    >
        <div class="px-4 py-3 border-b border-slate-100 font-bold text-slate-700 text-sm flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-sm z-10">
            <span>通知</span>
            <span x-text="notifications.length + ' 件'" class="text-xs font-normal text-slate-400"></span>
        </div>

        {{-- 縦スクロール領域 --}}
        <div class="max-h-[340px] overflow-y-auto divide-y divide-slate-100">
            <template x-for="notification in notifications" :key="notification.id">
                <div
                    class="block px-4 py-3.5 transition-colors hover:bg-slate-50 relative"
                    :class="notification.is_unread ? 'bg-sky-50/40' : ''"
                >
                    <div class="flex items-start gap-2.5">
                        {{-- 未読ポッチ --}}
                        <template x-if="notification.is_unread">
                            <span class="w-2 h-2 mt-1.5 rounded-full bg-brand-blue flex-shrink-0"></span>
                        </template>

                        <div class="flex-1 min-w-0">
                            {{-- ① シャワー通知の場合（アイコン + メッセージ表示） --}}
                            <template x-if="!notification.category || notification.category.includes('シャワー')">
                                <a :href="notification.url || '#'" class="block">
                                    <div class="flex items-start gap-2">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-sky-100 text-brand-blue flex-shrink-0 mt-0.5">
                                            <span class="material-symbols-outlined !text-base leading-none">shower</span>
                                        </span>
                                        <h4 class="text-xs font-bold text-slate-800 leading-snug break-words" x-text="notification.message"></h4>
                                    </div>
                                </a>
                            </template>

                            {{-- ② シャワー以外の通知の場合 --}}
                            <template x-if="notification.category && !notification.category.includes('シャワー')">
                                <div>
                                    {{-- ヘッダー部分 --}}
                                    <div
                                        @click="notification.expanded = !notification.expanded"
                                        class="flex items-center justify-between gap-2 cursor-pointer select-none"
                                    >
                                        <div class="flex items-center gap-2 min-w-0 flex-1">
                                            {{-- 動的にクラスを適用するカテゴリーバッジ --}}
                                            <span
                                                class="text-[10px] font-bold px-2 py-0.5 rounded-full border flex-shrink-0"
                                                :class="getBadgeClass(notification.category)"
                                                x-text="notification.category"
                                            ></span>
                                            <h4 class="text-xs font-bold text-slate-800 truncate" x-text="notification.title || 'お知らせ'"></h4>
                                        </div>

                                        {{-- ＞ ボタン --}}
                                        <button
                                            type="button"
                                            class="p-1 text-slate-400 hover:text-slate-600 transition-transform flex-shrink-0"
                                            :class="notification.expanded ? 'rotate-90' : ''"
                                            aria-label="詳細表示"
                                        >
                                            <span class="material-symbols-outlined !text-base leading-none">chevron_right</span>
                                        </button>
                                    </div>

                                    {{-- 本文 --}}
                                    <div
                                        x-show="notification.expanded"
                                        x-cloak
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 -translate-y-1"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        class="mt-2 text-xs text-slate-600 leading-relaxed break-words bg-slate-50/80 p-2.5 rounded-xl border border-slate-100"
                                    >
                                        <p x-text="notification.message" class="whitespace-pre-line"></p>

                                        <template x-if="notification.url">
                                            <a :href="notification.url" class="inline-block text-xs text-brand-blue hover:underline mt-2 font-bold">
                                                詳細を見る &rarr;
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            {{-- 日時 --}}
                            <p class="text-[10px] text-slate-400 mt-1.5" x-text="notification.created_at"></p>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="notifications.length === 0">
                <div class="px-4 py-10 text-center text-slate-400">
                    <span class="material-symbols-outlined text-3xl mb-1 text-slate-300 block">notifications_off</span>
                    <p class="text-xs">通知はありません</p>
                </div>
            </template>
        </div>
    </div>

</div>
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open"
                        class="w-9 h-9 rounded-full ring-2 ring-sky-100 hover:ring-sky-200 transition-all overflow-hidden {{ Auth::user()->avatar_url ? '' : 'bg-brand-blue text-white text-sm font-bold flex items-center justify-center' }}"
                        aria-label="アカウントメニュー">
                        @if (Auth::user()->avatar_url)
                            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                            {{ Str::of(Auth::user()->name)->substr(0, 1)->upper() }}
                        @endif
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-3 w-52 bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden z-40"
                        style="display: none;">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-sm font-bold text-slate-700 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}"
                            class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-sky-50 hover:text-brand-blue transition-colors">{{ __('Profile') }}</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2.5 text-sm text-slate-600 hover:bg-sky-50 hover:text-brand-blue transition-colors">{{ __('Log Out') }}</button>
                        </form>
                    </div>
                </div>

                <button @click="mobileOpen = !mobileOpen"
                    class="sm:hidden text-slate-600 hover:text-brand-blue transition-colors" aria-label="メニュー">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" />
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="mobileOpen" x-transition @click.outside="mobileOpen = false"
            class="sm:hidden border-t border-slate-100 px-6 py-3 space-y-1" style="display: none;">
            <a href="{{ route('dashboard') }}"
                class="block py-2 text-sm font-semibold text-slate-600 hover:text-brand-blue">{{ __('Dashboard') }}</a>
            <a href="{{ route('shower.entry') }}"
                class="block py-2 text-sm font-semibold {{ request()->routeIs('shower.*') ? 'text-brand-blue' : 'text-slate-600 hover:text-brand-blue' }}">シャワー情報</a>
            <a href="{{ route('english.hub') }}"
                class="block py-2 text-sm font-semibold {{ request()->routeIs('english.*') ? 'text-brand-yellow' : 'text-slate-600 hover:text-brand-yellow' }}">英語学習</a>
            <a href="{{ route('carinderia.index') }}"
                class="block py-2 text-sm font-semibold {{ request()->routeIs('carinderia.*') ? 'text-brand-green' : 'text-slate-600 hover:text-brand-green' }}">留学情報</a>
            <a href="{{ route('suggestion') }}"
                class="block py-2 text-sm font-semibold {{ request()->routeIs('suggestion') ? 'text-brand-red' : 'text-slate-600 hover:text-brand-red' }}">目安箱</a>
            @if (auth()->user()?->isAdmin())
                <a href="{{ route('admin.dashboard') }}"
                    class="block py-2 text-sm font-semibold {{ request()->routeIs('admin.*') ? 'text-indigo-600' : 'text-slate-600 hover:text-indigo-600' }}">システム管理</a>
            @endif
            <a href="{{ route('profile.edit') }}"
                class="block py-2 text-sm font-semibold text-slate-600 hover:text-brand-blue">{{ __('Profile') }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="block w-full text-left py-2 text-sm font-semibold text-slate-600 hover:text-brand-blue">{{ __('Log Out') }}</button>
            </form>
        </div>
    </header>

    <!-- Page Heading (optional, for pages without their own hero) -->
    @hasSection('header')
        <div class="max-w-[1140px] mx-auto px-6 pt-10">
            @yield('header')
        </div>
    @endif

    <!-- Page Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    {{--
        Carinderia/Restaurant&Cafe/Travel/Other/投稿/編集など、
        独自の固定フッターナビ(Home/Post/Map)を持っているページでは、
        この会社紹介用フッターは表示しない(下に重なって見えてしまうため)。
        新しいページでこの固定ナビを使う時は、routeIs() の一覧にルート名を足すだけでOK。
    --}}
    @unless (request()->routeIs(['carinderia.*', 'restaurant-cafe.*', 'travel.*', 'other.*', 'information.*','earth.*']))
        <footer class="relative bg-[#334155] text-gray-300 text-center">
            {{-- 波型ディバイダー: main の一部を覆わず自然に高さを足すだけなので、
                 どのページ（青/オレンジ/緑テーマ）の直下に来ても崩れない --}}
            <div aria-hidden="true" class="leading-[0]">
                <svg viewBox="0 0 1440 48" preserveAspectRatio="none" class="w-full h-6 sm:h-8 block">
                    <path d="M0,24 C220,48 360,0 600,16 C860,32 1000,2 1220,18 C1320,24 1400,20 1440,16 L1440,48 L0,48 Z"
                          fill="#334155" />
                </svg>
            </div>

            <div class="max-w-[1140px] mx-auto px-6 pt-0 pb-7 flex flex-col items-center gap-2.5">
                <div class="flex items-center gap-2 text-slate-200">
                    <svg width="18" height="15" viewBox="0 0 48 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 3 C13 2, 16 2.5, 17.5 4 L13.5 37 C9.5 36.5, 7 35, 5.5 33.5 Z" fill="#7c9cf0" />
                        <path d="M13 22 L31 3 C34 2, 36.5 3.5, 37.5 6.5 L16.5 25.5 C14.5 24.8, 13.4 23.6, 13 22 Z" fill="#f0947f" />
                        <path d="M13.5 23.5 L32 32.5 C32.5 35.5, 31 37.8, 28.5 38.5 L12 27 C12.2 25.5, 12.7 24.4, 13.5 23.5 Z" fill="#a9d488" />
                        <path d="M40.5 6.5h3.5v4h4v3.5h-4v4h-3.5v-4h-4V10.5h4z" fill="#f7c862" />
                    </svg>
                    <span class="font-display font-bold text-sm tracking-wide text-white">Kredo Plus</span>
                </div>
                <p class="text-xs font-light tracking-wide text-slate-400">&copy; Kredo Plus. All rights reserved.</p>
            </div>
        </footer>
    @endunless

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('genderModal', {
                    open: false,
                });
            });
        </script>
    @endpush

    @stack('scripts')

    @hasSection('intro')
        <script>
            (function() {
                const intro = document.getElementById('intro');
                const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                function finish() {
                    document.body.classList.add('loaded');
                    if (intro) intro.remove();
                }

                if (reduced || !intro) {
                    finish();
                    return;
                }

                setTimeout(() => {
                    intro.classList.add('leave');
                    intro.addEventListener('animationend', finish, {
                        once: true
                    });
                    setTimeout(finish, 1300);
                }, 2600);
            })();
        </script>
    @else
        <script>
            document.body.classList.add('loaded');
        </script>
    @endif
</body>

</html>
