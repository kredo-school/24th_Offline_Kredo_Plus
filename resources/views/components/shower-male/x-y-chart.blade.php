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

    <div class="flex flex-wrap justify-between items-center gap-3 mb-2">
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

    <div class="relative h-[450px] rounded-xl bg-blue-500/10 mt-4">

        {{-- グリッド --}}
        <div class="absolute top-8 right-8 bottom-20 left-24 grid grid-cols-3 grid-rows-3">
            @for ($i = 0; $i < 9; $i++)
                <div class="border border-gray-200"></div>
            @endfor
        </div>

        {{-- X軸 --}}
        <div class="absolute right-8 left-24 bottom-20 border-[1px] border-t border-slate-300"></div>

        {{-- Y軸 --}}
        <div class="absolute top-8 bottom-20 left-24 border-[1px] border-l border-slate-300"></div>

        {{-- X軸ラベル --}}
        <div class="absolute bottom-8 right-4 left-20 flex justify-between text-l font-semibold text-slate-400">
            <span>冷たい</span>
            <span>ぬるい</span>
            <span>温かい</span>
            <span>熱い</span>
        </div>

        {{-- Y軸ラベル --}}
        <div class="absolute top-6 bottom-[72px] left-8 flex flex-col justify-between text-l font-semibold text-slate-400">
            <span>強い</span>
            <span>普通</span>
            <span>弱い</span>
            <span>無し</span>
        </div>

        {{-- プロットエリア --}}
        <div class="absolute top-8 right-8 bottom-20 left-24" id="plot-area">
            <template x-for="point in points" :key="point.shower_number">
                <div
                    class="absolute -translate-x-1/2 translate-y-1/2 rounded-full flex items-center justify-center text-xs font-bold shadow-md transition-all"
                    :class="point.shower_number === recommendedNumber
                        ? 'w-11 h-11 bg-sky-500 text-white ring-4 ring-sky-300 animate-pulse shadow-[0_0_20px_6px_rgba(56,189,248,0.6)] z-10'
                        : 'w-8 h-8 bg-blue-500 text-white'"
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