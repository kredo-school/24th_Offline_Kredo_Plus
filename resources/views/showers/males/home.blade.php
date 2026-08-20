@extends('layouts.app')
 
@section('title', 'male shower')
 
@section('content')

<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-8 md:py-12">
    {{-- usage status  おすすめシャワー表示 --}}
    <section class="relative overflow-hidden rounded-[0.75rem] mb-8 p-8 md:p-10 bg-cover"
    style="background-image: url('{{ asset('images/shower/shower-image.jpg') }}'); background-position: 70% 58%">
        <div class="absolute inset-0 bg-gradient-to-r from-white/80 from-5% via-white/25 via-40% to-transparent to-65% pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-stretch gap-8">
            <div class="flex-1 flex flex-col">

                <div>
                    <h1 class="text-display font-black text-blue-950/90 mb-1">シャワー情報</h1>
                    <p class="text-headline-md font-bold text-blue-700 mb-3">Shower Information</p>
                    <p class="text-body-md text-blue-950 max-w-lg">
                        好みに応じたおすすめのシャワーを<br class="sm:hidden">ご案内します。
                    </p>
                </div>

                <div class="flex flex-col gap-2 mt-auto pt-4">
                    @if ($isFull)
                        <div class="w-96 max-w-full flex items-center gap-2 bg-red-50 text-red-600 rounded-xl px-4 py-2.5 text-sm font-semibold">
                            <span class="material-symbols-outlined !text-lg">error</span>
                            現在満室です。(報告：{{ $fullReportedMinutesAgo }}分前)
                        </div>
                    @endif

                    @if ($brokenShowerNumbers->isNotEmpty())
                        <div class="w-96 max-w-full flex items-center gap-2 bg-amber-50 text-amber-700 rounded-xl px-4 py-2.5 text-sm font-semibold">
                            <span class="material-symbols-outlined !text-lg">build</span>
                            現在{{ $brokenShowerNumbers->map(fn ($n) => $n . '番')->implode('、') }}のシャワーが故障中です。
                        </div>
                    @endif
                </div>
            </div>

            <div class="w-full lg:w-[380px] bg-surface-container-lowest rounded-[0.75rem] shadow-md p-6 shrink-0">
                <div class="flex items-center gap-3 mb-4 border-b border-outline-blue-950/30 pb-4">
                    <div class="w-11 h-11 shrink-0 bg-blue-500/10 rounded-[0.75rem] flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600">thumb_up</span>
                    </div>
                    <div class="w-full grid grid-cols-2 text-center">
                        <div class="border-e border-outline-950/30 pe-5 h-full flex flex-col">
                            <p class="text-caption text-blue-950 leading-none mb-1">
                                <span class="sm:hidden">おすすめ</span>
                                <span class="hidden sm:inline">おすすめシャワー</span>
                            </p>
                            <div class="flex-1 flex items-center justify-center">
                                <p class="text-4xl font-black text-blue-600 leading-none"
                                    x-text="$store.showerPriority.recommendation ? $store.showerPriority.recommendation.shower_number : '—'"></p>
                            </div>
                        </div>
                        <div class="ps-5">
                            <p class="text-caption text-blue-950 leading-none mb-1">
                                <span class="sm:hidden">マッチ度</span>
                                <span class="hidden sm:inline">好みとのマッチ度</span>
                            </p>
                            <p class="text-headline-md font-black text-blue-950 leading-none"
                                x-text="$store.showerPriority.recommendation ? $store.showerPriority.recommendation.match_percent + ' %' : '—'"></p>
                            <x-shower-male.preference-setup />
                        </div>
                    </div>
                </div>

                {{-- 水温バー --}}
                <x-shower-male.temperature-bar

                />

                {{-- 水圧バー --}}
                <x-shower-male.pressure-bar

                />
            </div>
        </div>
    </section>

    {{-- buttons 報告ボタン --}}
    <section>
        <div class="flex gap-5 justify-center">
                    
            {{-- 満室報告 --}}
            <x-shower-male.no-vacancy-report-button :is-full="$isFull" />

            {{-- シャワー情報投稿 --}}
            <x-shower-male.shower-rating-button :broken-shower-numbers="$brokenShowerNumbers" />
            {{-- 故障報告 --}}
            <x-shower-male.defect-report-button :broken-shower-numbers="$brokenShowerNumbers" />
        </div>           
    </section>

    {{-- statistics 統計 --}}
    <section class="mt-6">
        <h2 class="text-headline-md font-bold text-blue-950 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-700 translate-y-[2px]">bar_chart_4_bars</span>
            統計
        </h2>
        {{-- x-y chart 二次元チャートで各シャワーを比較 --}}
        <x-shower-male.x-y-chart
        
        />

        {{-- line chart 線グラフで各シャワーの状態変化を確認 --}}
        <x-shower-male.line-chart :recommendation="$recommendation" />
    </section>

    {{-- trend table  トレンドテーブル --}}
    {{-- <x-shower-male.trend-table /> --}}

    {{-- comments コメント欄 --}}
    <section
        class="space-y-stack-md mt-6"
        x-data="{
            showerNumber: 'all',
            temperatureLabel: null,
            pressureLabel: null,
            items: [],
            total: 0,
            showingAll: false,

            async load(all = false) {
                this.showingAll = all;
                const limit = all ? 1000 : 5;
                const params = new URLSearchParams({
                    shower_number: this.showerNumber,
                    limit,
                });
                if (this.temperatureLabel) params.set('temperature_label', this.temperatureLabel);
                if (this.pressureLabel) params.set('pressure_label', this.pressureLabel);

                const response = await fetch(`{{ route('shower.comments') }}?${params}`);
                const data = await response.json();
                this.items = data.items;
                this.total = data.total;
            },

            temperatureColor(label) {
                return {
                    '冷たい': '#60a5fa',
                    'ぬるい': '#34d399',
                    '温かい': '#fbbf24',
                    '熱い': '#ef4444',
                }[label] ?? '#94a3b8';
            },

            pressureColor(label) {
                return {
                    '無し': '#93c5fd',
                    '弱い': '#60a5fa',
                    '普通': '#3b82f6',
                    '強い': '#1e3a8a',
                }[label] ?? '#94a3b8';
            },
        }"
        x-init="load()"
    >

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-headline-md font-bold text-blue-950 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-700 translate-y-[2px]">chat</span>
                コメント
            </h2>

            {{-- スマホ：フィルター --}}
            <div
                class="sm:hidden relative"
                x-data="{ filterOpen: false }"
                @click.outside="filterOpen = false"
            >
                <button
                    type="button"
                    @click="filterOpen = !filterOpen"
                    class="flex items-center gap-1 rounded-full bg-sky-400 text-white text-xs font-bold px-3.5 py-1.5"
                >
                    <span>フィルター</span>
                    <span class="material-symbols-outlined !text-sm">expand_more</span>
                </button>

                <div
                    x-show="filterOpen"
                    x-cloak
                    x-transition
                    class="absolute right-0 mt-2 z-20 bg-white rounded-2xl shadow-xl border border-slate-200 p-3 w-72"
                >
                    <p class="text-xs font-bold text-slate-500 mb-1.5 ms-1">シャワー番号</p>
                    <div class="mb-1.5">
                        <button
                            type="button"
                            @click="showerNumber = 'all'; load(showingAll)"
                            :class="showerNumber === 'all' ? 'bg-sky-400 text-white' : 'bg-sky-50 text-sky-700'"
                            class="rounded-full text-xs font-bold px-3 py-1.5 transition-colors"
                        >すべて</button>
                    </div>
                    
                <div class="flex flex-wrap justify-between gap-1.5 mb-3">
                    @for ($i = 1; $i <= 7; $i++)
                        <button
                            type="button"
                            @click="showerNumber = '{{ $i }}'; load(showingAll)"
                            :class="showerNumber === '{{ $i }}' ? 'bg-sky-400 text-white' : 'bg-sky-50 text-sky-700'"
                            class="rounded-full w-8 h-8 text-xs font-bold transition-colors"
                        >{{ $i }}</button>
                    @endfor
                </div>

                    <p class="text-xs font-bold text-slate-500 mb-1.5 ms-1">温度</p>
                    <div class="flex flex-wrap justify-between gap-1.5 mb-3">
                        <template x-for="option in ['冷たい', 'ぬるい', '温かい', '熱い']" :key="'temp-' + option">
                            <button
                                type="button"
                                @click="temperatureLabel = (temperatureLabel === option ? null : option); load(showingAll)"
                                :class="temperatureLabel === option ? 'bg-rose-400 text-white' : 'bg-rose-50 text-rose-700'"
                                class="rounded-full text-xs font-bold px-3 py-1.5 transition-colors"
                                x-text="option"
                            ></button>
                        </template>
                    </div>

                    <p class="text-xs font-bold text-slate-500 mb-1.5 ms-1">水圧</p>
                    <div class="flex flex-wrap justify-between gap-1.5">
                        <template x-for="option in ['無し', '弱い', '普通', '強い']" :key="'pressure-' + option">
                            <button
                                type="button"
                                @click="pressureLabel = (pressureLabel === option ? null : option); load(showingAll)"
                                :class="pressureLabel === option ? 'bg-sky-500 text-white' : 'bg-sky-50 text-sky-700'"
                                class="rounded-full text-xs font-bold px-3 py-1.5 transition-colors"
                                x-text="option"
                            ></button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        

        <x-shower-female.comment-log
        
        />
    </section>
    
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('showerPriority', {
            factor: @json(auth()->user()->shower_priority_factor ?? null),
            recommendation: @json($recommendation),

            async toggle(clicked) {
                const nextFactor = this.factor === clicked ? 'none' : clicked;

                const response = await fetch('{{ route('shower.priority.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ factor: nextFactor }),
                });

                if (!response.ok) {
                    console.error('優先設定の保存に失敗しました');
                    return;
                }

                const data = await response.json();
                this.factor = data.priority_factor;
                this.recommendation = data.recommendation;
            },
        });

        Alpine.data('showerGauge', (type) => ({
            type,

            levelsByType: {
                temperature: [
                    { value: 2.5, label: '冷たい', color: 'text-[#60a5fa]' },
                    { value: 5.0, label: 'ぬるい', color: 'text-[#34d399]' },
                    { value: 7.5, label: '温かい', color: 'text-[#fbbf24]' },
                    { value: 10,  label: '熱い',   color: 'text-[#ef4444]' },
                ],
                pressure: [
                    { value: 2.5, label: '無し', color: 'text-[#eff6ff]' },
                    { value: 5.0, label: '弱い', color: 'text-[#93c5fd]' },
                    { value: 7.5, label: '普通', color: 'text-[#3b82f6]' },
                    { value: 10,  label: '強い', color: 'text-[#1e3a8a]' },
                ],
            },

            get levels() {
                return this.levelsByType[this.type];
            },

            get hasData() {
                return this.$store.showerPriority.recommendation !== null;
            },

            get value() {
                return this.hasData ? this.$store.showerPriority.recommendation[this.type] : 0;
            },

            get percent() {
                return (this.value / 10) * 100;
            },

            get label() {
                const count = this.levels.length;
                let idx = Math.floor((this.value / 10) * count);
                idx = Math.max(0, Math.min(idx, count - 1));
                return this.levels[idx].label;
            },
        }));
    });
</script>
@endpush
