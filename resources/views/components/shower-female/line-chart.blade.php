
{{-- line chart 線グラフで各シャワーの状態変化を確認 --}}
<div
    class="bg-white rounded-2xl p-6 border-none shadow-lg mt-10"
    x-data="{
        days: '7',
        showerNumber: {{ optional($recommendation)['shower_number'] ?? 1 }},
        points: [],
        brokenPeriods: [],

        async load() {
            const response = await fetch(`{{ route('shower.trend-data') }}?shower_number=${this.showerNumber}&days=${this.days}`);
            const data = await response.json();
            this.points = data.points;
            this.brokenPeriods = data.broken_periods;
        },

        stepPercent() {
            const daysNum = parseInt(this.days, 10);
            return daysNum > 1 ? 100 / (daysNum - 1) : 100;
        },

        dateToPercent(dateStr) {
            const daysNum = parseInt(this.days, 10);
            const rangeStart = new Date();
            rangeStart.setHours(0, 0, 0, 0);
            rangeStart.setDate(rangeStart.getDate() - (daysNum - 1));

            const target = new Date(dateStr);
            const dayOffset = Math.round((target - rangeStart) / (1000 * 60 * 60 * 24));

            return Math.max(0, Math.min(100, (dayOffset / (daysNum - 1)) * 100));
        },

        xPercent(index) {
            if (this.points.length === 0) return 0;
            return this.dateToPercent(this.points[index].date);
        },

        yPercent(value) {
            return (value / 10) * 100;
        },

        // 故障区間をまたぐ場所で線を分断し、複数のセグメント(線分)に分ける
        segments(key) {
            const result = [];
            let current = [];

            this.points.forEach((point, index) => {
                const isBroken = this.brokenPeriods.some(period => point.date >= period.start && point.date <= period.end);

                if (isBroken) {
                    if (current.length > 0) {
                        result.push(current);
                        current = [];
                    }
                    return;
                }

                current.push(`${this.xPercent(index)},${100 - this.yPercent(point[key])}`);
            });

            if (current.length > 0) result.push(current);

            return result.map(seg => seg.join(' '));
        },
    }"
    x-init="load()"
>
    <div class="flex flex-wrap justify-between items-end gap-3 mb-4">
        <div>
            <h3 class="text-headline-sm font-bold text-blue-950 mb-2">
                パフォーマンストレンド
            </h3>
            <div class="flex items-center gap-4 text-sm font-semibold text-slate-500 h-8">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-400"></span> 温度</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-400"></span> 水圧</span>
            </div>
        </div>

        <div class="flex flex-col gap-2 items-end">
            {{-- 上段: 日数 --}}
            <div class="flex gap-1.5">
                <template x-for="option in [
                    { value: '3', label: '3日間' },
                    { value: '7', label: '7日間' },
                    { value: '14', label: '14日間' },
                ]" :key="option.value">
                    <button
                        type="button"
                        @click="days = option.value; load()"
                        :class="days === option.value
                            ? 'bg-sky-400 text-white shadow-sm'
                            : 'bg-sky-200/60 text-sky-700 hover:bg-sky-200'"
                        class="rounded-full text-xs font-bold px-3.5 py-1.5 transition-colors"
                        x-text="option.label"
                    ></button>
                </template>
            </div>

            {{-- 下段: シャワー番号 --}}
            <div class="flex gap-1.5 h-8">
                @for ($i = 1; $i <= 7; $i++)
                    <button
                        type="button"
                        @click="showerNumber = {{ $i }}; load()"
                        :class="showerNumber === {{ $i }}
                            ? 'bg-sky-400 text-white shadow-sm'
                            : 'bg-sky-200/60 text-sky-700 hover:bg-sky-200'"
                        class="rounded-full w-8 h-8 text-xs font-bold transition-colors"
                    >{{ $i }}</button>
                @endfor
            </div>
        </div>
    </div>

    <div class="relative h-[450px] rounded-xl bg-blue-500/10 p-8">

        {{-- グリッド --}}
        <div class="absolute top-14 right-8 bottom-20 left-[180px] grid grid-rows-3">
            @for ($i = 0; $i < 3; $i++)
                <div class="border border-gray-200"></div>
            @endfor
        </div>

        {{-- X軸 --}}
        <div class="absolute right-8 left-[180px] bottom-20 border-[1px] border-t border-slate-300"></div>

        {{-- Y軸 --}}
        <div class="absolute top-8 bottom-20 left-[180px] border-[1px] border-l border-slate-300"></div>

        {{-- X軸ラベル(ドットと同じ座標計算で、テキスト中央を点に合わせる) --}}
        <div class="absolute bottom-8 right-12 left-[200px] h-5">
            <template x-for="(point, index) in points" :key="'label-' + point.date">
                <span
                    class="absolute -translate-x-1/2 text-center font-semibold text-slate-400 text-sm whitespace-nowrap"
                    :style="{ left: xPercent(index) + '%' }"
                    x-text="point.date.slice(5)"
                ></span>
            </template>
            <template x-if="points.length === 0">
                <span class="absolute left-1/2 -translate-x-1/2 text-slate-400">データがありません</span>
            </template>
        </div>

        {{-- Y軸ラベル --}}
        <div class="absolute top-12 bottom-[72px] left-[50px] flex flex-col justify-between text-center font-semibold text-slate-400">
            <span>熱い・強い</span>
            <span>温かい・普通</span>
            <span>ぬるい・弱い</span>
            <span>冷たい・無し</span>
        </div>

        {{-- 故障期間の帯(シェード) --}}
        <div class="absolute top-14 right-12 bottom-20 left-[200px]">
            <template x-for="period in brokenPeriods" :key="period.start + period.end">
                <div
                    class="absolute top-0 bottom-0 bg-red-400/10 border-x border-red-300/40 flex items-start justify-center pt-2"
                    :style="{
                        left: `calc(${dateToPercent(period.start)}% + 12px)`,
                        width: `calc(${dateToPercent(period.end) - dateToPercent(period.start)}% - 24px)`,
                    }"
                >
                    <span class="text-[10px] font-bold text-red-500 bg-white/80 rounded px-1.5 py-0.5 whitespace-nowrap">故障</span>
                </div>
            </template>
        </div>

        {{-- 折れ線グラフ本体(SVG)+ドット --}}
        <div class="absolute top-14 right-12 bottom-20 left-[200px]">
            <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="w-full h-full overflow-visible">
                <template x-for="(seg, i) in segments('temperature')" :key="'temp-seg-' + i">
                    <polyline :points="seg" fill="none" stroke="#f87171" stroke-width="1.5" vector-effect="non-scaling-stroke" />
                </template>
                <template x-for="(seg, i) in segments('pressure')" :key="'pressure-seg-' + i">
                    <polyline :points="seg" fill="none" stroke="#60a5fa" stroke-width="1.5" vector-effect="non-scaling-stroke" />
                </template>
            </svg>

            <template x-for="(point, index) in points" :key="'temp-dot-' + point.date">
                <div
                    class="absolute w-3 h-3 -translate-x-1/2 translate-y-1/2 rounded-full bg-red-400 border-2 border-white shadow"
                    :style="{ left: xPercent(index) + '%', bottom: yPercent(point.temperature) + '%' }"
                ></div>
            </template>
            <template x-for="(point, index) in points" :key="'pressure-dot-' + point.date">
                <div
                    class="absolute w-3 h-3 -translate-x-1/2 translate-y-1/2 rounded-full bg-blue-400 border-2 border-white shadow"
                    :style="{ left: xPercent(index) + '%', bottom: yPercent(point.pressure) + '%' }"
                ></div>
            </template>
        </div>

    </div>

</div>