@extends('layouts.app')

@if($showIntro ?? false)
    @section('intro', true)
@endif

@section('content')
    {{-- シャワーページにアクセスする際に性別登録のmodalが表示される（未登録の場合） --}}
    @if (session('showGenderModal'))
        <div x-data="{ open: true }" x-show="open" x-cloak
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
                        <button type="button" @click="open = false"
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
    @endif

    <!-- Hero -->
    <section class="relative bg-gradient-to-b from-sky-50 via-white to-white pt-8 pb-16">
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

                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/10 to-transparent"></div>

                <div class="absolute inset-x-0 bottom-0 p-8 sm:p-12 text-white">
                    <h1 class="font-display font-extrabold text-3xl sm:text-4xl drop-shadow-sm">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="mt-3 text-white/90 leading-relaxed">今日も素敵な一日を始めましょう！</p>
                    <p class="text-white/80 text-sm">セブでの学びと生活を、もっと充実させよう。</p>
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
                                class="h-2 rounded-full transition-all"
                                :class="current === index ? 'bg-white w-5' : 'bg-white/50 w-2'"
                                aria-label="スライドに移動"></button>
                    </template>
                </div>
            </div>

            <!-- Feature cards -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Shower Information -->
                <div class="relative bg-white rounded-[24px] shadow-card hover:shadow-card-hover transition-all duration-300 p-7 overflow-hidden">
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-sky-400 to-brand-blue"></div>
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-100 to-sky-50 ring-1 ring-sky-200 flex items-center justify-center mb-5">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#2f5fdb" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 11h14"/>
                            <path d="M6.5 11a5.5 5.5 0 0111 0"/>
                            <path d="M4 8.5L2.5 7"/>
                            <path d="M8 15l-.6 1.6M12 15.5v2M16 15l.6 1.6"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-brand-blue tracking-widest">Shower Information</p>
                    <h3 class="mt-1 font-bold text-lg text-slate-800">シャワー情報</h3>
                    <p class="mt-2.5 text-sm text-slate-500 leading-relaxed">シャワーの混雑状況をチェックして、快適にご利用いただけます。</p>
                    <a href="{{ route('shower.entry') }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-brand-blue border border-brand-blue/40 rounded-full px-5 py-2 hover:bg-brand-blue hover:text-white hover:border-brand-blue transition-all duration-200">
                        詳しく見る
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
                    </a>
                </div>

                <!-- English Learning -->
                <div class="relative bg-white rounded-[24px] shadow-card hover:shadow-card-hover transition-all duration-300 p-7 overflow-hidden">
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 flex items-center justify-center mb-5">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 6a2 2 0 012-2h5.5v15H6a2 2 0 00-2 2V6z"/>
                            <path d="M20 6a2 2 0 00-2-2h-5.5v15H18a2 2 0 012 2V6z"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-orange-600 tracking-widest">English Learning</p>
                    <h3 class="mt-1 font-bold text-lg text-slate-800">英語学習</h3>
                    <p class="mt-2.5 text-sm text-slate-500 leading-relaxed">TOEIC・IELTS対策を中心に、英語力を総合的に伸ばすコンテンツが利用できます。</p>
                    <a href="{{ route('english.hub') }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-orange-600 border border-orange-600/50 rounded-full px-5 py-2 hover:bg-orange-600 hover:text-white hover:border-orange-600 transition-all duration-200">
                        学習を始める
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
                    </a>
                </div>

                <!-- Study Abroad Info -->
                <div class="relative bg-white rounded-[24px] shadow-card hover:shadow-card-hover transition-all duration-300 p-7 overflow-hidden">
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-emerald-400 to-brand-green"></div>
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-100 to-green-50 ring-1 ring-emerald-200 flex items-center justify-center mb-5">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22v-9"/>
                            <path d="M12 13c-3-1-7-1-9-5 4-1 8 0 9 3"/>
                            <path d="M12 13c3-1 7-1 9-5-4-1-8 0-9 3"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-brand-green tracking-widest">Study Abroad Info</p>
                    <h3 class="mt-1 font-bold text-lg text-slate-800">留学情報</h3>
                    <p class="mt-2.5 text-sm text-slate-500 leading-relaxed">セブ島の生活情報やおすすめスポットなど、留学生活に役立つ情報をチェックできます。</p>

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
                    <div class="mt-5 flex gap-2 overflow-x-auto snap-x snap-mandatory pb-1" style="scrollbar-width:none;">
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

                    <a href="{{ route('travel.index') }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-brand-green border border-brand-green/40 rounded-full px-5 py-2 hover:bg-brand-green hover:text-white hover:border-brand-green transition-all duration-200">
                        詳しく見る
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- School Location -->
    <section class="relative bg-gradient-to-b from-white via-sky-50 to-white pb-20">
        <div class="relative max-w-[1140px] mx-auto px-6">
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
