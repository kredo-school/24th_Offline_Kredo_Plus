{{-- x-y chart 二次元チャートで各シャワーを比較 --}}
<div
    class="bg-white rounded-2xl p-6 border-none shadow-lg"
    x-data="{
        period: '24h',
        points: [],
        brokenNumbers: [],

        get recommendedNumber() {
            return this.$store.showerPriority.recommendation
                ? this.$store.showerPriority.recommendation.shower_number
                : null;
        },

        async load() {
            const response = await fetch(`{{ route('shower.scatter-data') }}?period=${this.period}`);
            const data = await response.json();
            this.points = data.points;
            this.brokenNumbers = data.broken_numbers;
        },
    }"
    x-init="load()"
>

    {{-- PC --}}
<div class="hidden sm:flex flex-wrap justify-between items-center gap-3 mb-2">
    <div class="flex flex-wrap items-center gap-2">
        <h3 class="text-headline-sm font-bold text-blue-950">
            シャワー状態散布図
        </h3>

        <template x-for="number in brokenNumbers" :key="number">
            <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1">
                <span class="material-symbols-outlined !text-sm">build</span>
                <span x-text="number + '番 故障中'"></span>
            </span>
        </template>
    </div>

    <div class="flex gap-1.5">
        <template x-for="option in [
            { value: 'latest', label: '最新' },
            { value: '24h', label: '24時間' },
            { value: '3d', label: '3日間' },
            { value: '7d', label: '7日間' },
            { value: '14d', label: '14日間' },
        ]" :key="option.value">
            <button
                type="button"
                @click="period = option.value; load()"
                :class="period === option.value
                    ? 'bg-sky-400 text-white shadow-sm'
                    : 'bg-sky-200/60 text-sky-700 hover:bg-sky-200'"
                class="rounded-full text-xs font-bold px-3.5 py-1.5 transition-colors"
                x-text="option.label"
            ></button>
        </template>
    </div>
</div>


{{-- スマホ --}}
<div class="sm:hidden mb-2">

    {{-- 故障シャワーなし --}}
    <div
        x-show="brokenNumbers.length === 0"
        x-cloak
        class="flex items-center justify-between gap-2"
    >
        <h3 class="text-headline-sm font-bold text-blue-950">
            シャワー状態散布図
        </h3>

        {{-- フィルター --}}
        <div
            class="relative shrink-0"
            x-data="{ filterOpen: false }"
            @click.outside="filterOpen = false"
        >
            <button
                type="button"
                @click="filterOpen = !filterOpen"
                class="flex items-center gap-1 rounded-full bg-sky-400 text-white text-xs font-bold px-3.5 py-1.5"
            >
                <span x-text="{ 'latest': '最新', '24h': '24時間', '3d': '3日間', '7d': '7日間', '14d': '14日間' }[period]"></span>
                <span class="material-symbols-outlined !text-sm">expand_more</span>
            </button>

            <div
                x-show="filterOpen"
                x-cloak
                x-transition
                class="absolute right-0 mt-2 z-50 bg-white rounded-2xl shadow-xl border border-slate-200 p-2 flex flex-col gap-1 w-32"
            >
                <template x-for="option in [
                    { value: 'latest', label: '最新' },
                    { value: '24h', label: '24時間' },
                    { value: '3d', label: '3日間' },
                    { value: '7d', label: '7日間' },
                    { value: '14d', label: '14日間' },
                ]" :key="option.value">
                    <button
                        type="button"
                        @click="period = option.value; load(); filterOpen = false"
                        :class="period === option.value
                            ? 'bg-sky-400 text-white'
                            : 'text-sky-700 hover:bg-sky-50'"
                        class="rounded-lg text-xs font-bold px-3 py-2 text-left transition-colors"
                        x-text="option.label"
                    ></button>
                </template>
            </div>
        </div>
    </div>


    {{-- 故障シャワーあり --}}
    <div
        x-show="brokenNumbers.length > 0"
        x-cloak
    >
        {{-- 1行目：タイトル --}}
        <h3 class="text-headline-sm font-bold text-blue-950 mb-2">
            シャワー状態散布図
        </h3>

        {{-- 2行目：故障シャワー ＋ フィルター --}}
        <div class="flex items-center justify-between gap-2">

            {{-- 故障シャワー --}}
            <div class="flex flex-wrap items-center gap-2 min-w-0">
                <template x-for="number in brokenNumbers" :key="number">
                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1">
                        <span class="material-symbols-outlined !text-sm">build</span>
                        <span x-text="number + '番 故障中'"></span>
                    </span>
                </template>
            </div>

            {{-- フィルター --}}
            <div
                class="relative shrink-0"
                x-data="{ filterOpen: false }"
                @click.outside="filterOpen = false"
            >
                <button
                    type="button"
                    @click="filterOpen = !filterOpen"
                    class="flex items-center gap-1 rounded-full bg-sky-400 text-white text-xs font-bold px-3.5 py-1.5"
                >
                    <span x-text="{ 'latest': '最新', '24h': '24時間', '3d': '3日間', '7d': '7日間', '14d': '14日間' }[period]"></span>
                    <span class="material-symbols-outlined !text-sm">expand_more</span>
                </button>

                <div
                    x-show="filterOpen"
                    x-cloak
                    x-transition
                    class="absolute right-0 mt-2 z-50 bg-white rounded-2xl shadow-xl border border-slate-200 p-2 flex flex-col gap-1 w-32"
                >
                    <template x-for="option in [
                        { value: 'latest', label: '最新' },
                        { value: '24h', label: '24時間' },
                        { value: '3d', label: '3日間' },
                        { value: '7d', label: '7日間' },
                        { value: '14d', label: '14日間' },
                    ]" :key="option.value">
                        <button
                            type="button"
                            @click="period = option.value; load(); filterOpen = false"
                            :class="period === option.value
                                ? 'bg-sky-400 text-white'
                                : 'text-sky-700 hover:bg-sky-50'"
                            class="rounded-lg text-xs font-bold px-3 py-2 text-left transition-colors"
                            x-text="option.label"
                        ></button>
                    </template>
                </div>
            </div>

        </div>
    </div>

</div>

    <div class="relative h-[225px] sm:h-[450px] rounded-xl bg-blue-500/10">

        {{-- グリッド --}}
        <div class="absolute top-8 right-4 sm:right-8 bottom-20 left-14 sm:left-24 grid grid-cols-3 grid-rows-3">
            @for ($i = 0; $i < 9; $i++)
                <div class="border border-gray-200"></div>
            @endfor
        </div>

        {{-- X軸 --}}
        <div class="absolute right-4 sm:right-8 left-14 sm:left-24 bottom-20 border-[1px] border-t border-slate-300"></div>

        {{-- Y軸 --}}
        <div class="absolute top-8 bottom-20 left-14 sm:left-24 border-[1px] border-l border-slate-300"></div>

        {{-- X軸ラベル --}}
        <div class="absolute bottom-8 right-4 left-10 sm:left-20 flex justify-between text-sm sm:text-l font-semibold text-slate-400">
            <span>冷たい</span>
            <span>ぬるい</span>
            <span>温かい</span>
            <span>熱い</span>
        </div>

        {{-- Y軸ラベル --}}
        <div class="absolute top-6 bottom-[72px] left-2 sm:left-8 flex flex-col justify-between text-sm sm:text-l font-semibold text-slate-400">
            <span>強い</span>
            <span>普通</span>
            <span>弱い</span>
            <span>無し</span>
        </div>

        {{-- プロットエリア --}}
        <div class="absolute top-8 right-4 sm:right-8 bottom-20 left-14 sm:left-24" id="plot-area">
            <template x-for="point in points" :key="point.shower_number">
                <div
                    class="absolute -translate-x-1/2 translate-y-1/2 rounded-full flex items-center justify-center text-xs font-bold shadow-md transition-all"
                    :class="point.shower_number === recommendedNumber
                        ? 'w-8 h-8 sm:w-11 sm:h-11 bg-sky-500 text-white ring-4 ring-sky-300 animate-pulse shadow-[0_0_20px_6px_rgba(56,189,248,0.6)] z-10'
                        : 'w-6 h-6 sm:w-8 sm:h-8 bg-blue-500 text-white'"
                    :style="{
                        left: (point.temperature / 10 * 100) + '%',
                        bottom: (point.pressure / 10 * 100) + '%',
                    }"
                    :title="point.shower_number + '番' + (point.shower_number === recommendedNumber ? '(おすすめ)' : '')"
                    x-text="point.shower_number"
                ></div>
            </template>
        </div>
    </div>
</div>