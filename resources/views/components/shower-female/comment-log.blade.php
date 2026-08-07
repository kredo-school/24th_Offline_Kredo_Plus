<!-- comments コメント欄 -->
<section class="space-y-stack-md">
    <div class="flex justify-between items-center mb-6">
        <button class="ms-6 text-headline-sm font-semibold py-2 px-3 rounded-full hover:bg-blue-500/10 text-blue-700 transition-colors">
            View All
        </button>

        <div class="relative me-6"
            x-data="{ open: false }"
            @mouseenter="open = true"
            @mouseleave="open = false">

            <!-- フィルターボタン -->
            <button
                class="material-symbols-outlined p-2 rounded-full hover:bg-blue-500/10 text-blue-700 transition-colors">
                filter_list
            </button>

            <!-- ドロップダウン -->
            <div
                x-show="open"
                x-transition.origin.top.right
                class="absolute right-0 top-full mt-2 z-50 w-64 bg-white rounded-2xl shadow-xl border border-slate-200">

                <div class="p-6">

                    <h2 class="font-bold mb-4 text-slate-800">
                        表示設定
                    </h2>

                    <div x-data="{ table: 'all' }">

                        <form action="#" method="#" class="flex flex-col gap-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="radio"
                                    name="table"
                                    value="all"
                                    x-model="table">
                                <span>すべて（最新順）</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="radio"
                                    name="table"
                                    value="each"
                                    x-model="table">
                                <span>シャワー別</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="radio"
                                    name="table"
                                    value="preference"
                                    x-model="table">
                                <span>好み</span>
                            </label>
                            
                            {{-- preference 好み　上で「好み」を選択した場合のみに表示される --}}
                            <div
                                x-show="table === 'preference'"
                                x-transition
                            >
                                <hr class="w-full my-2">

                                {{-- temperature 温度 --}}
                                <p class="text-slate-500 text-sm">
                                    温度
                                </p>

                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="temp" id="cold" value="cold">
                                        <span>冷たい</span>
                                    </label>

                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="temp" id="luke" value="luke">
                                        <span>ぬるい</span>
                                    </label>

                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="temp" id="warm" value="warm">
                                        <span>温かい</span>
                                    </label>

                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="temp" id="hot" value="hot">
                                        <span>熱い</span>
                                    </label>
                                </div>

                                {{-- pressure 水圧 --}}
                                <p class="text-slate-500 text-sm mt-2">
                                    水圧
                                </p>

                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="pressure" id="none" value="none">
                                        <span>無し</span>
                                    </label>

                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="pressure" id="weak" value="weak">
                                        <span>弱い</span>
                                    </label>

                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="pressure" id="medium" value="medium">
                                        <span>普通</span>
                                    </label>

                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="pressure" id="strong" value="strong">
                                        <span>強い</span>
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-stack-sm">
        <!-- Log Entry 1 -->
        <div class="bg-white rounded-xl p-4 mb-4 overflow-hidden shadow-lg flex items-center justify-between group hover:border-2 hover:border-blue transition-colors">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600">shower</span>
                </div>

                <div>
                    <p class="font-label-md text-label-md text-on-surface">Shower 04 • Morning session</p>
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Oct 24, 2023 • 08:15 AM</p>
                </div>
            </div>

            <div class="flex flex-col items-end gap-1">
                <div class="flex gap-2">
                    <span class="text-label-sm font-label-md text-primary">熱い</span>
                    <span class="text-label-sm font-label-md text-tertiary">強い</span>
                </div>
                <span class="font-label-sm text-label-sm bg-secondary-container text-on-secondary-container px-2 py-0.5 rounded-full">最適
                </span>
            </div>
        </div>

        <!-- Log Entry 2 -->
        <div class="bg-white rounded-xl p-4 mb-4 overflow-hidden shadow-lg flex items-center justify-between group hover:border-2 hover:border-blue transition-colors">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600">shower</span>
                </div>

                <div>
                    <p class="font-label-md text-label-md text-on-surface">Shower 02 • Evening session</p>
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Oct 23, 2023 • 09:42 PM</p>
                </div>
            </div>

            <div class="flex flex-col items-end gap-1">
                <div class="flex gap-2">
                    <span class="text-label-sm font-label-md text-primary">ぬるい</span>
                    <span class="text-label-sm font-label-md text-tertiary">中間</span>
                </div>
                <span class="font-label-sm text-label-sm bg-surface-container-highest text-on-surface-variant px-2 py-0.5 rounded-full">平均的</span>
            </div>
        </div>

        <!-- Log Entry 3 -->
        <div class="bg-white rounded-xl p-4 mb-4 overflow-hidden shadow-lg flex items-center justify-between group hover:border-2 hover:border-blue transition-colors">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600">shower</span>
                </div>

                <div>
                    <p class="font-label-md text-label-md text-on-surface">Shower 07 • Post-Gym</p>
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Oct 22, 2023 • 07:10 PM</p>
                </div>
            </div>

            <div class="flex flex-col items-end gap-1">
                <div class="flex gap-2">
                    <span class="text-label-sm font-label-md text-primary">温かい</span>
                    <span class="text-label-sm font-label-md text-tertiary">強い</span>
                </div>
                <span class="font-label-sm text-label-sm bg-secondary-container/40 text-on-secondary-container px-2 py-0.5 rounded-full">おすすめ</span>
            </div>
        </div>
    </div>
</section>