@extends('layouts.app')

@if($showIntro ?? false)
    @section('intro', true)
@endif

@section('content')
    {{-- シャワーページにアクセスする際に性別登録のmodalが表示される（未登録の場合） --}}
    @if (session('showGenderModal'))
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('genderModal').open = true;
            });
        </script>
    @endif
    <div x-show="$store.genderModal.open" x-cloak
    class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 px-4">
            <div class="relative bg-white rounded-[24px] shadow-card p-8 w-full max-w-sm overflow-hidden">
                {{-- 上部のグラデーションライン --}}
                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-sky-400 to-brand-blue"></div>


                <form method="POST" action="{{ route('gender.store') }}">
                    @csrf
                    <h2 class="font-display font-bold text-xl text-blue-700 text-center">ユーザー情報登録</h2>

                    <p class="mt-4 font-semibold text-lg text-blue-950">お住まいの寮</p>
                    <p class="text-sm text-red-500">※登録後は変更できません</p>

                    <div class="flex gap-3 mt-4">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="gender" value="male" required class="peer sr-only">
                            <div class="text-center border-2 border-slate-200 rounded-2xl py-4 text-sm font-semibold text-slate-600 transition-all peer-checked:border-brand-blue peer-checked:bg-sky-50 peer-checked:text-brand-blue">
                                男子寮
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="gender" value="female" required class="peer sr-only">
                            <div class="text-center border-2 border-slate-200 rounded-2xl py-4 text-sm font-semibold text-slate-600 transition-all peer-checked:border-brand-blue peer-checked:bg-sky-50 peer-checked:text-brand-blue">
                                女子寮
                            </div>
                        </label>
                    </div>

                    <p class="mt-6 font-semibold text-lg text-blue-950">シャワーのお好み</p>

                    <p class="text-slate-500 text-sm mt-3">温度</p>
                    <div class="grid grid-cols-4 gap-2 my-2 text-sm font-semibold text-slate-500">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="temp" id="cold" value="冷たい">
                            <span>冷たい</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="temp" id="luke" value="ぬるい">
                            <span>ぬるい</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="temp" id="warm" value="温かい" checked>
                            <span>温かい</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="temp" id="hot" value="熱い">
                            <span>熱い</span>
                        </label>
                    </div>

                    <p class="text-sm text-slate-500 mt-3">水圧</p>
                    <div class="grid grid-cols-4 gap-2 my-2 text-sm font-semibold text-slate-500">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pressure" id="weak" value="弱い">
                            <span>弱い</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pressure" id="medium" value="普通" checked>
                            <span>普通</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pressure" id="strong" value="強い">
                            <span>強い</span>
                        </label>
                    </div>

                    <div class="mt-7 flex justify-center gap-3">
                        <button type="button" @click="$store.genderModal.open = false"
                                class="text-sm font-semibold text-slate-400 px-5 py-2.5 rounded-full hover:bg-slate-100 transition-colors">
                            キャンセル
                        </button>
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-brand-blue text-white text-sm font-semibold px-6 py-2.5 rounded-full hover:bg-indigo-700 transition-colors shadow-soft">
                            登録する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    <!-- Hero -->
    <section class="relative bg-gradient-to-b from-sky-50 via-white to-white pt-8 pb-16 overflow-hidden">

        {{-- 装飾: 左右の余白にヤシの葉と南国フラワー、紙吹雪ドット（Kredoブランドカラー） --}}
        <div aria-hidden="true" class="hidden 2xl:block absolute left-8 top-6 w-28 pointer-events-none">
            <svg viewBox="0 0 120 260" fill="none">
                <path d="M30 10C50 40 58 90 44 140C34 110 20 70 6 40C16 30 22 18 30 10Z" fill="#5eab35" fill-opacity="0.75" />
                <path d="M40 4C66 26 82 66 78 118C64 92 46 58 26 30C31 20 35 12 40 4Z" fill="#7cc24a" fill-opacity="0.75" />
                <path d="M18 20C36 34 50 66 50 104C36 84 20 58 4 40C9 32 13 26 18 20Z" fill="#8fce54" fill-opacity="0.65" />
                <g transform="translate(30 172)">
                    <path d="M0-15C5-15 8-8 4-2C8 2 5 9 0 9C-5 9-8 2-4-2C-8-8-5-15 0-15Z" fill="#fff" transform="rotate(0)" />
                    <path d="M0-15C5-15 8-8 4-2C8 2 5 9 0 9C-5 9-8 2-4-2C-8-8-5-15 0-15Z" fill="#fff" transform="rotate(72)" />
                    <path d="M0-15C5-15 8-8 4-2C8 2 5 9 0 9C-5 9-8 2-4-2C-8-8-5-15 0-15Z" fill="#fff" transform="rotate(144)" />
                    <path d="M0-15C5-15 8-8 4-2C8 2 5 9 0 9C-5 9-8 2-4-2C-8-8-5-15 0-15Z" fill="#fff" transform="rotate(216)" />
                    <path d="M0-15C5-15 8-8 4-2C8 2 5 9 0 9C-5 9-8 2-4-2C-8-8-5-15 0-15Z" fill="#fff" transform="rotate(288)" />
                    <circle r="3.5" fill="#f5b52e" />
                </g>
                <circle cx="14" cy="220" r="4" fill="#2f5fdb" fill-opacity="0.55" />
                <circle cx="46" cy="238" r="3" fill="#e05237" fill-opacity="0.55" />
                <circle cx="24" cy="252" r="2.5" fill="#f5b52e" fill-opacity="0.6" />
            </svg>
        </div>

        <div aria-hidden="true" class="hidden 2xl:block absolute right-8 top-16 w-28 pointer-events-none">
            <svg viewBox="0 0 120 260" fill="none">
                <path d="M90 10C70 40 62 90 76 140C86 110 100 70 114 40C104 30 98 18 90 10Z" fill="#2f5fdb" fill-opacity="0.7" />
                <path d="M80 4C54 26 38 66 42 118C56 92 74 58 94 30C89 20 85 12 80 4Z" fill="#5b86e6" fill-opacity="0.7" />
                <path d="M102 20C84 34 70 66 70 104C84 84 100 58 116 40C111 32 107 26 102 20Z" fill="#7ea3ef" fill-opacity="0.6" />
                <g transform="translate(90 168)">
                    <path d="M0-14C5-14 7-8 4-2C7 2 5 8 0 8C-5 8-7 2-4-2C-7-8-5-14 0-14Z" fill="#fff" transform="rotate(0)" />
                    <path d="M0-14C5-14 7-8 4-2C7 2 5 8 0 8C-5 8-7 2-4-2C-7-8-5-14 0-14Z" fill="#fff" transform="rotate(72)" />
                    <path d="M0-14C5-14 7-8 4-2C7 2 5 8 0 8C-5 8-7 2-4-2C-7-8-5-14 0-14Z" fill="#fff" transform="rotate(144)" />
                    <path d="M0-14C5-14 7-8 4-2C7 2 5 8 0 8C-5 8-7 2-4-2C-7-8-5-14 0-14Z" fill="#fff" transform="rotate(216)" />
                    <path d="M0-14C5-14 7-8 4-2C7 2 5 8 0 8C-5 8-7 2-4-2C-7-8-5-14 0-14Z" fill="#fff" transform="rotate(288)" />
                    <circle r="3.2" fill="#e05237" />
                </g>
                <circle cx="100" cy="212" r="4" fill="#5eab35" fill-opacity="0.55" />
                <circle cx="70" cy="232" r="3" fill="#f5b52e" fill-opacity="0.6" />
                <circle cx="94" cy="248" r="2.5" fill="#2f5fdb" fill-opacity="0.55" />
            </svg>
        </div>

        <div class="max-w-[1140px] mx-auto px-6">

            <!-- Hero photo slider -->
            <div class="relative rounded-[28px] overflow-hidden shadow-card h-[420px] sm:h-[480px]"
                 x-data="{
                    slides: [
                        '{{ asset('images/dashboard/hero-1.jpg') }}',
                        '{{ asset('images/dashboard/hero-2.jpg') }}',
                        '{{ asset('images/dashboard/hero-3.jpg') }}',
                        '{{ asset('images/dashboard/hero-4.jpg') }}',
                        '{{ asset('images/dashboard/hero-5.jpg') }}',
                    ],
                    current: 0,
                    next() { this.current = (this.current + 1) % this.slides.length },
                    prev() { this.current = (this.current - 1 + this.slides.length) % this.slides.length },
                    goTo(i) { this.current = i },
                 }"
                 x-init="setInterval(() => next(), 6000)">

                <template x-for="(slide, index) in slides" :key="index">
                    <img :src="slide"
                         x-show="current === index"
                         x-transition:enter="transition ease-out duration-700"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0 w-full h-full object-cover"
                         alt="Cebu">
                </template>

                {{-- 白いウェーブパネル：写真を透かさず完全に塗りつぶす --}}
                <div class="absolute inset-y-0 left-0 w-full sm:w-[64%] pointer-events-none">
                    <svg viewBox="0 0 800 480" preserveAspectRatio="none" class="w-full h-full">
                        <path d="M0,0 H520 C700,90 340,260 520,480 H0 Z" fill="#ffffff" />
                    </svg>
                </div>

                {{-- 手書き風の挨拶 + タイトル + リード文（下部はイラスト用に余白を多めに確保） --}}
                <div class="absolute inset-x-0 bottom-0 pl-14 sm:pl-16 pr-8 sm:pr-12 pt-8 sm:pt-12 pb-16 sm:pb-24 sm:max-w-md">
                    <p class="flex items-center gap-1.5 -mb-1 text-amber-500 text-3xl sm:text-4xl"
                       style="font-family:'Caveat',cursive;">
                        Welcome back!
                    </p>
                    <h1 class="font-display font-extrabold text-3xl sm:text-4xl leading-tight text-slate-900">
                        {{ Auth::user()->name }}!
                    </h1>
                    <p class="mt-3 text-slate-700 leading-relaxed">今日も素敵な一日を始めましょう！</p>
                    <p class="text-slate-500 text-sm">セブでの学びと生活を、もっと充実させよう。</p>
                </div>

                {{-- 装飾: プルメリアの花 + ヤシの葉（左下、カードの角にかけて配置） --}}
                <div aria-hidden="true" class="absolute left-3 -bottom-1 sm:left-5 sm:bottom-0 w-24 sm:w-32 pointer-events-none">
                    <svg viewBox="0 0 140 140" fill="none">
                        <path d="M10 138C14 100 34 74 62 64C48 84 42 112 44 138Z" fill="#5eab35" fill-opacity="0.9" />
                        <path d="M6 130C20 96 46 78 74 74C56 90 42 112 36 138Z" fill="#7cc24a" fill-opacity="0.9" />
                        <g transform="translate(80 90) scale(1.1)">
                            <path d="M0-16C5-16 8-9 4-3C8 1 5 8 0 8C-5 8-8 1-4-3C-8-9-5-16 0-16Z" fill="#ffffff" />
                            <path d="M0-16C5-16 8-9 4-3C8 1 5 8 0 8C-5 8-8 1-4-3C-8-9-5-16 0-16Z" fill="#ffffff" transform="rotate(72)" />
                            <path d="M0-16C5-16 8-9 4-3C8 1 5 8 0 8C-5 8-8 1-4-3C-8-9-5-16 0-16Z" fill="#ffffff" transform="rotate(144)" />
                            <path d="M0-16C5-16 8-9 4-3C8 1 5 8 0 8C-5 8-8 1-4-3C-8-9-5-16 0-16Z" fill="#ffffff" transform="rotate(216)" />
                            <path d="M0-16C5-16 8-9 4-3C8 1 5 8 0 8C-5 8-8 1-4-3C-8-9-5-16 0-16Z" fill="#ffffff" transform="rotate(288)" />
                            <circle r="3.5" fill="#f5b52e" />
                        </g>
                    </svg>
                </div>

                {{-- 装飾: 淡いバブル --}}
                <div aria-hidden="true" class="hidden sm:flex absolute left-36 bottom-16 items-end gap-3 pointer-events-none">
                    <span class="w-3 h-3 rounded-full bg-sky-200/70"></span>
                    <span class="w-5 h-5 rounded-full bg-sky-100/60"></span>
                </div>

                <button type="button" @click="prev()"
                        class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/70 backdrop-blur-sm flex items-center justify-center text-slate-700 hover:bg-white transition-colors shadow-soft"
                        aria-label="前の写真">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button type="button" @click="next()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/70 backdrop-blur-sm flex items-center justify-center text-slate-700 hover:bg-white transition-colors shadow-soft"
                        aria-label="次の写真">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
                </button>

                <div class="absolute bottom-4 inset-x-0 flex justify-center gap-2">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button type="button" @click="goTo(index)"
                                class="h-2 rounded-full transition-all shadow-sm"
                                :class="current === index ? 'bg-brand-blue w-6' : 'bg-slate-300/80 w-2'"
                                aria-label="スライドに移動"></button>
                    </template>
                </div>
            </div>

            <!-- Feature cards -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Shower Information -->
                @if ($user->gender_locked)
                    <a href="{{ route('shower.entry') }}"
                        class="group relative rounded-[24px] shadow-card hover:shadow-card-hover transition-all duration-300 p-7 overflow-hidden bg-gradient-to-b from-sky-50 to-white block"
                    >
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-sky-400 to-brand-blue"></div>

                    {{-- 装飾: シャワーヘッドのイラスト --}}
                    <div aria-hidden="true" class="absolute -right-2 top-8 w-36 sm:w-40 pointer-events-none">
                        <svg viewBox="0 0 160 190" fill="none">
                            {{-- パイプ --}}
                            <path d="M150 6c0 38-10 52-46 52" stroke="#c3cee0" stroke-width="9" stroke-linecap="round" fill="none" />
                            <path d="M150 6c0 38-10 52-46 52" stroke="#eef2f8" stroke-width="3" stroke-linecap="round" fill="none" />
                            {{-- シャワーヘッド --}}
                            <g transform="translate(60 60)">
                                <ellipse cx="0" cy="16" rx="48" ry="14" fill="#c7d2e0" />
                                <path d="M-48 0a48 24 0 000 24a48 24 0 000-24Z" fill="#eef2f7" />
                                <ellipse cx="0" cy="0" rx="48" ry="16" fill="#dfe6ee" />
                                <ellipse cx="0" cy="0" rx="48" ry="16" fill="none" stroke="#c3cee0" stroke-width="2" />
                                <g fill="#a9b9cf">
                                    <circle cx="-26" cy="1" r="2" />
                                    <circle cx="-9" cy="4" r="2" />
                                    <circle cx="9" cy="4" r="2" />
                                    <circle cx="26" cy="1" r="2" />
                                    <circle cx="0" cy="5" r="2" />
                                </g>
                            </g>
                            {{-- 水滴 --}}
                            <g stroke="#8fbdec" stroke-width="2.5" stroke-linecap="round" opacity="0.85">
                                <line x1="20" y1="92" x2="14" y2="112" />
                                <line x1="40" y1="96" x2="37" y2="120" />
                                <line x1="60" y1="98" x2="60" y2="126" />
                                <line x1="80" y1="96" x2="83" y2="120" />
                                <line x1="98" y1="92" x2="104" y2="112" />
                            </g>
                        </svg>
                    </div>

                    {{-- 装飾: 水たまりの波 --}}
                    <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-20 pointer-events-none">
                        <svg viewBox="0 0 400 90" preserveAspectRatio="none" class="w-full h-full">
                            <path d="M0,55 C70,20 150,85 240,45 C310,18 360,50 400,32 L400,90 L0,90 Z" fill="#dbeafe" />
                        </svg>
                    </div>
                    {{-- 装飾: 泡（バブル） --}}
                    <div aria-hidden="true" class="absolute right-8 bottom-6 pointer-events-none">
                        <svg width="60" height="42" viewBox="0 0 60 42" fill="none">
                            <circle cx="10" cy="28" r="6" fill="#bfdbfe" />
                            <circle cx="27" cy="15" r="9" fill="#dbeafe" />
                            <circle cx="45" cy="25" r="4" fill="#bfdbfe" />
                        </svg>
                    </div>

                    <div class="relative w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-100 to-sky-50 ring-1 ring-sky-200 flex items-center justify-center mb-5">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#2f5fdb" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 11h14"/>
                            <path d="M6.5 11a5.5 5.5 0 0111 0"/>
                            <path d="M4 8.5L2.5 7"/>
                            <path d="M8 15l-.6 1.6M12 15.5v2M16 15l.6 1.6"/>
                        </svg>
                    </div>
                    <p class="relative max-w-[60%] text-xs font-bold text-brand-blue tracking-widest">Shower Information</p>
                    <h3 class="relative max-w-[60%] mt-1 font-bold text-lg text-slate-800">シャワー情報</h3>

                    @if ($showerSummary)
                        <div class="relative mt-6 flex items-center gap-3">
                            @if ($showerSummary['recommended_number'])
                                <span class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-brand-blue text-white font-black text-4xl shadow-md shrink-0">
                                    {{ $showerSummary['recommended_number'] }}
                                </span>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[12px] font-bold text-slate-400 ps-1">おすすめシャワー</span>
                                    <div class="flex gap-1.5">
                                        <span class="inline-block rounded-full bg-rose-100 text-rose-600 text-[12px] font-bold px-2 py-0.5">{{ $showerSummary['temperature_label'] }}</span>
                                        <span class="inline-block rounded-full bg-sky-100 text-sky-600 text-[12px] font-bold px-2 py-0.5">{{ $showerSummary['pressure_label'] }}</span>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-slate-400">おすすめデータなし</span>
                            @endif
                        </div>

                        <div class="relative mt-3 flex flex-wrap gap-1.5">
                            @if ($showerSummary['is_full'])
                                <span class="inline-flex items-center gap-1 rounded-full bg-red-50 text-red-600 text-[12px] font-bold px-2.5 py-1">
                                    <span class="material-symbols-outlined !text-sm">error</span>
                                    満室({{ $showerSummary['full_reported_minutes_ago'] }}分前)
                                </span>
                            @endif

                            @if ($showerSummary['broken_numbers']->isNotEmpty())
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 text-amber-700 text-[12px] font-bold px-2.5 py-1">
                                    <span class="material-symbols-outlined !text-sm">build</span>
                                    {{ $showerSummary['broken_numbers']->map(fn ($n) => $n . '番')->implode('、') }}故障中
                                </span>
                            @endif
                        </div>
                    @endif
                    </a>
                @else
                    <a type="button" @click="$store.genderModal.open = true"
                        class="group relative rounded-[24px] shadow-card hover:shadow-card-hover transition-all duration-300 p-7 overflow-hidden bg-gradient-to-b from-sky-50 to-white block w-full text-left"
                    >
                        <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-sky-400 to-brand-blue"></div>

                    {{-- 装飾: シャワーヘッドのイラスト --}}
                    <div aria-hidden="true" class="absolute -right-2 top-8 w-36 sm:w-40 pointer-events-none">
                        <svg viewBox="0 0 160 190" fill="none">
                            {{-- パイプ --}}
                            <path d="M150 6c0 38-10 52-46 52" stroke="#c3cee0" stroke-width="9" stroke-linecap="round" fill="none" />
                            <path d="M150 6c0 38-10 52-46 52" stroke="#eef2f8" stroke-width="3" stroke-linecap="round" fill="none" />
                            {{-- シャワーヘッド --}}
                            <g transform="translate(60 60)">
                                <ellipse cx="0" cy="16" rx="48" ry="14" fill="#c7d2e0" />
                                <path d="M-48 0a48 24 0 000 24a48 24 0 000-24Z" fill="#eef2f7" />
                                <ellipse cx="0" cy="0" rx="48" ry="16" fill="#dfe6ee" />
                                <ellipse cx="0" cy="0" rx="48" ry="16" fill="none" stroke="#c3cee0" stroke-width="2" />
                                <g fill="#a9b9cf">
                                    <circle cx="-26" cy="1" r="2" />
                                    <circle cx="-9" cy="4" r="2" />
                                    <circle cx="9" cy="4" r="2" />
                                    <circle cx="26" cy="1" r="2" />
                                    <circle cx="0" cy="5" r="2" />
                                </g>
                            </g>
                            {{-- 水滴 --}}
                            <g stroke="#8fbdec" stroke-width="2.5" stroke-linecap="round" opacity="0.85">
                                <line x1="20" y1="92" x2="14" y2="112" />
                                <line x1="40" y1="96" x2="37" y2="120" />
                                <line x1="60" y1="98" x2="60" y2="126" />
                                <line x1="80" y1="96" x2="83" y2="120" />
                                <line x1="98" y1="92" x2="104" y2="112" />
                            </g>
                        </svg>
                    </div>

                    {{-- 装飾: 水たまりの波 --}}
                    <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-20 pointer-events-none">
                        <svg viewBox="0 0 400 90" preserveAspectRatio="none" class="w-full h-full">
                            <path d="M0,55 C70,20 150,85 240,45 C310,18 360,50 400,32 L400,90 L0,90 Z" fill="#dbeafe" />
                        </svg>
                    </div>
                    {{-- 装飾: 泡（バブル） --}}
                    <div aria-hidden="true" class="absolute right-8 bottom-6 pointer-events-none">
                        <svg width="60" height="42" viewBox="0 0 60 42" fill="none">
                            <circle cx="10" cy="28" r="6" fill="#bfdbfe" />
                            <circle cx="27" cy="15" r="9" fill="#dbeafe" />
                            <circle cx="45" cy="25" r="4" fill="#bfdbfe" />
                        </svg>
                    </div>

                    <div class="relative w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-100 to-sky-50 ring-1 ring-sky-200 flex items-center justify-center mb-5">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#2f5fdb" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 11h14"/>
                            <path d="M6.5 11a5.5 5.5 0 0111 0"/>
                            <path d="M4 8.5L2.5 7"/>
                            <path d="M8 15l-.6 1.6M12 15.5v2M16 15l.6 1.6"/>
                        </svg>
                    </div>
                    <p class="relative max-w-[60%] text-xs font-bold text-brand-blue tracking-widest">Shower Information</p>
                    <h3 class="relative max-w-[60%] mt-1 font-bold text-lg text-slate-800">シャワー情報</h3>

                    <p class="relative mt-2.5 text-sm text-slate-500 leading-relaxed">お好みの温度や水圧に合った、<br>おすすめのシャワーをご案内します。</p>
                        <span class="relative mt-5 inline-flex items-center gap-2 text-sm font-semibold text-brand-blue bg-white border border-brand-blue/40 rounded-full px-5 py-2 hover:bg-brand-blue hover:text-white hover:border-brand-blue transition-all duration-200 shadow-soft">
                            詳しく見る
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 6l6 6-6 6"/>
                            </svg>
                        </span>
                    </a>
                    
                @endif
                

                <!-- English Learning -->
                <div class="relative rounded-[24px] shadow-card hover:shadow-card-hover transition-all duration-300 p-7 overflow-hidden bg-gradient-to-b from-amber-50 to-white">
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-amber-400 to-orange-500"></div>

                    {{-- 装飾: きらめくドット（右上） --}}
                    <div aria-hidden="true" class="absolute right-5 top-4 w-24 h-24 pointer-events-none opacity-80">
                        <svg viewBox="0 0 100 100" fill="none">
                            <circle cx="78" cy="14" r="14" fill="#fbbf7a" fill-opacity="0.45" />
                            <circle cx="18" cy="46" r="3" fill="#f0b168" />
                            <circle cx="40" cy="60" r="2.5" fill="#f0b168" />
                            <circle cx="60" cy="34" r="2" fill="#f0b168" />
                            <path d="M62 6l2.4 6.6L71 15l-6.6 2.4L62 24l-2.4-6.6L53 15l6.6-2.4Z" fill="#f2a154" />
                            <path d="M84 40l1.6 4.4L90 46l-4.4 1.6L84 52l-1.6-4.4L78 46l4.4-1.6Z" fill="#f2a154" />
                        </svg>
                    </div>

                    {{-- 装飾: 開いた本と鉛筆 --}}
                    <div aria-hidden="true" class="absolute -right-3 -bottom-3 w-36 sm:w-40 pointer-events-none">
                        <svg viewBox="0 0 160 120" fill="none">
                            <path d="M80 26C64 16 34 12 12 18v76c22-7 52-3 68 7Z" fill="#fde3c7" />
                            <path d="M80 26C96 16 126 12 148 18v76c-22-7-52-3-68 7Z" fill="#fbd3a1" />
                            <path d="M80 26v83" stroke="#f0b168" stroke-width="1.5" />
                            <path d="M20 30c16-4 38-2 52 6M20 44c16-4 38-2 52 6M20 58c16-4 38-2 52 6" stroke="#f0b168" stroke-width="1.4" stroke-linecap="round" opacity="0.6" />
                            <path d="M140 30c-16-4-38-2-52 6M140 44c-16-4-38-2-52 6M140 58c-16-4-38-2-52 6" stroke="#e8a55a" stroke-width="1.4" stroke-linecap="round" opacity="0.55" />
                            <g transform="translate(112 4) rotate(38)">
                                <rect x="0" y="0" width="9" height="72" rx="2" fill="#f5c26b" />
                                <path d="M0 0h9l-4.5-10Z" fill="#e8a55a" />
                                <path d="M2 66h5l-2.5 9Z" fill="#7a5230" />
                                <rect x="0" y="0" width="9" height="6" fill="#e07b53" />
                            </g>
                        </svg>
                    </div>

                    <div class="relative w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 flex items-center justify-center mb-5">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 6a2 2 0 012-2h5.5v15H6a2 2 0 00-2 2V6z"/>
                            <path d="M20 6a2 2 0 00-2-2h-5.5v15H18a2 2 0 012 2V6z"/>
                        </svg>
                    </div>
                    <p class="relative text-xs font-bold text-orange-600 tracking-widest">English Learning</p>
                    <h3 class="relative mt-1 font-bold text-lg text-slate-800">英語学習</h3>
                    <p class="relative mt-2.5 text-sm text-slate-500 leading-relaxed">TOEIC・IELTS対策を中心に、英語力を総合的に伸ばすコンテンツが利用できます。</p>
                    <a href="{{ route('english.hub') }}" class="relative mt-5 inline-flex items-center gap-2 text-sm font-semibold text-orange-600 bg-white border border-orange-600/40 rounded-full px-5 py-2 hover:bg-orange-600 hover:text-white hover:border-orange-600 transition-all duration-200 shadow-soft">
                        学習を始める
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
                    </a>
                </div>

                <!-- Study Abroad Info -->
                <div class="relative rounded-[24px] shadow-card hover:shadow-card-hover transition-all duration-300 p-7 overflow-hidden bg-gradient-to-b from-emerald-50 to-white">
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-emerald-400 to-brand-green"></div>

                    {{-- 装飾: ヤシの葉（右上） --}}
                    <div aria-hidden="true" class="absolute -right-4 -top-4 w-32 h-32 pointer-events-none opacity-60">
                        <svg viewBox="0 0 140 140" fill="none">
                            <path d="M140 0C110 6 84 22 70 50C95 34 122 26 140 30Z" fill="#bbf0c9" />
                            <path d="M140 0C104 2 74 16 56 42C86 24 116 18 140 18Z" fill="#a9e8ba" />
                            <path d="M140 0C100 -2 66 8 44 30C78 12 112 6 140 8Z" fill="#9adfad" />
                            <path d="M140 0C96 4 60 20 36 46C72 22 108 10 140 16Z" fill="#cdf5d8" />
                        </svg>
                    </div>

                    {{-- 装飾: 星座のようなドットライン --}}
                    <div aria-hidden="true" class="absolute right-16 top-8 w-16 h-16 pointer-events-none opacity-70">
                        <svg viewBox="0 0 70 70" fill="none">
                            <path d="M8 8L34 40L52 26" stroke="#86d69c" stroke-width="1.4" stroke-linecap="round" />
                            <circle cx="8" cy="8" r="2.5" fill="#5eab35" />
                            <circle cx="34" cy="40" r="2" fill="#5eab35" />
                            <circle cx="52" cy="26" r="1.6" fill="#5eab35" />
                        </svg>
                    </div>

                    <div class="relative w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-100 to-green-50 ring-1 ring-emerald-200 flex items-center justify-center mb-5">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22v-9"/>
                            <path d="M12 13c-3-1-7-1-9-5 4-1 8 0 9 3"/>
                            <path d="M12 13c3-1 7-1 9-5-4-1-8 0-9 3"/>
                        </svg>
                    </div>
                    <p class="relative text-xs font-bold text-brand-green tracking-widest">Study Abroad Info</p>
                    <h3 class="relative mt-1 font-bold text-lg text-slate-800">留学情報</h3>
                    <p class="relative mt-2.5 text-sm text-slate-500 leading-relaxed">セブ島の生活情報やおすすめスポットなど、留学生活に役立つ情報をチェックできます。</p>

                    {{--
                        メインカテゴリーのアイコン一覧。既存4つは元のアイコン・色・URLをそのまま使い、
                        アドミンがmain_categoriesに追加した5つ目以降は共通の汎用アイコンで自動表示される。
                    --}}
                    @php
                        $fixedIcons = [
                            'carinderia' => '<path d="M7 2v20M7 2a3 3 0 000 6M17 2v8a3 3 0 01-3 3h0a3 3 0 01-3-3V2M14 13v9"/>',
                            'restaurant-cafe' => '<path d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>',
                            'travel' => '<path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>',
                            'other' => '<circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/>',
                        ];
                        $fixedRoutes = [
                            'carinderia' => 'carinderia.index',
                            'restaurant-cafe' => 'restaurant-cafe.index',
                            'travel' => 'travel.index',
                            'other' => 'other.index',
                        ];
                        $genericIcon = '<path d="M12 2 3 7l9 5 9-5-9-5zM3 12l9 5 9-5M3 17l9 5 9-5"/>';
                    @endphp
                    {{-- カテゴリーが増えてもカードの高さが伸びないよう、4つ分の幅で1行固定+はみ出た分は横スライド --}}
                    <div class="relative mt-5 flex gap-2 overflow-x-auto snap-x snap-mandatory pb-1" style="scrollbar-width:none;">
                        @foreach ($mainCategories as $mc)
                            @php
                                $href = isset($fixedRoutes[$mc->key]) ? route($fixedRoutes[$mc->key]) : route('information.dynamic', $mc->key);
                                $color = $mc->color();
                                $icon = $fixedIcons[$mc->key] ?? $genericIcon;
                            @endphp
                            <a href="{{ $href }}" class="group flex flex-col items-center gap-1.5 shrink-0 w-[68px] snap-start">
                                <span class="w-11 h-11 rounded-xl flex items-center justify-center transition-transform group-hover:scale-105" style="background:{{ $color }}1a">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $color }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $icon !!}</svg>
                                </span>
                                <span class="text-[11px] text-slate-500 font-semibold text-center leading-tight">{{ $mc->name }}</span>
                            </a>
                        @endforeach
                    </div>

                    <a href="{{ route('carinderia.index') }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-brand-green border border-brand-green/40 rounded-full px-5 py-2 hover:bg-brand-green hover:text-white hover:border-brand-green transition-all duration-200">
                        詳しく見る
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- School Location -->
    <section class="relative bg-gradient-to-b from-white via-sky-50/60 to-white pb-20 overflow-hidden">

        {{-- 装飾: 右側にヤシの木のシルエット（セクション内に収め、他ページへ影響しないよう overflow-hidden） --}}
        <div aria-hidden="true" class="hidden lg:block absolute right-0 bottom-0 w-64 h-80 pointer-events-none opacity-[0.14]">
            <svg viewBox="0 0 200 260" fill="none">
                <path d="M100 260V110" stroke="#16a34a" stroke-width="10" stroke-linecap="round" />
                <path d="M100 115C60 90 30 95 5 75C40 70 70 80 100 100Z" fill="#16a34a" />
                <path d="M100 115C140 85 170 90 195 65C160 65 125 78 100 100Z" fill="#16a34a" />
                <path d="M100 108C75 75 55 40 45 10C70 30 90 65 100 95Z" fill="#16a34a" />
                <path d="M100 108C125 75 145 40 155 10C130 30 110 65 100 95Z" fill="#16a34a" />
                <path d="M100 105C95 65 100 30 90 0C110 25 112 65 100 95Z" fill="#16a34a" />
            </svg>
        </div>

        <div class="relative max-w-[1140px] mx-auto px-6">
            <div class="relative bg-white rounded-[20px] shadow-card overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 items-stretch">

                    <!-- Map -->
                    <div class="relative h-[280px] sm:h-[320px] md:h-auto">
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

                    <!-- Info -->
                    <div class="relative p-6 md:p-8 overflow-hidden flex flex-col justify-center">
                        <div aria-hidden="true" class="absolute -right-6 -bottom-6 w-32 h-32 pointer-events-none opacity-60">
                            <svg viewBox="0 0 120 120" fill="none">
                                <circle cx="70" cy="70" r="42" fill="#eff6ff" />
                                <circle cx="30" cy="90" r="14" fill="#fef3c7" />
                            </svg>
                        </div>

                        <div class="relative">
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
            </div>
        </div>
    </section>

@endsection
