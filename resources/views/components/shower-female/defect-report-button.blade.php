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

            <form action="{{ route('shower.malfunction.store') }}" method="POST">
                @csrf

                <p class="text-slate-500 text-sm font-semibold">シャワー番号</p>
                <div class="flex items-center mt-2 mb-4 gap-1">
                    @for ($i = 1; $i <= 7; $i++)
                        <div>
                            <input id="defected-shower-{{$i}}" type="checkbox" name="defected_shower[]" value="{{$i}}" class="peer hidden">
                            <label for="defected-shower-{{$i}}" class="flex rounded-full w-10 h-10 border-2 border-amber-300 text-md font-semibold text-amber-300 items-center justify-center cursor-pointer transition-transform duration-200 hover:scale-110 active:scale-95 peer-checked:bg-amber-300 peer-checked:text-white">{{$i}}</label>
                        </div>
                    @endfor
                </div>

                <p class="text-slate-500 text-sm font-semibold">故障内容</p>
                <textarea
                    name="comment"
                    rows="3"
                    class="mt-2 mb-4 rounded-md border-2 border-slate-300 w-full text-sm p-2"
                    placeholder="例: お湯が出ない、水圧が弱すぎる など"
                ></textarea>
                @error('comment')
                    <p class="text-red-500 text-xs mt-1 mb-2">{{ $message }}</p>
                @enderror

                <div class="flex justify-center gap-2">
                    <button
                        type="button"
                        @click="open = false"
                        class="rounded-full px-5 py-2.5 text-sm font-semibold text-slate-400 transition-all hover:bg-slate-100 hover:text-slate-600"
                    >
                        キャンセル
                    </button>

                    <button type="submit"
                        class="rounded-full bg-yellow-300 px-6 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition-all hover:bg-yellow-400 hover:shadow-md"
                    >
                        報告する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>