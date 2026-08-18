
{{-- line chart 線グラフで各シャワーの状態変化を確認 --}}
<div
    class="bg-white rounded-2xl p-6 border-none shadow-lg mt-10"
    x-data="{
        days: '7',
        showerNumber: {{ optional($recommendation)['shower_number'] ?? 1 }},
        points: [],
        brokenPeriods: [],
        hoveredIndex: null,

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

        temperatureLabel(value) {
            if (value <= 2.5) return '冷たい';
            if (value <= 5.0) return 'ぬるい';
            if (value <= 7.5) return '温かい';
            return '熱い';
        },

        pressureLabel(value) {
            if (value <= 2.5) return '無し';
            if (value <= 5.0) return '弱い';
            if (value <= 7.5) return '普通';
            return '強い';
        },

        // 故障区間をまたぐ場所で線を分断し、複数のセグメント(線分)に分ける
        segments(key) {
            const result = [];
            let current = [];

            const addDays = (dateStr, days) => {
                const d = new Date(dateStr);
                d.setDate(d.getDate() + days);
                return d.toISOString().slice(0, 10);
            };

            this.points.forEach((point, index) => {
                if (index > 0) {
                    const prevDate = this.points[index - 1].date;
                    const currDate = point.date;

                    // 2点の間にある「投稿が存在しない日」の範囲
                    const gapStart = addDays(prevDate, 1);
                    const gapEnd = addDays(currDate, -1);

                    let hasBreakBetween = false;
                    if (gapStart <= gapEnd) {
                        // 隙間が実際に存在する場合だけ、故障期間との重なりを判定
                        hasBreakBetween = this.brokenPeriods.some(
                            period => !(period.end < gapStart || period.start > gapEnd)
                        );
                    }

                    if (hasBreakBetween) {
                        if (current.length > 0) {
                            result.push(current);
                            current = [];
                        }
                    }
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
        <div class="absolute bottom-8 right-12 left-[200px] h-8">
            <template x-for="(point, index) in points" :key="'label-' + point.date">
                <div
                    class="absolute -translate-x-1/2 w-16 h-8 cursor-pointer"
                    :style="{ left: xPercent(index) + '%' }"
                    @mouseenter="hoveredIndex = index"
                    @mouseleave="hoveredIndex = null"
                >
                    <span
                        class="absolute left-1/2 -translate-x-1/2 whitespace-nowrap text-center font-semibold text-slate-400 text-sm transition-all duration-200"
                        :class="hoveredIndex === index
                            ? 'text-blue-950 font-black scale-110'
                            : ''"
                        x-text="point.date.slice(5)"
                    ></span>
                </div>
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
            {{-- X軸ごとのhoverエリア --}}
            <div class="absolute inset-0 z-10 flex pointer-events-none">
                <template x-for="(point, index) in points" :key="'hover-' + point.date">
                    <div
                        class="absolute top-0 bottom-0 pointer-events-auto cursor-pointer"
                        :style="{
                            left: `calc(${xPercent(index)}% - 32px)`,
                            width: '64px'
                        }"
                        @mouseenter="hoveredIndex = index"
                        @mouseleave="hoveredIndex = null"
                    ></div>
                </template>
            </div>
            <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="absolute inset-0 w-full h-full overflow-visible" x-html="segments('temperature').map(seg => `<polyline points=\'${seg}\' fill=\'none\' stroke=\'#f87171\' stroke-width=\'1.5\' vector-effect=\'non-scaling-stroke\' />`).join('')"></svg>

            <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="absolute inset-0 w-full h-full overflow-visible" x-html="segments('pressure').map(seg => `<polyline points=\'${seg}\' fill=\'none\' stroke=\'#60a5fa\' stroke-width=\'1.5\' vector-effect=\'non-scaling-stroke\' />`).join('')"></svg>

            <template x-for="(point, index) in points" :key="'temp-dot-' + point.date">
                <div
                    class="absolute -translate-x-1/2 translate-y-1/2 rounded-full bg-red-400 border-2 border-white shadow transition-all duration-200"
                    :class="hoveredIndex === index
                        ? 'w-5 h-5 ring-4 ring-red-200'
                        : 'w-3 h-3'"
                    :style="{
                        left: xPercent(index) + '%',
                        bottom: yPercent(point.temperature) + '%'
                    }"
                ></div>
            </template>
            <template x-for="(point, index) in points" :key="'pressure-dot-' + point.date">
                <div
                    class="absolute -translate-x-1/2 translate-y-1/2 rounded-full bg-blue-400 border-2 border-white shadow transition-all duration-200"
                    :class="hoveredIndex === index
                        ? 'w-5 h-5 ring-4 ring-blue-200'
                        : 'w-3 h-3'"
                    :style="{
                        left: xPercent(index) + '%',
                        bottom: yPercent(point.pressure) + '%'
                    }"
                ></div>
            </template>

            <template x-if="hoveredIndex !== null">
                <div
                    class="absolute z-30 -translate-x-1/2 bg-white rounded-xl shadow-lg border border-slate-200 px-4 py-3 whitespace-nowrap pointer-events-none"
                    :style="{
                        left: xPercent(hoveredIndex) + '%',
                        top: '12px'
                    }"
                >
                    {{-- 日付 --}}
                    <p
                        class="text-xs font-bold text-blue-950 mb-2 text-center"
                        x-text="points[hoveredIndex].date.slice(5)"
                    ></p>

                    {{-- 温度 --}}
                    <div class="flex items-center gap-2 text-xs">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                        <span class="font-bold text-red-400">
                            温度
                        </span>

                        <span
                            class="font-semibold text-slate-600"
                            x-text="temperatureLabel(points[hoveredIndex].temperature)"
                        ></span>
                    </div>

                    {{-- 水圧 --}}
                    <div class="flex items-center gap-2 text-xs mt-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>
                        <span class="font-bold text-blue-400">
                            水圧
                        </span>

                        <span
                            class="font-semibold text-slate-600"
                            x-text="pressureLabel(points[hoveredIndex].pressure)"
                        ></span>
                    </div>

                    {{-- 吹き出しの三角 --}}
                    <div
                        class="absolute left-1/2 -bottom-1.5 -translate-x-1/2 w-3 h-3 bg-white border-r border-b border-slate-200 rotate-45"
                    ></div>
                </div>
            </template>
        </div>

    </div>

</div>