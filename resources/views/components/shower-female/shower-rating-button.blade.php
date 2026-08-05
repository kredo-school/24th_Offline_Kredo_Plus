{{-- シャワー情報投稿 --}}
<div x-data="{ open: false }">

    <button
        @click="open = true"
        class="w-auto rounded-full text-center bg-sky-200 text-sky-700 hover:bg-sky-300 transition-colors font-bold p-3 shadow-md hover:shadow-lg"
    >
        シャワー情報を投稿する
    </button>

    <div
        x-show="open"
        x-transition
        class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center"
    >
        <div
            @click.outside="open = false"
            class="relative overflow-hidden bg-white rounded-[24px] p-6 w-106"
        >

            <div class="absolute top-0 inset-x-0 h-1.5 rounded-t-[24px] bg-gradient-to-r from-sky-400 to-brand-blue"></div>

            <h2 class="text-xl font-bold mb-4 text-slate-800 text-center">
                シャワー情報投稿
            </h2>

            <div class="flex justify-center gap-2">
                <form action="#" method="#">
                    <p class="text-slate-500 text-sm font-semibold">シャワー番号</p>
                    <div class="flex items-center mt-1 mb-4 gap-1">
                        @for ($i = 1; $i <= 7; $i++)
                            <div>
                                <input id="shower-{{$i}}" type="radio" name="shower_number" value="{{$i}}" class="peer hidden">
                                <label for="shower-{{$i}}" class="flex rounded-full w-10 h-10 border-2 border-blue-300 text-md font-semibold text-blue-400 items-center justify-center cursor-pointer transition-transform duration-200 hover:scale-110 active:scale-95 peer-checked:bg-blue-300 peer-checked:text-white">{{$i}}</label>
                            </div>
                        @endfor
                    </div>

                    <p class="text-slate-500 text-sm font-semibold">コメント</p>
                    <textarea name="comment" rows="3" class="mt-3 rounded-md border-2 border-slate-300 w-full"></textarea>

                    <div class="flex justify-center gap-2 mt-4">
                        <button
                        @click="open = false"
                        class="px-4 py-2 rounded-full border-[2px] border-gray-300/50 font-bold text-gray-400 hover:shadow-md hover:bg-gray-400/80 hover:text-white transition-all"
                        >
                        キャンセル
                        </button>
                        
                        <button type="submit"
                        class="px-4 py-2 rounded-full border-[2px] border-blue-300 bg-slate-50 text-blue-400 font-bold hover:shadow-md hover:border-blue-400 hover:bg-sky-500 hover:text-slate-50"
                        >
                        投稿する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>