<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kredo Plus') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
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

            .fade-up { animation: fadeUp .7s ease both; }
            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(16px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            #auth-card { transition: opacity .3s ease, transform .3s ease; }

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

            @media (prefers-reduced-motion: reduce) {
                .fade-up { animation: none; }
                [class*="pulse"], [class*="node"], [class*="float-code"], .blink { animation: none !important; }
                * { transition: none !important; }
            }
        </style>
    </head>
    <body class="font-sans bg-gradient-to-b from-sky-100 via-sky-50 to-white text-slate-800 antialiased min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

        <!-- Background: IT motif + soft color glows -->
        <div class="absolute inset-0 z-0 pointer-events-none" aria-hidden="true">
            <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[720px] h-[420px] rounded-full bg-sky-200/40 blur-3xl"></div>
            <div class="absolute top-20 -left-24 w-[340px] h-[340px] rounded-full bg-amber-100/50 blur-3xl"></div>
            <div class="absolute bottom-10 -right-24 w-[340px] h-[340px] rounded-full bg-emerald-100/50 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/4 w-[300px] h-[240px] rounded-full bg-rose-100/40 blur-3xl"></div>

            <svg class="absolute inset-0 w-full h-full" viewBox="0 0 1200 800" preserveAspectRatio="xMidYMid slice" fill="none">
                <g stroke="#93c5fd" stroke-width="1.5" opacity="0.4">
                    <path d="M-20 140 H120 L180 200 V360 H100 L60 400 V620"/>
                    <path d="M-20 300 H80 L140 360 H240 V520 L200 600"/>
                    <path d="M1220 120 H1080 L1020 190 V340 H1100 L1150 400 V640"/>
                    <path d="M1220 320 H1120 L1060 380 H960 V560"/>
                </g>
                <g stroke-width="2.5" stroke-linecap="round" fill="none" opacity="0.8">
                    <path class="pulse" stroke="#2f5fdb" d="M-20 140 H120 L180 200 V360 H100 L60 400 V620"/>
                    <path class="pulse p2" stroke="#e05237" d="M1220 120 H1080 L1020 190 V340 H1100 L1150 400 V640"/>
                    <path class="pulse p3" stroke="#5eab35" d="M-20 300 H80 L140 360 H240 V520 L200 600"/>
                </g>
                <g>
                    <circle class="node"    cx="180" cy="200" r="5" fill="#2f5fdb"/>
                    <circle class="node n2" cx="100" cy="360" r="5" fill="#5eab35"/>
                    <circle class="node n3" cx="1020" cy="190" r="5" fill="#e05237"/>
                    <circle class="node n4" cx="1100" cy="340" r="5" fill="#f5b52e"/>
                </g>
                <g stroke="#60a5fa" fill="none" opacity="0.5">
                    <circle class="node n3" cx="180" cy="200" r="11"/>
                    <circle class="node"    cx="1020" cy="190" r="11"/>
                </g>
                <g class="float-code">
                    <text x="90" y="130" font-size="26" fill="#60a5fa" opacity="0.5" font-family="ui-monospace, Menlo, monospace" font-weight="700">&lt;/&gt;</text>
                </g>
                <g class="float-code f2">
                    <text x="1060" y="110" font-size="24" fill="#e05237" opacity="0.4" font-family="ui-monospace, Menlo, monospace" font-weight="700">{ }</text>
                </g>
                <g class="float-code f3">
                    <text x="200" y="640" font-size="15" fill="#5eab35" opacity="0.45" font-family="ui-monospace, Menlo, monospace" letter-spacing="3">101010</text>
                </g>
                <g class="float-code f4">
                    <text x="950" y="640" font-size="15" fill="#93c5fd" opacity="0.5" font-family="ui-monospace, Menlo, monospace" letter-spacing="3">010011</text>
                </g>
                <g class="float-code f2">
                    <text x="1060" y="600" font-size="18" fill="#60a5fa" opacity="0.5" font-family="ui-monospace, Menlo, monospace" font-weight="700">&gt;_</text>
                    <rect class="blink" x="1087" y="586" width="9" height="16" fill="#f5b52e" opacity="0.7"/>
                </g>
            </svg>
        </div>

        <!-- Main -->
        <main class="w-full max-w-[440px] z-10 fade-up">

            <!-- Logo & Brand -->
            <header class="flex flex-col items-center mb-8">
                <a href="/" class="mb-3">
                    <svg width="76" height="64" viewBox="0 0 48 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="drop-shadow-sm">
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
                </a>
                <h1 class="font-display font-extrabold text-4xl tracking-tight">
                    <span class="wordmark-kredo">Kredo</span> <span class="wordmark-plus">Plus</span>
                </h1>
                <p class="text-slate-500 mt-2">@yield('welcome', 'おかえりなさい、ログインして続けましょう。')</p>
            </header>

            <!-- Form Card -->
            <section class="bg-white/85 backdrop-blur border border-white/60 rounded-[24px] shadow-card p-7 relative overflow-hidden" id="auth-card">
                <div class="kredo-bar absolute top-0 inset-x-0 h-1.5"></div>

                <!-- Tab Switcher -->
                <div class="flex bg-sky-50 rounded-full p-1 mb-6">
                    <a href="{{ route('login') }}"
                       class="flex-1 text-center py-2.5 rounded-full text-sm font-bold transition-all {{ request()->routeIs('login') ? 'bg-white text-brand-blue shadow-sm' : 'text-slate-400 hover:text-brand-blue' }}">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}"
                       class="flex-1 text-center py-2.5 rounded-full text-sm font-bold transition-all {{ request()->routeIs('register') ? 'bg-white text-brand-blue shadow-sm' : 'text-slate-400 hover:text-brand-blue' }}">
                        Sign Up
                    </a>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

                @yield('content')
            </section>

            <!-- Footer -->
            <footer class="mt-8 text-center">
                <div class="flex justify-center items-center gap-1.5 mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-blue"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-red"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-yellow"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-green"></span>
                </div>
                <p class="text-xs text-slate-400 tracking-wide">&copy; Kredo Plus &mdash; セブ島で学ぶあなたを応援します。</p>
            </footer>
        </main>

        <script>
            document.querySelectorAll('[data-toggle-password]').forEach((button) => {
                button.addEventListener('click', () => {
                    const input = document.getElementById(button.dataset.togglePassword);
                    const icon = button.querySelector('svg');
                    const show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    icon.innerHTML = show
                        ? '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-6.5 0-10-7-10-7a18.4 18.4 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c6.5 0 10 7 10 7a18.5 18.5 0 01-2.16 3.19M1 1l22 22"/><path d="M9.5 9.5a3 3 0 004.24 4.24"/>'
                        : '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>';
                });
            });
        </script>
    </body>
</html>
