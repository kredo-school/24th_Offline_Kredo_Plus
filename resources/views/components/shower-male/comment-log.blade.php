{{-- comments コメント欄 --}}
<section
    class="space-y-stack-md"
    x-data="{
        showerNumber: 'all',
        temperatureLabel: null,
        pressureLabel: null,
        items: [],
        page: 1,
        lastPage: 1,
        total: 0,

        async load(targetPage = 1) {
            this.page = targetPage;
            const params = new URLSearchParams({
                shower_number: this.showerNumber,
                page: this.page,
            });
            if (this.temperatureLabel) params.set('temperature_label', this.temperatureLabel);
            if (this.pressureLabel) params.set('pressure_label', this.pressureLabel);

            const response = await fetch(`{{ route('shower.comments') }}?${params}`);
            const data = await response.json();
            
            // Laravelのページネーション構造(data.data, data.last_page 等)に合わせる
            this.items = data.data ?? data.items; 
            this.lastPage = data.last_page ?? 1;
            this.total = data.total ?? 0;
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
    x-init="load(1)"
>
    {{-- PC表示 --}}
    <div class="hidden sm:flex gap-2 m-6 justify-between">
        {{-- シャワー番号 --}}
        <div class="flex flex-col justify-center gap-1.5">
            <p class="text-slate-500 text-sm font-semibold ms-2">シャワー番号</p>
            <div class="flex gap-1 items-center">
                <button
                    type="button"
                    @click="showerNumber = 'all'; load(1)"
                    :class="showerNumber === 'all' ? 'bg-sky-400 text-white shadow-sm' : 'bg-sky-200/60 text-sky-700 hover:bg-sky-200'"
                    class="rounded-full text-xs font-bold px-3.5 py-1.5 transition-colors"
                >すべて</button>

                @for ($i = 1; $i <= 7; $i++)
                    <button
                        type="button"
                        @click="showerNumber = '{{ $i }}'; load(1)"
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
                        @click="temperatureLabel = (temperatureLabel === option ? null : option); load(1)"
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
                        @click="pressureLabel = (pressureLabel === option ? null : option); load(1)"
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
            <div
                class="bg-white rounded-xl p-4 mb-4 overflow-hidden shadow-lg"
                x-data="{ expanded: false }"
            >
                {{-- PC表示 --}}
                <div class="hidden sm:flex items-center gap-8">
                    <template x-if="item.user_avatar_url">
                        <img :src="item.user_avatar_url" :alt="item.user_name"
                            class="w-12 h-12 rounded-full object-cover shrink-0 bg-slate-100">
                    </template>
                    <template x-if="!item.user_avatar_url">
                        <div class="w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-blue-600">shower</span>
                        </div>
                    </template>

                    <div class="shrink-0 w-10 text-center">
                        <p class="text-2xl font-black text-blue-950 leading-none" x-text="item.shower_number"></p>
                    </div>

                    <div class="shrink-0 whitespace-nowrap">
                        <p class="font-label-sm text-label-sm text-on-surface-variant font-bold" x-text="item.user_name"></p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant" x-text="item.created_at"></p>
                    </div>

                    <div class="flex-1 min-w-0 px-10">
                        <p
                            x-show="item.comment"
                            @click="expanded = !expanded"
                            class="text-sm text-slate-600 cursor-pointer"
                            :class="expanded ? '' : 'truncate'"
                            x-text="item.comment"
                        ></p>
                    </div>

                    <div class="flex gap-2 shrink-0">
                        <span
                            class="rounded-full px-3 py-1.5 text-xs font-bold text-white shadow-sm text-center"
                            :style="{ backgroundColor: temperatureColor(item.temperature_label) }"
                            x-text="item.temperature_label"
                        ></span>
                        <span
                            class="rounded-full px-3 py-1.5 text-xs font-bold text-white shadow-sm text-center"
                            :style="{ backgroundColor: pressureColor(item.pressure_label) }"
                            x-text="item.pressure_label"
                        ></span>
                    </div>
                </div>

                {{-- スマホ表示 --}}
                <div class="sm:hidden">
                    <div class="flex items-center gap-4">
                        <template x-if="item.user_avatar_url">
                            <img :src="item.user_avatar_url" :alt="item.user_name"
                                class="w-12 h-12 rounded-full object-cover shrink-0 bg-slate-100">
                        </template>
                        <template x-if="!item.user_avatar_url">
                            <div class="w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-blue-600">shower</span>
                            </div>
                        </template>

                        <div class="shrink-0 w-10 text-center">
                            <p class="text-2xl font-black text-blue-950 leading-none" x-text="item.shower_number"></p>
                        </div>

                        <div class="shrink-0 whitespace-nowrap">
                            <p class="font-label-sm text-label-sm text-on-surface-variant font-bold" x-text="item.user_name"></p>
                            <p class="font-label-sm text-label-sm text-on-surface-variant" x-text="item.created_at"></p>
                        </div>

                        <div class="flex flex-col gap-1 shrink-0 ml-auto">
                            <span
                                class="rounded-full px-3 py-1 text-[10px] font-bold text-white shadow-sm text-center"
                                :style="{ backgroundColor: temperatureColor(item.temperature_label) }"
                                x-text="item.temperature_label"
                            ></span>
                            <span
                                class="rounded-full px-3 py-1 text-[10px] font-bold text-white shadow-sm text-center"
                                :style="{ backgroundColor: pressureColor(item.pressure_label) }"
                                x-text="item.pressure_label"
                            ></span>
                        </div>
                    </div>

                    <div x-show="item.comment" class="mt-3 pt-3 border-t border-slate-100">
                        <p
                            @click="expanded = !expanded"
                            class="text-sm text-slate-600 cursor-pointer"
                            :class="expanded ? '' : 'truncate'"
                            x-text="item.comment"
                        ></p>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="items.length === 0">
            <p class="text-center text-slate-400 text-sm py-8">投稿がありません</p>
        </template>
    </div>

    {{-- ページネーションコントロール --}}
    <div class="flex justify-center items-center gap-1 mt-4 text-sm font-bold" x-show="lastPage > 1">
        {{-- 最初へ --}}
        <button
            type="button"
            @click="load(1)"
            :disabled="page <= 1"
            class="w-9 h-9 flex items-center justify-center rounded-full text-blue-700 hover:bg-blue-500/10 transition-colors disabled:opacity-30 disabled:hover:bg-transparent"
            title="最初へ"
        >
            <span class="material-symbols-outlined !text-xl">keyboard_double_arrow_left</span>
        </button>

        {{-- 1ページ前へ --}}
        <button
            type="button"
            @click="load(page - 1)"
            :disabled="page <= 1"
            class="w-9 h-9 flex items-center justify-center rounded-full text-blue-700 hover:bg-blue-500/10 transition-colors disabled:opacity-30 disabled:hover:bg-transparent"
            title="前へ"
        >
            <span class="material-symbols-outlined !text-xl">keyboard_arrow_left</span>
        </button>

        {{-- 現在のページ / 最後のページ --}}
        <span class="text-slate-600 px-3 font-black text-base">
            <span x-text="page"></span> / <span x-text="lastPage"></span>
        </span>

        {{-- 1ページ次へ --}}
        <button
            type="button"
            @click="load(page + 1)"
            :disabled="page >= lastPage"
            class="w-9 h-9 flex items-center justify-center rounded-full text-blue-700 hover:bg-blue-500/10 transition-colors disabled:opacity-30 disabled:hover:bg-transparent"
            title="次へ"
        >
            <span class="material-symbols-outlined !text-xl">keyboard_arrow_right</span>
        </button>

        {{-- 最後へ --}}
        <button
            type="button"
            @click="load(lastPage)"
            :disabled="page >= lastPage"
            class="w-9 h-9 flex items-center justify-center rounded-full text-blue-700 hover:bg-blue-500/10 transition-colors disabled:opacity-30 disabled:hover:bg-transparent"
            title="最後へ"
        >
            <span class="material-symbols-outlined !text-xl">keyboard_double_arrow_right</span>
        </button>
    </div>
</section>