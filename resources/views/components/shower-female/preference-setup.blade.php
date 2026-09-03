{{-- シャワーの好み --}}
<div x-data="{ open: false }">

    <button
        @click="open = true"
        class="mt-2 text-caption text-sky-600 hover:text-sky-800 transition-colors"
    >
        {{ auth()->user()->preferred_temperature_label }}　{{ auth()->user()->preferred_pressure_label }}
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition
        class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center"
    >
        <div
            @click.outside="open = false"
            class="relative overflow-hidden bg-white rounded-[24px] p-6 w-full max-w-sm mx-4"
        >

            <div class="absolute top-0 inset-x-0 h-1.5 rounded-t-[24px] bg-gradient-to-r from-rose-200 to-rose-600"></div>

            <h2 class="text-xl font-bold text-blue-950 text-center mb-6">
                お好みの温度・水圧
            </h2>

            <form action="{{ route('shower.preference.update') }}" method="POST">
                @csrf

                {{-- 温度 --}}
                <div class="mb-6">
                    <p class="mb-2 text-sm font-bold text-slate-600">
                        温度
                    </p>

                    <div class="grid grid-cols-4 gap-2">

                        {{-- 冷たい --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="temperature" value="冷たい" class="peer hidden"
                                {{ auth()->user()->preferred_temperature_label === '冷たい' ? 'checked' : '' }}>

                            <span class="flex items-center justify-center rounded-xl border border-slate-200
                                        bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-500
                                        transition-all
                                        hover:bg-slate-100
                                        peer-checked:border-[#60a5fa]
                                        peer-checked:bg-[#60a5fa]
                                        peer-checked:text-white">
                                冷たい
                            </span>
                        </label>

                        {{-- ぬるい --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="temperature" value="ぬるい" class="peer hidden"
                                {{ auth()->user()->preferred_temperature_label === 'ぬるい' ? 'checked' : '' }}>

                            <span class="flex items-center justify-center rounded-xl border border-slate-200
                                        bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-500
                                        transition-all
                                        hover:bg-slate-100
                                        peer-checked:border-[#34d399]
                                        peer-checked:bg-[#34d399]
                                        peer-checked:text-white">
                                ぬるい
                            </span>
                        </label>

                        {{-- 温かい --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="temperature" value="温かい" class="peer hidden"
                                {{ auth()->user()->preferred_temperature_label === '温かい' ? 'checked' : '' }}>

                            <span class="flex items-center justify-center rounded-xl border border-slate-200
                                        bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-500
                                        transition-all
                                        hover:bg-slate-100
                                        peer-checked:border-[#fbbf24]
                                        peer-checked:bg-[#fbbf24]
                                        peer-checked:text-white">
                                温かい
                            </span>
                        </label>

                        {{-- 熱い --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="temperature" value="熱い" class="peer hidden"
                                {{ auth()->user()->preferred_temperature_label === '熱い' ? 'checked' : '' }}>

                            <span class="flex items-center justify-center rounded-xl border border-slate-200
                                        bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-500
                                        transition-all
                                        hover:bg-slate-100
                                        peer-checked:border-[#ef4444]
                                        peer-checked:bg-[#ef4444]
                                        peer-checked:text-white">
                                熱い
                            </span>
                        </label>
                    </div>
                    @error('temperature')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>


                {{-- 水圧 --}}
                <div class="mb-6">
                    <p class="mb-2 text-sm font-bold text-slate-600">
                        水圧
                    </p>

                    <div class="grid grid-cols-3 gap-2">

                        {{-- 弱い --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="pressure" value="弱い" class="peer hidden"
                                {{ auth()->user()->preferred_pressure_label === '弱い' ? 'checked' : '' }}>

                            <span class="flex items-center justify-center rounded-xl border border-slate-200
                                        bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-500
                                        transition-all
                                        hover:bg-slate-100
                                        peer-checked:border-[#93c5fd]
                                        peer-checked:bg-[#93c5fd]
                                        peer-checked:text-white">
                                弱い
                            </span>
                        </label>

                        {{-- 普通 --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="pressure" value="普通" class="peer hidden"
                                {{ auth()->user()->preferred_pressure_label === '普通' ? 'checked' : '' }}>

                            <span class="flex items-center justify-center rounded-xl border border-slate-200
                                        bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-500
                                        transition-all
                                        hover:bg-slate-100
                                        peer-checked:border-[#3b82f6]
                                        peer-checked:bg-[#3b82f6]
                                        peer-checked:text-white">
                                普通
                            </span>
                        </label>

                        {{-- 強い --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="pressure" value="強い" class="peer hidden"
                                {{ auth()->user()->preferred_pressure_label === '強い' ? 'checked' : '' }}>

                            <span class="flex items-center justify-center rounded-xl border border-slate-200
                                        bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-500
                                        transition-all
                                        hover:bg-slate-100
                                        peer-checked:border-[#1e3a8a]
                                        peer-checked:bg-[#1e3a8a]
                                        peer-checked:text-white">
                                強い
                            </span>
                        </label>

                    </div>
                    @error('pressure')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>


                {{-- ボタン --}}
                <div class="flex justify-center gap-3 pt-5">
                    <button
                        type="button"
                        @click="open = false"
                        class="rounded-full px-5 py-2.5 text-sm font-semibold text-slate-400 transition-all hover:bg-slate-100 hover:text-slate-600"
                    >
                        キャンセル
                    </button>

                    <button
                        type="submit"
                        class="rounded-full bg-rose-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-rose-600 hover:shadow-md"
                    >
                        変更する
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>