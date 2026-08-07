<section>
    <div class="flex items-center justify-between mt-12 mb-6">
        <h3 class="ms-6 text-headline-sm font-bold text-blue-950">トレンドテーブル</h3>

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
                                <span>すべて</span>
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
                                <hr class="my-2">

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

    <!-- Table -->
    <div class="bg-white border border-blue-500/20 rounded-xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse ">
                <thead class="bg-blue-500/20 border-b border-blue-500/20">
                    <tr>
                        <th class="px-4 py-3 font-label-md text-label-md text-blue-950">シャワー番号</th>
                        <th class="px-4 py-3 font-label-md text-label-md text-blue-950">温度</th>
                        <th class="px-4 py-3 font-label-md text-label-md text-blue-950">水圧</th>
                        <th class="px-4 py-3 font-label-md text-label-md text-blue-950"></th>
                    </tr>
                </thead>
                <tbody class="divide-y outline-blue-500/20">
                    <tr class="hover:bg-blue-500/10">
                        <td class="px-4 py-3 font-label-md text-slate-950">04</td>
                        <td class="px-4 py-3 font-body-md text-slate-950">熱い</td>
                        <td class="px-4 py-3 font-body-md text-slate-950">強い</td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 bg-red-500/10 text-red-800 text-label-sm rounded-full">Optimal</span></td>
                    </tr>
                    <tr class="hover:bg-blue-500/10">
                        <td class="px-4 py-3 font-label-md text-slate-950">02</td>
                        <td class="px-4 py-3 font-body-md text-slate-950">ぬるい</td>
                        <td class="px-4 py-3 font-body-md text-slate-950">普通</td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 bg-blue-500/10 text-blue-800 text-label-sm rounded-full">Stable</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>