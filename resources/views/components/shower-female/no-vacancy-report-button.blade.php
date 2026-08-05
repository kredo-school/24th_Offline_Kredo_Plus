{{-- 満室報告 --}}
<div x-data="{ open: false }">

    <button
        @click="open = true"
        class="w-auto rounded-full text-center bg-green-400/35 text-green-700 hover:bg-green-300 transition-colors font-bold p-3 shadow-md hover:shadow-lg"
    >
        満室を報告する
    </button>

    <div
        x-show="open"
        x-transition
        class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center"
    >
        <div
            @click.outside="open = false"
            class="relative overflow-hidden bg-white rounded-[24px] p-6 w-96"
        >

            <div class="absolute top-0 inset-x-0 h-1.5 rounded-t-[24px] bg-gradient-to-r from-lime-200 to-green-500"></div>
        
            <h2 class="text-xl font-bold mb-4 text-slate-800 text-center">
                現在満室ですか？
            </h2>

            <div class="flex justify-center gap-2">
                <button
                    @click="open = false"
                    class="px-4 py-2 rounded-full border-[2px] border-gray-300/50 font-bold text-gray-400 hover:shadow-md hover:bg-gray-400/80 hover:text-white transition-all"
                >
                    キャンセル
                </button>

                <button
                    class="px-4 py-2 rounded-full border-[2px] border-green-400 bg-slate-50 text-green-500/90 font-bold hover:shadow-md hover:border-green-300/50 hover:bg-green-500/90 hover:text-slate-50"
                >
                    満室です
                </button>
            </div>
        </div>
    </div>
</div>