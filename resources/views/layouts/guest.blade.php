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

            @media (prefers-reduced-motion: reduce) {
                .fade-up { animation: none; }
                * { transition: none !important; }
            }
        </style>
    </head>
    <body class="font-sans bg-gradient-to-b from-sky-50 via-white to-white text-slate-800 antialiased min-h-screen flex items-center justify-center p-4">

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

                <!-- Session Status -->
                <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

                @yield('content')
            </section>

            @hasSection('below-card')
                <div class="mt-6 text-center">
                    @yield('below-card')
                </div>
            @endif

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
