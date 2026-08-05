{{-- line chart 線グラフで各シャワーの状態変化を確認 --}}
<div class="bg-white rounded-2xl p-6 border-none shadow-lg mt-10">
    <h3 class="text-headline-sm font-bold text-blue-950 mb-6">
        パフォーマンストレンド
    </h3>

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