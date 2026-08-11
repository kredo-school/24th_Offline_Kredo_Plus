{{-- x-y chart 二次元チャートで各シャワーを比較 --}}
<div class="bg-white rounded-2xl p-6 border-none shadow-lg">

    <h3 class="text-headline-sm font-bold text-blue-950 mb-6">
        シャワー状態散布図
    </h3>

    <div class="relative h-[450px] rounded-xl bg-blue-500/10">

        <!-- グリッド -->
        <div class="absolute top-8 right-8 bottom-20 left-24 grid grid-cols-3 grid-rows-3">
            @for ($i = 0; $i < 9; $i++)
                <div class="border border-gray-200"></div>
            @endfor
        </div>

        <!-- X軸 -->
        <div class="absolute right-8 left-24 bottom-20 border-[1px] border-t border-slate-300"></div>

        <!-- Y軸 -->
        <div class="absolute top-8 bottom-20 left-24 border-[1px] border-l border-slate-300"></div>

        <!-- X軸ラベル（グラフ下の余白スペースに配置） -->
        <div class="absolute bottom-8 right-4 left-20 flex justify-between text-l font-semibold text-slate-400">
            <span>冷たい</span>
            <span>ぬるい</span>
            <span>温かい</span>
            <span>熱い</span>
        </div>

        <!-- Y軸ラベル（グラフ左の余白スペースに配置） -->
        <div class="absolute top-6 bottom-[72px] left-8 flex flex-col justify-between text-l font-semibold text-slate-400">
            <span>強い</span>
            <span>普通</span>
            <span>弱い</span>
            <span>無し</span>
        </div>
        <!-- 後からデータを配置する場所 -->
        <div class="absolute inset-0" id="plot-area">
        </div>
    </div>
</div>