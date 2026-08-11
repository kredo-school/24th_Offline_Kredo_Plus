{{-- line chart 線グラフで各シャワーの状態変化を確認 --}}
<div class="bg-white rounded-2xl p-6 border-none shadow-lg mt-10">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-headline-sm font-bold text-blue-950">
            パフォーマンストレンド
        </h3>

        <div class="relative"
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
                class="absolute right-0 top-full mt-2 z-50 w-[290px] bg-white rounded-2xl shadow-xl border border-slate-200">

                <div class="p-6">

                    <h2 class="font-bold mb-4 text-slate-800">
                        表示設定
                    </h2>

                    <form action="#" method="#" class="flex gap-8">

                        {{-- 左側 --}}
                        <div class="flex-1">
                            <p class="text-slate-500 text-sm font-semibold mb-2">日数</p>

                            <div class="flex flex-col gap-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input
                                        type="radio"
                                        name="line_days"
                                        value="three_days"
                                        x-model="line">
                                    <span>3日間</span>
                                </label>

                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input
                                        type="radio"
                                        name="line_days"
                                        value="7days"
                                        x-model="line">
                                    <span>7日間</span>
                                </label>

                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input
                                        type="radio"
                                        name="line_days"
                                        value="14days"
                                        x-model="line">
                                    <span>14日間</span>
                                </label>
                            </div>
                        </div>

                        {{-- 縦線 --}}
                        <div class="border-l border-slate-300"></div>

                        {{-- 右側 --}}
                        <div class="flex-1">
                            <p class="text-slate-500 text-sm font-semibold mb-2">シャワー番号</p>

                            <div class="flex flex-col gap-2">
                                @for ($i = 1; $i <= 7; $i++)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input
                                            type="radio"
                                            name="line_shower"
                                            value="{{ $i }}"
                                            x-model="line">
                                        <span>{{ $i }}</span>
                                    </label>
                                @endfor
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="relative h-[450px] rounded-xl bg-blue-500/10 p-8">

        <!-- グリッド -->
        <div class="absolute top-14 right-8 bottom-20 left-[180px] grid grid-rows-3">
            @for ($i = 0; $i < 3; $i++)
                <div class="border border-gray-200"></div>
            @endfor
        </div>

        <!-- X軸 -->
        <div class="absolute right-8 left-[180px] bottom-20 border-[1px] border-t border-slate-300"></div>

        <!-- Y軸 -->
        <div class="absolute top-8 bottom-20 left-[180px] border-[1px] border-l border-slate-300"></div>

        <!-- X軸ラベル（グラフ下の余白スペースに配置） -->
        <div class="absolute bottom-8 right-12 left-[200px] flex justify-between text-center font-semibold text-slate-400">
            <span>日付</span>
            <span>日付</span>
            <span>日付</span>
            <span>日付</span>
            <span>日付</span>
        </div>

        <!-- Y軸ラベル（グラフ左の余白スペースに配置） -->
        <div class="absolute top-12 bottom-[72px] left-[50px] flex flex-col justify-between text-center font-semibold text-slate-400">
            <span>熱い・強い</span>
            <span>温かい・普通</span>
            <span>ぬるい・弱い</span>
            <span>冷たい・無し</span>
        </div>
        <!-- 後からデータを配置する場所 -->
        <div class="absolute inset-0" id="plot-area">
        </div>
    </div>
</div>