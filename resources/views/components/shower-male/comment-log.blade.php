{{-- comments コメント欄 --}}
<section
    class="space-y-stack-md"
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
    

    <div class="flex gap-2 m-6 justify-between">

        {{-- シャワー番号 --}}
        <div class="flex flex-col justify-center gap-1.5">
            <p class="text-slate-500 text-sm font-semibold ms-2">シャワー番号</p>
            <div class="flex gap-1 items-center">
                <button
                    type="button"
                    @click="showerNumber = 'all'; load(showingAll)"
                    :class="showerNumber === 'all' ? 'bg-sky-400 text-white shadow-sm' : 'bg-sky-200/60 text-sky-700 hover:bg-sky-200'"
                    class="rounded-full text-xs font-bold px-3.5 py-1.5 transition-colors"
                >すべて</button>

                @for ($i = 1; $i <= 7; $i++)
                    <button
                        type="button"
                        @click="showerNumber = '{{ $i }}'; load(showingAll)"
                        :class="showerNumber === '{{ $i }}' ? 'bg-sky-400 text-white shadow-sm' : 'bg-sky-200/60 text-sky-700 hover:bg-sky-200'"
                        class="rounded-full w-8 h-8 text-xs font-bold transition-colors"
                    >{{ $i }}</button>
                @endfor
            </div>
        </div>

        {{-- 温度 --}}
        <div class="flex flex-col justify-center gap-1.5">
            <p class="text-slate-500 text-sm font-semibold ms-2">温度</p>
            <div class="flex gap-1 items-center">
                <template x-for="option in ['冷たい', 'ぬるい', '温かい', '熱い']" :key="'temp-' + option">
                    <button
                        type="button"
                        @click="temperatureLabel = (temperatureLabel === option ? null : option); load(showingAll)"
                        :class="temperatureLabel === option ? 'bg-rose-400 text-white shadow-sm' : 'bg-rose-200/60 text-rose-700 hover:bg-rose-200'"
                        class="rounded-full text-xs font-bold px-3.5 py-1.5 transition-colors"
                        x-text="option"
                    ></button>
                </template>
            </div>
        </div>

        {{-- 水圧 --}}
        <div class="flex flex-col justify-center gap-1.5">
            <p class="text-slate-500 text-sm font-semibold ms-2">水圧</p>
            <div class="flex gap-1 justify-end">
                <template x-for="option in ['無し', '弱い', '普通', '強い']" :key="'pressure-' + option">
                    <button
                        type="button"
                        @click="pressureLabel = (pressureLabel === option ? null : option); load(showingAll)"
                        :class="pressureLabel === option ? 'bg-sky-500 text-white shadow-sm' : 'bg-sky-200/60 text-sky-700 hover:bg-sky-200'"
                        class="rounded-full text-xs font-bold px-3.5 py-1.5 transition-colors"
                        x-text="option"
                    ></button>
                </template>
            </div>
        </div>

    </div>
    

    


    <div class="space-y-stack-sm">
        <template x-for="item in items" :key="item.id">
            <div class="bg-white rounded-xl p-4 mb-4 overflow-hidden shadow-lg flex items-center gap-4">

                {{-- アイコン --}}
                <div class="w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-blue-600">shower</span>
                </div>

                {{-- シャワー番号(大きく、数字のみ) --}}
                <div class="shrink-0 w-10 text-center">
                    <p class="text-2xl font-black text-blue-950 leading-none" x-text="item.shower_number"></p>
                </div>

                {{-- 投稿日時 --}}
                <div class="shrink-0 whitespace-nowrap">
                    <p class="font-label-sm text-label-sm text-on-surface-variant" x-text="item.created_at"></p>
                </div>

                {{-- 温度・水圧ラベル(バッジ表示) --}}
                <div class="flex gap-2 shrink-0">
                    <span
                        class="rounded-full px-3 py-1.5 text-xs font-bold text-white shadow-sm"
                        :style="{ backgroundColor: temperatureColor(item.temperature_label) }"
                        x-text="item.temperature_label"
                    ></span>
                    <span
                        class="rounded-full px-3 py-1.5 text-xs font-bold text-white shadow-sm"
                        :style="{ backgroundColor: pressureColor(item.pressure_label) }"
                        x-text="item.pressure_label"
                    ></span>
                </div>

                {{-- コメント本文 --}}
                <div class="flex-1 min-w-0 px-2">
                    <p x-show="item.comment" x-text="item.comment" class="text-sm text-slate-600 truncate"></p>
                </div>

            </div>
        </template>

        <template x-if="items.length === 0">
            <p class="text-center text-slate-400 text-sm py-8">投稿がありません</p>
        </template>
    </div>

    <div class="flex justify-center mt-2" x-show="total > 5">
        <button
            type="button"
            @click="load(!showingAll)"
            class="text-headline-sm font-semibold py-2 px-3 rounded-full hover:bg-blue-500/10 text-blue-700 transition-colors"
        >
            <span x-text="showingAll ? '閉じる' : 'すべてを見る'"></span>
        </button>
    </div>
</section>