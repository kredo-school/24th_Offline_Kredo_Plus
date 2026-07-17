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
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;800&family=Poppins:wght@600;700;800&family=IBM+Plex+Mono:wght@500;600&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

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
        <header class="w-full bg-white sticky top-0 z-30 shadow-[0_1px_0_rgba(15,23,42,0.06)]">
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
                    <button class="relative text-slate-500 hover:text-brand-blue transition-colors" aria-label="通知">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M13.7 21a2 2 0 01-3.4 0" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" />
                        </svg>
                        <span
                            class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>

                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                            class="w-9 h-9 rounded-full bg-brand-blue text-white text-sm font-bold flex items-center justify-center ring-2 ring-sky-100 hover:ring-sky-200 transition-all"
                            aria-label="アカウントメニュー">
                            {{ Str::of(Auth::user()->name)->substr(0, 1)->upper() }}
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
                        class="text-slate-600 hover:text-brand-blue transition-colors" aria-label="メニュー">
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

    <footer class="bg-[#334155] text-gray-300 text-center">
        <p class="py-6 text-sm font-light tracking-wide">&copy; Kredo Plus</p>
    </footer>

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
                    setTimeout(finish, 1200);
                }, 1900);
            })();
        </script>
    @else
        <script>
            document.body.classList.add('loaded');
        </script>
    @endif
</body>

</html>
