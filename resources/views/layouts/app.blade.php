<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Kredo Plus'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;800&family=Poppins:wght@600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .material-symbols-outlined {
                font-family: 'Material Symbols Outlined';
                font-weight: normal;
                font-style: normal;
                font-size: 24px;
                line-height: 1;
                letter-spacing: normal;
                text-transform: none;
                white-space: nowrap;
                word-wrap: normal;
                direction: ltr;
                -webkit-font-feature-settings: 'liga';
                font-feature-settings: 'liga';
                -webkit-font-smoothing: antialiased;
            }

            .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
            .scrollbar-hide::-webkit-scrollbar { display: none; }

            .wordmark-kredo {
                background: linear-gradient(100deg, #4338ca 0%, #2f5fdb 55%, #2563eb 100%);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }
            .wordmark-plus { color: #3f6db3; }

            .kredo-bar {
                background: linear-gradient(90deg, #2f5fdb 0%, #e05237 33%, #f5b52e 66%, #5eab35 100%);
            }

            /* Fade-up on load */
            .fade-up { opacity: 0; }
            body.loaded .fade-up { animation: fadeUp .7s ease both; }
            body.loaded .fade-up-1 { animation-delay: .10s; }
            body.loaded .fade-up-2 { animation-delay: .20s; }
            body.loaded .fade-up-3 { animation-delay: .32s; }
            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(14px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* Opening curtain */
            #intro {
                position: fixed; inset: 0; z-index: 100;
                background: linear-gradient(160deg, #eff6ff 0%, #e0f2fe 60%, #dbeafe 100%);
                display: flex; align-items: center; justify-content: center;
                flex-direction: column; gap: 20px;
            }
            #intro.leave { animation: curtainUp .8s cubic-bezier(.76,0,.24,1) .1s both; }
            @keyframes curtainUp { to { transform: translateY(-100%); } }

            .intro-ribbon { opacity: 0; transform-origin: 20% 55%; }
            .intro-ribbon-blue  { animation: ribbonIn .5s cubic-bezier(.34,1.56,.64,1) .15s both; }
            .intro-ribbon-red   { animation: ribbonIn .5s cubic-bezier(.34,1.56,.64,1) .35s both; }
            .intro-ribbon-green { animation: ribbonIn .5s cubic-bezier(.34,1.56,.64,1) .55s both; }
            .intro-plus         { opacity: 0; animation: plusPop .45s cubic-bezier(.34,1.56,.64,1) .8s both; transform-origin: 88% 30%; }
            @keyframes ribbonIn {
                from { opacity: 0; transform: scale(.3) rotate(-20deg); }
                to   { opacity: 1; transform: scale(1) rotate(0); }
            }
            @keyframes plusPop {
                from { opacity: 0; transform: scale(0); }
                60%  { transform: scale(1.25); }
                to   { opacity: 1; transform: scale(1); }
            }

            .intro-word { overflow: hidden; }
            .intro-word span { display: inline-block; transform: translateY(110%); animation: wordUp .6s cubic-bezier(.22,1,.36,1) both; }
            .intro-word .w1 { animation-delay: 1.0s; }
            .intro-word .w2 { animation-delay: 1.12s; }
            @keyframes wordUp { to { transform: translateY(0); } }

            .intro-tag { opacity: 0; animation: tagIn .5s ease 1.4s both; }
            @keyframes tagIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

            /* Hero background: IT motif */
            .pulse { stroke-dasharray: 40 460; animation: pulseTravel 5s linear infinite; }
            .pulse.p2 { animation-delay: 1.6s; animation-duration: 6s; }
            .pulse.p3 { animation-delay: 3.2s; animation-duration: 7s; }
            @keyframes pulseTravel {
                from { stroke-dashoffset: 500; }
                to   { stroke-dashoffset: 0; }
            }
            .node { animation: nodeBlink 3.2s ease-in-out infinite; }
            .node.n2 { animation-delay: .8s; }
            .node.n3 { animation-delay: 1.6s; }
            .node.n4 { animation-delay: 2.4s; }
            @keyframes nodeBlink {
                0%, 100% { opacity: .35; }
                50% { opacity: 1; }
            }
            .float-code { animation: floatY 7s ease-in-out infinite; }
            .float-code.f2 { animation-delay: 1.5s; animation-duration: 9s; }
            .float-code.f3 { animation-delay: 3s; animation-duration: 8s; }
            .float-code.f4 { animation-delay: .8s; animation-duration: 10s; }
            @keyframes floatY {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-16px); }
            }
            .blink { animation: blink 1.1s steps(1) infinite; }
            @keyframes blink { 50% { opacity: 0; } }

            /* Penguin shower character */
            .pg-body { animation: pgSway 2.6s ease-in-out infinite; transform-origin: 50% 85%; transform-box: fill-box; }
            @keyframes pgSway {
                0%, 100% { transform: rotate(-3deg); }
                50% { transform: rotate(3deg); }
            }
            .pg-drop { animation: dropFall 1s linear infinite; }
            .pg-drop.d2 { animation-delay: .25s; }
            .pg-drop.d3 { animation-delay: .5s; }
            .pg-drop.d4 { animation-delay: .75s; }
            @keyframes dropFall {
                0%   { transform: translateY(0); opacity: 0; }
                12%  { opacity: .9; }
                85%  { opacity: .8; }
                100% { transform: translateY(34px); opacity: 0; }
            }
            .pg-bubble { animation: bubbleUp 3.4s ease-in infinite; }
            .pg-bubble.b2 { animation-delay: 1.1s; }
            .pg-bubble.b3 { animation-delay: 2.2s; }
            @keyframes bubbleUp {
                0%   { transform: translateY(0) scale(.6); opacity: 0; }
                20%  { opacity: .8; }
                100% { transform: translateY(-26px) scale(1.1); opacity: 0; }
            }
            .pg-steam { animation: steamRise 4s ease-in-out infinite; }
            @keyframes steamRise {
                0%   { transform: translateY(0); opacity: 0; }
                30%  { opacity: .5; }
                100% { transform: translateY(-14px); opacity: 0; }
            }

            /* Otter typing character */
            .ot-head { animation: otNod 2.2s ease-in-out infinite; transform-origin: 50% 90%; transform-box: fill-box; }
            @keyframes otNod {
                0%, 100% { transform: rotate(0deg); }
                50% { transform: rotate(-4deg); }
            }
            .ot-paw { animation: otType .24s ease-in-out infinite alternate; transform-box: fill-box; }
            .ot-paw.pw2 { animation-delay: .12s; }
            @keyframes otType {
                from { transform: translateY(0); }
                to   { transform: translateY(-2.5px); }
            }
            .ot-screen-line { animation: screenType 2.4s steps(6) infinite; stroke-dasharray: 22; }
            .ot-screen-line.sl2 { animation-delay: .8s; }
            @keyframes screenType {
                0% { stroke-dashoffset: 22; }
                60%, 100% { stroke-dashoffset: 0; }
            }
            .ot-spark { animation: sparkFloat 2.8s ease-out infinite; }
            .ot-spark.sp2 { animation-delay: 1.4s; }
            @keyframes sparkFloat {
                0%   { transform: translateY(0); opacity: 0; }
                25%  { opacity: .9; }
                100% { transform: translateY(-18px); opacity: 0; }
            }
            .ot-tail { animation: tailWag 1.6s ease-in-out infinite; transform-origin: 0% 50%; transform-box: fill-box; }
            @keyframes tailWag {
                0%, 100% { transform: rotate(0deg); }
                50% { transform: rotate(8deg); }
            }

            /* Explore Cebu background: adventure motif */
            .adv-plane {
                position: absolute; top: 6%; left: 0;
                animation: flyAcross 28s linear infinite;
                will-change: transform;
            }
            @keyframes flyAcross {
                0%   { transform: translate(-14vw, 26px) rotate(2deg); }
                45%  { transform: translate(46vw, -12px) rotate(-2deg); }
                100% { transform: translate(114vw, 10px) rotate(2deg); }
            }
            .adv-trail { stroke-dasharray: 5 9; animation: trailFlow 1.2s linear infinite; }
            @keyframes trailFlow { to { stroke-dashoffset: -14; } }

            .adv-cloud {
                position: absolute;
                animation: cloudDrift 70s linear infinite;
                will-change: transform;
            }
            .adv-cloud.c2 { animation-duration: 95s; animation-delay: -40s; }
            @keyframes cloudDrift {
                from { transform: translateX(-20vw); }
                to   { transform: translateX(115vw); }
            }

            .adv-jeepney {
                position: absolute; bottom: 12px; left: 0;
                animation: driveAcross 22s linear infinite;
                animation-delay: 3s;
                will-change: transform;
            }
            @keyframes driveAcross {
                from { transform: translateX(-18vw); }
                to   { transform: translateX(114vw); }
            }
            .adv-jeepney-body { animation: jiggle .45s ease-in-out infinite; }
            @keyframes jiggle {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-1.5px); }
            }
            .adv-wheel { animation: wheelSpin .8s linear infinite; transform-box: fill-box; transform-origin: center; }
            @keyframes wheelSpin { to { transform: rotate(360deg); } }

            .adv-traveler {
                position: absolute; bottom: 8px; right: 0;
                animation: walkAcross 44s linear infinite;
                animation-delay: 12s;
                will-change: transform;
            }
            @keyframes walkAcross {
                from { transform: translateX(16vw); }
                to   { transform: translateX(-114vw); }
            }
            .adv-walk-bob { animation: walkBob .55s ease-in-out infinite; }
            @keyframes walkBob {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-2px); }
            }
            .adv-leg { animation: legSwing .55s ease-in-out infinite alternate; transform-origin: 50% 8%; transform-box: fill-box; }
            .adv-leg.lg2 { animation-direction: alternate-reverse; }
            @keyframes legSwing {
                from { transform: rotate(20deg); }
                to   { transform: rotate(-20deg); }
            }
            .adv-arm { animation: armSwing .55s ease-in-out infinite alternate; transform-origin: 50% 10%; transform-box: fill-box; }
            .adv-arm.am2 { animation-direction: alternate-reverse; }
            @keyframes armSwing {
                from { transform: rotate(-14deg); }
                to   { transform: rotate(14deg); }
            }

            .adv-birds {
                position: absolute; top: 22%; left: 0;
                animation: birdDrift 36s linear infinite;
                animation-delay: 7s;
                will-change: transform;
            }
            @keyframes birdDrift {
                0%   { transform: translate(-10vw, 8px); }
                50%  { transform: translate(50vw, -12px); }
                100% { transform: translate(112vw, 4px); }
            }
            .adv-wing { animation: flap .55s ease-in-out infinite alternate; transform-box: fill-box; transform-origin: center bottom; }
            @keyframes flap { from { transform: rotate(0deg) scaleY(1); } to { transform: rotate(0deg) scaleY(.35); } }

            .adv-pin { animation: pinBounce 2.6s ease-in-out infinite; }
            @keyframes pinBounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-8px); }
            }
            .adv-sun { animation: sunPulse 6s ease-in-out infinite; transform-origin: center; transform-box: fill-box; }
            @keyframes sunPulse {
                0%, 100% { transform: scale(1); opacity: .85; }
                50% { transform: scale(1.06); opacity: 1; }
            }

            @media (prefers-reduced-motion: reduce) {
                .fade-up, body.loaded .fade-up { animation: none; opacity: 1; }
                #intro { display: none; }
                [class*="pulse"], [class*="node"], [class*="float-code"], .blink,
                [class*="intro-"], [class*="pg-"], [class*="ot-"], [class*="adv-"] { animation: none !important; }
                * { transition: none !important; }
            }
        </style>
    </head>
    <body class="font-sans bg-white text-slate-800 antialiased" x-data="{ mobileOpen: false }">

        @hasSection('intro')
            <!-- Opening -->
            <div id="intro" aria-hidden="true">
                <svg width="120" height="100" viewBox="0 0 48 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="iRibbonBlue" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#4f7df0"/><stop offset="100%" stop-color="#2b52c7"/>
                        </linearGradient>
                        <linearGradient id="iRibbonRed" x1="1" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#f0654a"/><stop offset="100%" stop-color="#d94427"/>
                        </linearGradient>
                        <linearGradient id="iRibbonGreen" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#8fce54"/><stop offset="100%" stop-color="#5eab35"/>
                        </linearGradient>
                    </defs>
                    <path class="intro-ribbon intro-ribbon-blue" d="M9 3 C13 2, 16 2.5, 17.5 4 L13.5 37 C9.5 36.5, 7 35, 5.5 33.5 Z" fill="url(#iRibbonBlue)"/>
                    <path class="intro-ribbon intro-ribbon-red" d="M13 22 L31 3 C34 2, 36.5 3.5, 37.5 6.5 L16.5 25.5 C14.5 24.8, 13.4 23.6, 13 22 Z" fill="url(#iRibbonRed)"/>
                    <path class="intro-ribbon intro-ribbon-green" d="M13.5 23.5 L32 32.5 C32.5 35.5, 31 37.8, 28.5 38.5 L12 27 C12.2 25.5, 12.7 24.4, 13.5 23.5 Z" fill="url(#iRibbonGreen)"/>
                    <path class="intro-plus" d="M40.5 6.5h3.5v4h4v3.5h-4v4h-3.5v-4h-4V10.5h4z" fill="#f5b52e"/>
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
                    <svg width="40" height="34" viewBox="0 0 48 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-transform duration-300 group-hover:rotate-[-4deg]">
                        <defs>
                            <linearGradient id="ribbonBlue" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#4f7df0"/><stop offset="100%" stop-color="#2b52c7"/>
                            </linearGradient>
                            <linearGradient id="ribbonRed" x1="1" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#f0654a"/><stop offset="100%" stop-color="#d94427"/>
                            </linearGradient>
                            <linearGradient id="ribbonGreen" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#8fce54"/><stop offset="100%" stop-color="#5eab35"/>
                            </linearGradient>
                        </defs>
                        <path d="M9 3 C13 2, 16 2.5, 17.5 4 L13.5 37 C9.5 36.5, 7 35, 5.5 33.5 Z" fill="url(#ribbonBlue)"/>
                        <path d="M13 22 L31 3 C34 2, 36.5 3.5, 37.5 6.5 L16.5 25.5 C14.5 24.8, 13.4 23.6, 13 22 Z" fill="url(#ribbonRed)"/>
                        <path d="M13.5 23.5 L32 32.5 C32.5 35.5, 31 37.8, 28.5 38.5 L12 27 C12.2 25.5, 12.7 24.4, 13.5 23.5 Z" fill="url(#ribbonGreen)"/>
                        <path d="M40.5 6.5h3.5v4h4v3.5h-4v4h-3.5v-4h-4V10.5h4z" fill="#f5b52e"/>
                    </svg>
                    <span class="font-display font-extrabold text-2xl tracking-tight">
                        <span class="wordmark-kredo">Kredo</span> <span class="wordmark-plus">Plus</span>
                    </span>
                </a>
                <div class="flex items-center gap-5">
                    <button class="relative text-slate-500 hover:text-brand-blue transition-colors" aria-label="通知">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.7 21a2 2 0 01-3.4 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>

                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="w-9 h-9 rounded-full bg-brand-blue text-white text-sm font-bold flex items-center justify-center ring-2 ring-sky-100 hover:ring-sky-200 transition-all" aria-label="アカウントメニュー">
                            {{ Str::of(Auth::user()->name)->substr(0, 1)->upper() }}
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-52 bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden z-40"
                             style="display: none;">
                            <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-sm font-bold text-slate-700 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-sky-50 hover:text-brand-blue transition-colors">{{ __('Profile') }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-slate-600 hover:bg-sky-50 hover:text-brand-blue transition-colors">{{ __('Log Out') }}</button>
                            </form>
                        </div>
                    </div>

                    <button @click="mobileOpen = !mobileOpen" class="text-slate-600 hover:text-brand-blue transition-colors" aria-label="メニュー">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </button>
                </div>
            </div>

            <div x-show="mobileOpen" x-transition @click.outside="mobileOpen = false" class="sm:hidden border-t border-slate-100 px-6 py-3 space-y-1" style="display: none;">
                <a href="{{ route('dashboard') }}" class="block py-2 text-sm font-semibold text-slate-600 hover:text-brand-blue">{{ __('Dashboard') }}</a>
                <a href="{{ route('profile.edit') }}" class="block py-2 text-sm font-semibold text-slate-600 hover:text-brand-blue">{{ __('Profile') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left py-2 text-sm font-semibold text-slate-600 hover:text-brand-blue">{{ __('Log Out') }}</button>
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
        <main>
            @yield('content')
        </main>

        <footer class="bg-[#334155] text-gray-300 text-center">
            <p class="py-6 text-sm font-light tracking-wide">&copy; Kredo Plus</p>
        </footer>

        @hasSection('intro')
            <script>
                (function () {
                    const intro = document.getElementById('intro');
                    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                    function finish() {
                        document.body.classList.add('loaded');
                        if (intro) intro.remove();
                    }

                    if (reduced || !intro) { finish(); return; }

                    setTimeout(() => {
                        intro.classList.add('leave');
                        intro.addEventListener('animationend', finish, { once: true });
                        setTimeout(finish, 1200);
                    }, 1900);
                })();
            </script>
        @else
            <script>document.body.classList.add('loaded');</script>
        @endif
    </body>
</html>
