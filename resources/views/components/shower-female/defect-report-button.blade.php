{{-- 故障報告 --}}
<div x-data="{ open: false }">

    <button
        @click="open = true"
        class="w-auto rounded-full text-center bg-yellow-300/40 text-yellow-700 hover:bg-yellow-300 transition-colors font-bold p-3 shadow-md hover:shadow-lg"
    >
        故障を報告する
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

            <div class="absolute top-0 inset-x-0 h-1.5 rounded-t-[24px] bg-gradient-to-r from-yellow-100 to-amber-500"></div>

            <h2 class="text-xl font-bold mb-4 text-slate-800 text-center">
                故障中ですか？
            </h2>

            <div class="flex justify-center gap-2">
                <form action="#" method="#">
                    <p class="text-slate-500 text-sm font-semibold">シャワー番号</p>
                    <div class="flex items-center mt-1 mb-4 gap-1">
                        @for ($i = 1; $i <= 7; $i++)
                            <div>
                                <input id="defected-shower-{{$i}}" type="checkbox" name="defected_shower" value="{{$i}}" class="peer hidden">
                                <label for="defected-shower-{{$i}}" class="flex rounded-full w-10 h-10 border-2 border-amber-300 text-md font-semibold text-amber-300 items-center justify-center cursor-pointer transition-transform duration-200 hover:scale-110 active:scale-95 peer-checked:bg-amber-300 peer-checked:text-white">{{$i}}</label>
                            </div>
                        @endfor
                        
                    </div>

                    <div class="flex justify-center gap-2">
                        <button
                        @click="open = false"
                        class="px-4 py-2 rounded-full border-[2px] border-gray-300/50 font-bold text-gray-400 hover:shadow-md hover:bg-gray-400/80 hover:text-white transition-all"
                        >
                        キャンセル
                        </button>
                        
                        <button type="submit"
                        class="px-4 py-2 rounded-full border-[2px] border-yellow-400 bg-slate-50 text-yellow-500 font-bold hover:shadow-md hover:border-yellow-400 hover:bg-yellow-500/90 hover:text-slate-50"
                        >
                        報告する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>