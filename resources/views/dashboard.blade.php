@extends('layouts.app')

@section('intro', true)

@section('content')

    <!-- Hero -->
    <section class="relative overflow-hidden bg-gradient-to-b from-sky-100 via-sky-50 to-white">
        <!-- Background: IT motif + soft color glows -->
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[720px] h-[420px] rounded-full bg-sky-200/40 blur-3xl"></div>
            <div class="absolute top-32 -left-24 w-[340px] h-[340px] rounded-full bg-amber-100/50 blur-3xl"></div>
            <div class="absolute top-40 -right-24 w-[340px] h-[340px] rounded-full bg-emerald-100/50 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/4 w-[300px] h-[240px] rounded-full bg-rose-100/40 blur-3xl"></div>

            <svg class="absolute inset-x-0 top-0 w-full h-full" viewBox="0 0 1200 560" preserveAspectRatio="xMidYMid slice" fill="none">
                <g stroke="#93c5fd" stroke-width="1.5" opacity="0.45">
                    <path d="M-20 120 H120 L180 180 V300 H100 L60 340 V480"/>
                    <path d="M-20 240 H80 L140 300 H240 V420 L200 460"/>
                    <path d="M60 60 V140 L120 200" opacity="0.6"/>
                </g>
                <g stroke="#93c5fd" stroke-width="1.5" opacity="0.45">
                    <path d="M1220 100 H1080 L1020 160 V280 H1100 L1150 330 V470"/>
                    <path d="M1220 260 H1120 L1060 320 H960 V430"/>
                    <path d="M1150 50 V130 L1090 190" opacity="0.6"/>
                </g>

                <g stroke-width="2.5" stroke-linecap="round" fill="none" opacity="0.85">
                    <path class="pulse" stroke="#2f5fdb" d="M-20 120 H120 L180 180 V300 H100 L60 340 V480"/>
                    <path class="pulse p2" stroke="#e05237" d="M1220 100 H1080 L1020 160 V280 H1100 L1150 330 V470"/>
                    <path class="pulse p3" stroke="#5eab35" d="M-20 240 H80 L140 300 H240 V420 L200 460"/>
                </g>

                <g>
                    <circle class="node"    cx="180" cy="180" r="5" fill="#2f5fdb"/>
                    <circle class="node n2" cx="100" cy="300" r="5" fill="#5eab35"/>
                    <circle class="node n3" cx="1020" cy="160" r="5" fill="#e05237"/>
                    <circle class="node n4" cx="1100" cy="280" r="5" fill="#f5b52e"/>
                    <circle class="node n2" cx="140" cy="300" r="4" fill="#f5b52e"/>
                    <circle class="node n3" cx="1060" cy="320" r="4" fill="#2f5fdb"/>
                </g>
                <g stroke="#60a5fa" fill="none" opacity="0.5">
                    <circle class="node n3" cx="180" cy="180" r="11"/>
                    <circle class="node"    cx="1020" cy="160" r="11"/>
                </g>

                <g class="float-code">
                    <text x="90" y="110" font-size="26" fill="#60a5fa" opacity="0.55" font-family="ui-monospace, Menlo, monospace" font-weight="700">&lt;/&gt;</text>
                </g>
                <g class="float-code f2">
                    <text x="1060" y="90" font-size="24" fill="#e05237" opacity="0.45" font-family="ui-monospace, Menlo, monospace" font-weight="700">{ }</text>
                </g>
                <g class="float-code f3">
                    <text x="220" y="420" font-size="15" fill="#5eab35" opacity="0.5" font-family="ui-monospace, Menlo, monospace" letter-spacing="3">101010</text>
                </g>
                <g class="float-code f4">
                    <text x="950" y="440" font-size="15" fill="#93c5fd" opacity="0.55" font-family="ui-monospace, Menlo, monospace" letter-spacing="3">010011</text>
                </g>
                <g class="float-code f2">
                    <text x="1105" y="380" font-size="18" fill="#60a5fa" opacity="0.55" font-family="ui-monospace, Menlo, monospace" font-weight="700">&gt;_</text>
                    <rect class="blink" x="1132" y="366" width="9" height="16" fill="#f5b52e" opacity="0.75"/>
                </g>
            </svg>
        </div>

        <div class="relative max-w-[1140px] mx-auto px-6 pt-16 pb-20 text-center">
            <span class="fade-up inline-flex items-center gap-2.5 bg-white border border-brand-blue/15 text-brand-blue text-sm font-medium px-6 py-2.5 rounded-full shadow-soft">
                <span class="flex gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-red"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-yellow"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-green"></span>
                </span>
                IT × 英語で人生の選択肢を広げる
            </span>

            <h1 class="fade-up fade-up-1 mt-8 font-display font-extrabold text-6xl sm:text-7xl tracking-tight flex items-center justify-center gap-3 flex-wrap">
                <span><span class="wordmark-kredo">Kredo</span> <span class="wordmark-plus">Plus</span></span>
                <svg width="64" height="54" viewBox="0 0 48 40" fill="none" class="inline-block -mt-2">
                    <path d="M9 3 C13 2, 16 2.5, 17.5 4 L13.5 37 C9.5 36.5, 7 35, 5.5 33.5 Z" fill="url(#ribbonBlue)"/>
                    <path d="M13 22 L31 3 C34 2, 36.5 3.5, 37.5 6.5 L16.5 25.5 C14.5 24.8, 13.4 23.6, 13 22 Z" fill="url(#ribbonRed)"/>
                    <path d="M13.5 23.5 L32 32.5 C32.5 35.5, 31 37.8, 28.5 38.5 L12 27 C12.2 25.5, 12.7 24.4, 13.5 23.5 Z" fill="url(#ribbonGreen)"/>
                    <path d="M40.5 6.5h3.5v4h4v3.5h-4v4h-3.5v-4h-4V10.5h4z" fill="#f5b52e"/>
                </svg>
            </h1>

            <p class="fade-up fade-up-2 mt-7 max-w-2xl mx-auto text-slate-600 leading-loose">
                Kredo Plusは、セブ島でITと英語を学ぶ皆さんのための留学サポートサイトです。英語学習コンテンツをはじめ、学校周辺の生活情報やおすすめスポットなど、留学生活に役立つ情報をみんなで共有し、より充実した留学生活をつくりましょう。
            </p>

            <!-- Feature cards -->
            <div class="fade-up fade-up-3 mt-14 flex flex-col sm:flex-row gap-7 justify-center">

                <!-- Shower information -->
                <div class="group relative bg-white rounded-[24px] shadow-card hover:shadow-card-hover hover:-translate-y-1.5 transition-all duration-300 p-9 pb-7 w-full sm:w-[350px] text-left overflow-hidden">
                    <div class="absolute top-0 inset-x-0 h-1.5 rounded-t-[24px] bg-gradient-to-r from-sky-400 to-brand-blue"></div>
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-100 to-sky-50 ring-1 ring-sky-200 flex items-center justify-center mb-6 group-hover:scale-105 transition-transform duration-300">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#2f5fdb" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 11h14"/>
                            <path d="M6.5 11a5.5 5.5 0 0111 0"/>
                            <path d="M4 8.5L2.5 7"/>
                            <path d="M8 15l-.6 1.6M12 15.5v2M16 15l.6 1.6"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-brand-blue tracking-widest">Shower Information</p>
                    <h3 class="mt-1.5 font-bold text-xl text-slate-800">シャワー情報</h3>
                    <p class="mt-3 text-sm text-slate-500 leading-relaxed">シャワーの利用方法や混雑状況をチェックして、快適にご利用いただけます。</p>

                    <div class="mt-6 flex items-end justify-between gap-2">
                        <a href="#" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-blue border border-brand-blue/40 rounded-full px-5 py-2 hover:bg-brand-blue hover:text-white hover:border-brand-blue transition-all duration-200 shrink-0">
                            詳しく見る
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 6l6 6-6 6"/>
                            </svg>
                        </a>

                        <!-- Penguin: enjoying a shower -->
                        <svg width="92" height="104" viewBox="0 0 92 104" fill="none" aria-hidden="true" class="shrink-0 -mb-1">
                            <path d="M46 6 v6" stroke="#94a3b8" stroke-width="3" stroke-linecap="round"/>
                            <rect x="30" y="12" width="32" height="9" rx="4.5" fill="#cbd5e1"/>
                            <g stroke="#38bdf8" stroke-width="2.4" stroke-linecap="round">
                                <path class="pg-drop"    d="M34 24 v6"/>
                                <path class="pg-drop d2" d="M42 24 v6"/>
                                <path class="pg-drop d3" d="M50 24 v6"/>
                                <path class="pg-drop d4" d="M58 24 v6"/>
                            </g>
                            <path class="pg-steam" d="M68 44 q3 -4 0 -8" stroke="#bae6fd" stroke-width="2" stroke-linecap="round" fill="none"/>
                            <path class="pg-steam" style="animation-delay:1.6s" d="M24 48 q-3 -4 0 -8" stroke="#bae6fd" stroke-width="2" stroke-linecap="round" fill="none"/>
                            <g class="pg-body">
                                <ellipse cx="46" cy="70" rx="21" ry="25" fill="#334155"/>
                                <ellipse cx="46" cy="75" rx="14" ry="18" fill="#f8fafc"/>
                                <path d="M26 62 Q16 54 20 46 Q28 52 30 60 Z" fill="#334155"/>
                                <path d="M66 62 Q76 54 72 46 Q64 52 62 60 Z" fill="#334155"/>
                                <path d="M38 58 q3 3 6 0" stroke="#0f172a" stroke-width="2" stroke-linecap="round" fill="none"/>
                                <path d="M50 58 q3 3 6 0" stroke="#0f172a" stroke-width="2" stroke-linecap="round" fill="none"/>
                                <path d="M43 65 L46 69 L49 65 Z" fill="#f5a03a"/>
                                <circle cx="35" cy="64" r="2.6" fill="#fda4af" opacity=".7"/>
                                <circle cx="57" cy="64" r="2.6" fill="#fda4af" opacity=".7"/>
                                <path d="M38 94 l-4 5 h8 Z" fill="#f5a03a"/>
                                <path d="M54 94 l-4 5 h8 Z" fill="#f5a03a"/>
                            </g>
                            <g fill="#e0f2fe" stroke="#7dd3fc" stroke-width="1.2">
                                <circle class="pg-bubble"    cx="22" cy="80" r="4"/>
                                <circle class="pg-bubble b2" cx="72" cy="84" r="3"/>
                                <circle class="pg-bubble b3" cx="66" cy="72" r="2.5"/>
                            </g>
                        </svg>
                    </div>
                </div>

                <!-- English learning -->
                <div class="group relative bg-white rounded-[24px] shadow-card hover:shadow-card-hover hover:-translate-y-1.5 transition-all duration-300 p-9 pb-7 w-full sm:w-[350px] text-left overflow-hidden">
                    <div class="absolute top-0 inset-x-0 h-1.5 rounded-t-[24px] bg-gradient-to-r from-amber-400 to-orange-500"></div>
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 flex items-center justify-center mb-6 group-hover:scale-105 transition-transform duration-300">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 6a2 2 0 012-2h5.5v15H6a2 2 0 00-2 2V6z"/>
                            <path d="M20 6a2 2 0 00-2-2h-5.5v15H18a2 2 0 012 2V6z"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-orange-600 tracking-widest">English Learning</p>
                    <h3 class="mt-1.5 font-bold text-xl text-slate-800">英語学習</h3>
                    <p class="mt-3 text-sm text-slate-500 leading-relaxed">TOEIC・IELTS対策を中心に、語彙・文法・リスニング・リーディングを効率的に学習。</p>

                    <div class="mt-6 flex items-end justify-between gap-2">
                        <a href="#" class="inline-flex items-center gap-2 text-sm font-semibold text-orange-600 border border-orange-600/50 rounded-full px-5 py-2 hover:bg-orange-600 hover:text-white hover:border-orange-600 transition-all duration-200 shrink-0">
                            詳しく見る
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
                        </a>

                        <!-- Otter: typing lesson -->
                        <svg width="104" height="100" viewBox="0 0 104 100" fill="none" aria-hidden="true" class="shrink-0 -mb-1">
                            <text class="ot-spark" x="14" y="34" font-size="13" font-weight="700" fill="#f97316" font-family="Poppins, sans-serif">ABC</text>
                            <text class="ot-spark sp2" x="76" y="30" font-size="12" font-weight="700" fill="#f59e0b" font-family="Poppins, sans-serif">A+</text>
                            <path class="ot-tail" d="M76 82 Q92 78 96 66 Q88 70 78 74 Z" fill="#8b5e3c"/>
                            <ellipse cx="58" cy="74" rx="22" ry="18" fill="#a06b45"/>
                            <ellipse cx="56" cy="78" rx="14" ry="11" fill="#e8cfa8"/>
                            <g class="ot-head">
                                <circle cx="46" cy="46" r="17" fill="#a06b45"/>
                                <circle cx="35" cy="33" r="4.5" fill="#a06b45"/>
                                <circle cx="57" cy="33" r="4.5" fill="#a06b45"/>
                                <circle cx="35" cy="33" r="2" fill="#e8cfa8"/>
                                <circle cx="57" cy="33" r="2" fill="#e8cfa8"/>
                                <ellipse cx="46" cy="52" rx="10" ry="8" fill="#e8cfa8"/>
                                <circle cx="40" cy="44" r="2.2" fill="#1f2937"/>
                                <circle cx="52" cy="44" r="2.2" fill="#1f2937"/>
                                <circle cx="40.8" cy="43.2" r="0.7" fill="#fff"/>
                                <circle cx="52.8" cy="43.2" r="0.7" fill="#fff"/>
                                <ellipse cx="46" cy="50" rx="2.8" ry="2" fill="#4b2e1e"/>
                                <path d="M46 52 q0 3 -3 3 M46 52 q0 3 3 3" stroke="#4b2e1e" stroke-width="1.4" stroke-linecap="round" fill="none"/>
                                <g stroke="#cbd5e1" stroke-width="1.1" stroke-linecap="round">
                                    <path d="M34 49 L26 47 M34 52 L26 53"/>
                                    <path d="M58 49 L66 47 M58 52 L66 53"/>
                                </g>
                            </g>
                            <path d="M12 60 L44 60 L44 84 L12 84 Z" fill="#475569" transform="skewX(-6)"/>
                            <path d="M15 63 L41 63 L41 81 L15 81 Z" fill="#fffbeb" transform="skewX(-6)"/>
                            <g stroke="#f97316" stroke-width="2" stroke-linecap="round" transform="skewX(-6)">
                                <path class="ot-screen-line" d="M17 68 h22"/>
                                <path class="ot-screen-line sl2" d="M17 74 h16"/>
                            </g>
                            <path d="M6 84 h44 l4 8 H4 Z" fill="#64748b"/>
                            <g stroke="#94a3b8" stroke-width="1">
                                <path d="M12 87 h32 M11 89.5 h34"/>
                            </g>
                            <ellipse class="ot-paw"     cx="36" cy="86" rx="5" ry="3.5" fill="#8b5e3c"/>
                            <ellipse class="ot-paw pw2" cx="48" cy="86" rx="5" ry="3.5" fill="#8b5e3c"/>
                        </svg>
                    </div>
                </div>

            </div>

            <!-- Tip banner -->
            <div class="mt-12 inline-flex items-center gap-3 bg-white/80 backdrop-blur border border-amber-200/70 rounded-full px-7 py-3.5 text-sm text-slate-600 shadow-soft">
                <span class="w-7 h-7 rounded-full bg-gradient-to-br from-brand-yellow/30 to-amber-50 flex items-center justify-center shrink-0">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2"><path d="M9 18h6M10 22h4M12 2a6 6 0 00-4 10.4c.6.5 1 1.3 1 2.1v.5h6v-.5c0-.8.4-1.6 1-2.1A6 6 0 0012 2z"/></svg>
                </span>
                学びの積み重ねが、未来の可能性を広げます。<strong class="font-bold text-slate-700">Kredo Plus</strong>で、あなたの一歩を応援します。
            </div>
        </div>
    </section>

    <!-- Explore Cebu -->
    <section class="relative bg-gradient-to-b from-sky-50 via-white to-sky-50 pt-24 pb-20 overflow-hidden">

        <!-- Background: adventure motif (covers through Location) -->
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">

            <svg class="adv-sun absolute right-[6%] top-8 w-16 h-16 hidden sm:block" viewBox="0 0 64 64">
                <circle cx="32" cy="32" r="13" fill="#fcd34d"/>
                <circle cx="32" cy="32" r="13" fill="#f5b52e" opacity=".5"/>
                <g stroke="#fcd34d" stroke-width="2.5" stroke-linecap="round">
                    <path d="M32 8v7M32 49v7M8 32h7M49 32h7M15 15l5 5M44 44l5 5M49 15l-5 5M20 44l-5 5"/>
                </g>
            </svg>

            <div class="adv-cloud hidden sm:block" style="top:10%">
                <svg width="120" height="42" viewBox="0 0 120 42">
                    <ellipse cx="38" cy="28" rx="26" ry="12" fill="#ffffff"/>
                    <ellipse cx="62" cy="20" rx="22" ry="13" fill="#ffffff"/>
                    <ellipse cx="88" cy="28" rx="24" ry="11" fill="#ffffff"/>
                    <ellipse cx="60" cy="30" rx="42" ry="10" fill="#f0f9ff"/>
                </svg>
            </div>
            <div class="adv-cloud c2 hidden sm:block" style="top:34%">
                <svg width="90" height="34" viewBox="0 0 90 34" opacity=".85">
                    <ellipse cx="30" cy="22" rx="20" ry="9" fill="#ffffff"/>
                    <ellipse cx="52" cy="16" rx="18" ry="10" fill="#ffffff"/>
                    <ellipse cx="66" cy="23" rx="16" ry="8" fill="#f0f9ff"/>
                </svg>
            </div>

            <div class="adv-plane hidden sm:block">
                <svg width="170" height="60" viewBox="0 0 170 60" fill="none">
                    <path class="adv-trail" d="M0 34 H86" stroke="#bae6fd" stroke-width="2.5"/>
                    <g>
                        <path d="M92 30 Q96 24 108 23 L150 23 Q162 24 166 29 Q162 34 150 35 L108 35 Q96 34 92 30 Z" fill="#ffffff" stroke="#cbd5e1" stroke-width="1.2"/>
                        <path d="M158 26 q5 1 6 3 q-1 2 -6 3 Z" fill="#7dd3fc"/>
                        <path d="M96 24 L88 12 L100 14 L104 23 Z" fill="#e05237"/>
                        <path d="M94 34 L88 40 L100 38 L102 34 Z" fill="#ef8a75"/>
                        <path d="M118 28 L96 46 L112 44 L128 32 Z" fill="#2f5fdb"/>
                        <path d="M122 26 L112 14 L122 16 L130 25 Z" fill="#4f7df0"/>
                        <ellipse cx="116" cy="40" rx="6" ry="3.5" fill="#94a3b8"/>
                        <g fill="#7dd3fc">
                            <circle cx="116" cy="28" r="1.6"/><circle cx="123" cy="28" r="1.6"/>
                            <circle cx="130" cy="28" r="1.6"/><circle cx="137" cy="28" r="1.6"/>
                            <circle cx="144" cy="28" r="1.6"/>
                        </g>
                        <path d="M104 31 H158" stroke="#f5b52e" stroke-width="1.6"/>
                    </g>
                </svg>
            </div>

            <div class="adv-birds hidden sm:block">
                <svg width="100" height="44" viewBox="0 0 100 44" fill="none">
                    <g>
                        <ellipse cx="18" cy="22" rx="6" ry="2.8" fill="#e2e8f0"/>
                        <path class="adv-wing" d="M14 21 Q10 12 4 11 Q10 18 13 22 Z" fill="#f8fafc" stroke="#cbd5e1" stroke-width=".8"/>
                        <path class="adv-wing" style="animation-delay:.1s" d="M22 21 Q26 12 32 11 Q26 18 23 22 Z" fill="#f8fafc" stroke="#cbd5e1" stroke-width=".8"/>
                        <path d="M24 22 l3 1 -3 1 Z" fill="#f5a03a"/>
                    </g>
                    <g transform="translate(44 -8)">
                        <ellipse cx="18" cy="22" rx="5" ry="2.4" fill="#e2e8f0"/>
                        <path class="adv-wing" style="animation-delay:.25s" d="M14 21 Q10 13 5 12 Q10 18 13 22 Z" fill="#f8fafc" stroke="#cbd5e1" stroke-width=".8"/>
                        <path class="adv-wing" style="animation-delay:.35s" d="M22 21 Q26 13 31 12 Q26 18 23 22 Z" fill="#f8fafc" stroke="#cbd5e1" stroke-width=".8"/>
                        <path d="M23 22 l3 1 -3 1 Z" fill="#f5a03a"/>
                    </g>
                </svg>
            </div>

            <div class="adv-jeepney hidden md:block">
                <svg width="150" height="62" viewBox="0 0 150 62" fill="none">
                    <g class="adv-jeepney-body">
                        <path d="M8 20 Q8 14 14 14 H96 L122 26 Q126 28 126 32 V42 H8 Z" fill="#ffffff" stroke="#94a3b8" stroke-width="1.4"/>
                        <path d="M12 14 H98 L100 9 H14 Q12 9 12 14 Z" fill="#e05237"/>
                        <g fill="#bfe3ff" stroke="#94a3b8" stroke-width=".8">
                            <rect x="16" y="17" width="14" height="10" rx="1.5"/>
                            <rect x="34" y="17" width="14" height="10" rx="1.5"/>
                            <rect x="52" y="17" width="14" height="10" rx="1.5"/>
                            <rect x="70" y="17" width="14" height="10" rx="1.5"/>
                            <path d="M96 17 L114 26 H96 Z"/>
                        </g>
                        <rect x="10" y="30" width="112" height="3" fill="#e05237"/>
                        <rect x="10" y="33.5" width="112" height="2.5" fill="#f5b52e"/>
                        <rect x="10" y="36.5" width="112" height="2.5" fill="#5eab35"/>
                        <rect x="124" y="38" width="8" height="4" rx="2" fill="#94a3b8"/>
                        <circle cx="124" cy="32" r="2.4" fill="#fcd34d"/>
                        <circle class="pg-steam" cx="4" cy="40" r="3" fill="#e2e8f0"/>
                    </g>
                    <g class="adv-wheel">
                        <circle cx="34" cy="46" r="9" fill="#1f2937"/>
                        <circle cx="34" cy="46" r="4.5" fill="#cbd5e1"/>
                        <path d="M34 41.5 v9 M29.5 46 h9" stroke="#64748b" stroke-width="1.4"/>
                    </g>
                    <g class="adv-wheel" style="animation-delay:.1s">
                        <circle cx="100" cy="46" r="9" fill="#1f2937"/>
                        <circle cx="100" cy="46" r="4.5" fill="#cbd5e1"/>
                        <path d="M100 41.5 v9 M95.5 46 h9" stroke="#64748b" stroke-width="1.4"/>
                    </g>
                </svg>
            </div>

            <div class="adv-traveler hidden md:block">
                <svg width="46" height="72" viewBox="0 0 46 72" fill="none">
                    <g class="adv-walk-bob">
                        <path class="adv-arm am2" d="M20 30 L13 42" stroke="#e8b48a" stroke-width="4" stroke-linecap="round"/>
                        <rect x="24" y="22" width="13" height="19" rx="4" fill="#e05237"/>
                        <rect x="27" y="26" width="7" height="6" rx="1.5" fill="#f5b52e"/>
                        <path d="M25 24 q-2 8 0 15" stroke="#b53c25" stroke-width="1.5" fill="none"/>
                        <path d="M15 24 Q14 22 17 21 L24 21 Q27 22 26 26 L25 42 L16 42 Z" fill="#2f5fdb"/>
                        <circle cx="19" cy="13" r="6.5" fill="#f0c29b"/>
                        <path d="M12 11 Q13 5 20 5 Q26 5 26 10 L26 12 Q20 9 12 11 Z" fill="#5eab35"/>
                        <path d="M11.5 11.5 L8 12.5" stroke="#5eab35" stroke-width="3" stroke-linecap="round"/>
                        <circle cx="15.5" cy="13.5" r="1" fill="#1f2937"/>
                        <path class="adv-arm" d="M20 30 L27 40" stroke="#f0c29b" stroke-width="4" stroke-linecap="round"/>
                        <path class="adv-leg"     d="M19 42 L15 60 L13 60" stroke="#475569" stroke-width="5" stroke-linecap="round" fill="none"/>
                        <path class="adv-leg lg2" d="M22 42 L26 60 L28 60" stroke="#334155" stroke-width="5" stroke-linecap="round" fill="none"/>
                    </g>
                </svg>
            </div>

            <svg class="absolute left-4 bottom-0 w-64 opacity-90 hidden sm:block" viewBox="0 0 260 160" fill="none">
                <path d="M38 152 Q40 110 42 92" stroke="#a1887f" stroke-width="5" stroke-linecap="round"/>
                <g fill="#7cb85c">
                    <path d="M42 92 Q26 74 8 78 Q22 90 42 94 Z"/>
                    <path d="M42 92 Q58 72 78 78 Q62 90 42 94 Z"/>
                    <path d="M42 90 Q34 68 42 54 Q50 70 44 90 Z"/>
                    <path d="M42 90 Q56 74 70 72 Q58 86 44 92 Z" opacity=".85"/>
                    <path d="M42 90 Q28 76 16 74 Q28 88 40 92 Z" opacity=".85"/>
                </g>
                <circle cx="38" cy="94" r="3.5" fill="#8d6e63"/>
                <circle cx="46" cy="96" r="3.5" fill="#8d6e63"/>
                <rect x="92" y="112" width="70" height="42" rx="2" fill="#fef3c7" stroke="#d6bb86" stroke-width="1.5"/>
                <path d="M86 112 l41 -24 41 24 Z" fill="#e05237" stroke="#b53c25" stroke-width="1.5"/>
                <rect x="112" y="130" width="16" height="24" fill="#8d6e63"/>
                <rect x="138" y="126" width="14" height="12" fill="#bfe3ff" stroke="#94a3b8"/>
                <rect x="98" y="126" width="8" height="3" fill="#5eab35"/>
                <rect x="98" y="131" width="8" height="3" fill="#f5b52e"/>
            </svg>

            <svg class="absolute right-0 bottom-0 w-72 opacity-70 hidden sm:block" viewBox="0 0 300 150" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round">
                <path d="M20 110h260" stroke-width="3"/>
                <path d="M50 110V70M100 110V50M150 110V40M200 110V50M250 110V70"/>
                <path d="M40 60Q150 -10 260 60"/>
                <path d="M10 122 q10 -4 20 0 t20 0 t20 0 t20 0 t20 0 t20 0 t20 0 t20 0 t20 0 t20 0 t20 0 t20 0 t20 0" stroke="#7dd3fc" stroke-width="1.8"/>
            </svg>

            <svg class="adv-pin absolute left-[13%] top-24 w-9 h-9 hidden md:block" viewBox="0 0 24 24" fill="#e05237">
                <path d="M12 2a7 7 0 00-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 00-7-7zm0 9.5A2.5 2.5 0 1112 6.5a2.5 2.5 0 010 5z"/>
            </svg>
            <svg class="absolute right-[9%] top-40 w-14 h-14 hidden md:block" viewBox="0 0 60 60">
                <rect x="6" y="6" width="7" height="7" rx="1" fill="#e05237" transform="rotate(20 9 9)"/>
                <rect x="28" y="2" width="7" height="7" rx="1" fill="#5eab35" transform="rotate(-15 31 5)"/>
                <rect x="44" y="20" width="7" height="7" rx="1" fill="#f5b52e" transform="rotate(30 47 23)"/>
                <rect x="16" y="32" width="7" height="7" rx="1" fill="#2f5fdb" transform="rotate(-25 19 35)"/>
            </svg>
        </div>

        <div class="relative max-w-[1140px] mx-auto px-6 text-center">
            <h2 class="font-display font-extrabold text-5xl sm:text-6xl tracking-tight">
                <span class="text-slate-800">Explore</span> <span class="wordmark-kredo">Cebu</span>
            </h2>
            <div class="kredo-bar mt-4 mx-auto w-32 h-1.5 rounded-full"></div>
            <p class="mt-7 text-slate-600 leading-loose">
                学ぶだけではない、留学生活も充実。<br class="hidden sm:block">
                現地でおすすめのお店や施設、観光スポットをチェックしましょう。
            </p>
        </div>

        <!-- Post cards -->
        <div class="relative max-w-[1140px] mx-auto mt-14 px-6">
            <div class="flex overflow-x-auto snap-x snap-mandatory gap-6 pb-8 scrollbar-hide">

                <article class="group snap-center min-w-[300px] md:min-w-[350px] bg-white rounded-[20px] overflow-hidden shadow-card hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="https://picsum.photos/seed/sisig-food/500/340" alt="Best Sisig near school" class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105">
                        <span class="absolute top-3.5 left-3.5 bg-brand-red/90 backdrop-blur text-white text-xs font-bold px-3.5 py-1.5 rounded-full">Carinderia</span>
                        <span class="absolute bottom-3.5 right-3.5 bg-white/95 text-slate-800 text-xs font-bold px-3.5 py-1.5 rounded-full shadow-sm">150 PHP</span>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-bold text-slate-800">Best Sisig near school</h3>
                            <span class="flex items-center gap-1 text-sm text-amber-500 font-semibold shrink-0">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.6 7.1.6-5.4 4.7 1.6 7-6.2-3.7-6.2 3.7 1.6-7L2 9.2l7.1-.6z"/></svg>
                                4.8
                            </span>
                        </div>
                        <div class="mt-5 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 text-xs font-bold flex items-center justify-center ring-2 ring-white shadow-sm">ML</span>
                                <div class="text-xs text-slate-400 leading-tight">
                                    <p class="font-semibold text-slate-700 text-[13px]">Mateo L.</p>
                                    <p class="mt-0.5">2 hours ago</p>
                                </div>
                            </div>
                            <button class="text-brand-blue/70 hover:text-brand-blue transition-colors" aria-label="共有"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="M8.6 10.6L15.4 6.4M8.6 13.4l6.8 4.2"/></svg></button>
                        </div>
                    </div>
                </article>

                <article class="group snap-center min-w-[300px] md:min-w-[350px] bg-white rounded-[20px] overflow-hidden shadow-card hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="https://picsum.photos/seed/blue-lagoon-pool/500/340" alt="Day Pass at Blue Lagoon" class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105">
                        <span class="absolute top-3.5 left-3.5 bg-brand-green/90 backdrop-blur text-white text-xs font-bold px-3.5 py-1.5 rounded-full">Travel</span>
                        <span class="absolute bottom-3.5 right-3.5 bg-white/95 text-slate-800 text-xs font-bold px-3.5 py-1.5 rounded-full shadow-sm">500 PHP</span>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-bold text-slate-800">Day Pass at Blue Lagoon</h3>
                            <span class="flex items-center gap-1 text-sm text-amber-500 font-semibold shrink-0">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.6 7.1.6-5.4 4.7 1.6 7-6.2-3.7-6.2 3.7 1.6-7L2 9.2l7.1-.6z"/></svg>
                                4.9
                            </span>
                        </div>
                        <div class="mt-5 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 text-xs font-bold flex items-center justify-center ring-2 ring-white shadow-sm">SC</span>
                                <div class="text-xs text-slate-400 leading-tight">
                                    <p class="font-semibold text-slate-700 text-[13px]">Sophia C.</p>
                                    <p class="mt-0.5">5 hours ago</p>
                                </div>
                            </div>
                            <button class="text-brand-blue/70 hover:text-rose-500 transition-colors" aria-label="お気に入り"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 21s-7-4.35-9.5-8.5C.7 9 2 5.5 5.3 5c2-.3 3.5.8 4.7 2.4C11.2 5.8 12.7 4.7 14.7 5c3.3.5 4.6 4 3.8 7.5C16 16.65 12 21 12 21z"/></svg></button>
                        </div>
                    </div>
                </article>

                <article class="group snap-center min-w-[300px] md:min-w-[350px] bg-white rounded-[20px] overflow-hidden shadow-card hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="https://picsum.photos/seed/laundry-machines/500/340" alt="Quick Dry Laundry Shop" class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-teal-700/25 mix-blend-multiply"></div>
                        <span class="absolute top-3.5 left-3.5 bg-brand-blue/90 backdrop-blur text-white text-xs font-bold px-3.5 py-1.5 rounded-full">Laundry</span>
                        <span class="absolute bottom-3.5 right-3.5 bg-white/95 text-slate-800 text-xs font-bold px-3.5 py-1.5 rounded-full shadow-sm">35 PHP/kg</span>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-bold text-slate-800">Quick Dry Laundry Shop</h3>
                            <span class="flex items-center gap-1 text-sm text-amber-500 font-semibold shrink-0">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.6 7.1.6-5.4 4.7 1.6 7-6.2-3.7-6.2 3.7 1.6-7L2 9.2l7.1-.6z"/></svg>
                                4.5
                            </span>
                        </div>
                        <div class="mt-5 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 text-xs font-bold flex items-center justify-center ring-2 ring-white shadow-sm">JR</span>
                                <div class="text-xs text-slate-400 leading-tight">
                                    <p class="font-semibold text-slate-700 text-[13px]">Juan R.</p>
                                    <p class="mt-0.5">1 day ago</p>
                                </div>
                            </div>
                            <button class="text-brand-blue/70 hover:text-brand-blue transition-colors" aria-label="その他"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="5" r="1.4" fill="currentColor"/><circle cx="12" cy="12" r="1.4" fill="currentColor"/><circle cx="12" cy="19" r="1.4" fill="currentColor"/></svg></button>
                        </div>
                    </div>
                </article>

            </div>
        </div>

        <!-- School Location -->
        <div class="relative max-w-[1140px] mx-auto px-6 mt-10 mb-16">
            <div class="bg-white rounded-[20px] shadow-card p-6 md:p-8 flex flex-col md:flex-row gap-8 items-center">

                <div class="w-full md:w-1/2 h-[300px] rounded-[16px] overflow-hidden shadow-soft">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3925.367584168285!2d123.9067527756858!3d10.329718467554015!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a99907372b64d7%3A0x63390c58a5e55e56!2sSkyrise%204%2C%20Cebu%20City%2C%20Cebu!5e0!3m2!1sja!2sph!4v1715000000000!5m2!1sja!2sph"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="School Location Map">
                    </iframe>
                </div>

                <div class="w-full md:w-1/2">
                    <h3 class="font-display font-bold text-2xl text-slate-800">School Location</h3>
                    <div class="kredo-bar mt-2 w-16 h-1 rounded-full"></div>
                    <p class="mt-4 text-slate-600 leading-relaxed">
                        Kredoのキャンパスは、ITパーク内に位置するSkyrise 4の7階にあります。周辺には多くのカフェや飲食店があり、勉強にも生活にも最適な環境です。また、KredoはQQ Englishと協業してIT×英語留学を提供しており、英語レッスンはQQ Englishの校舎で受講します。
                    </p>

                    <div class="mt-6 flex items-start gap-3">
                        <svg class="w-6 h-6 text-brand-red shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span class="text-slate-700 font-medium">7th Floor, Skyrise 4, Apas, Cebu City, 6000 Cebu</span>
                    </div>

                    <a href="https://www.google.co.jp/maps/place/CampusTop(QQEnglish)+IT+Park+Campus/@10.330136,123.9036374,16z/data=!3m1!4b1!4m6!3m5!1s0x33a9992189a343c3:0xa7758b38dbbe1750!8m2!3d10.330136!4d123.9062123!16s%2Fg%2F11c3k6h1kt?entry=ttu&g_ep=EgoyMDI2MDYyOS4wIKXMDSoASAFQAw%3D%3D" target="_blank" rel="noopener" class="mt-8 inline-flex items-center gap-2 bg-brand-blue text-white font-semibold px-8 py-3 rounded-full hover:bg-indigo-700 transition-colors shadow-soft">
                        Googleマップで開く
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14L21 3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
