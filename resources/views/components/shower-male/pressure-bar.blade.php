<div x-data="showerGauge('pressure')">

    <div class="flex items-center gap-2 mb-2">
        <span class="font-bold text-blue-950">水圧</span>
        <button
            type="button"
            @click="$store.showerPriority.toggle('pressure')"
            :class="$store.showerPriority.factor === 'pressure' ? 'text-pink-500' : 'text-slate-300 hover:text-pink-300'"
            :style="$store.showerPriority.factor === 'pressure' ? { fontVariationSettings: '\'FILL\' 1' } : { fontVariationSettings: '\'FILL\' 0' }"
            class="material-symbols-outlined !text-xl translate-y-[1px] transition-colors"
        >
            favorite
        </button>
    </div>

    <div class="relative w-full h-4 rounded-full overflow-hidden">
        <div class="absolute inset-0 bg-gray-200"></div>
        <div
            class="absolute inset-0 rounded-full"
            style="background: linear-gradient(to right, #eff6ff 0%, #93c5fd 33%, #3b82f6 66%, #1e3a8a 100%);"
            :style="{ clipPath: 'inset(0 ' + (100 - percent) + '% 0 0 round 9999px)' }"
        ></div>
    </div>

    <div class="flex justify-between text-sm mt-1">
        <template x-for="lvl in levels" :key="lvl.label">
            <span
                :class="hasData && label === lvl.label ? lvl.color + ' font-bold' : 'text-gray-300'"
                x-text="lvl.label"
            ></span>
        </template>
    </div>

    <p class="text-xs text-gray-400 mt-1" x-show="!hasData">データなし</p>
</div>