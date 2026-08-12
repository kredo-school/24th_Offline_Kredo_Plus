{{-- シャワー状態報告 --}}
@if ($isFull)
    {{-- 満室解除報告 --}}
    <div x-data="{ open: false }">

        <button
            @click="open = true"
            class="w-auto rounded-full text-center bg-red-400/35 text-red-700 hover:bg-red-300 transition-colors font-bold p-3 shadow-md hover:shadow-lg"
        >
            満室を解除する
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
                <div class="absolute top-0 inset-x-0 h-1.5 rounded-t-[24px] bg-gradient-to-r from-orange-200 to-red-500"></div>

                <h2 class="text-xl font-bold mb-4 text-slate-800 text-center">
                    空きが出ましたか？
                </h2>

                <form action="{{ route('shower.capacity.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="vacant">

                    <div class="flex justify-center gap-2">
                        <button
                            type="button"
                            @click="open = false"
                            class="rounded-full px-5 py-2.5 text-sm font-semibold text-slate-400 transition-all hover:bg-slate-100 hover:text-slate-600"
                        >
                            キャンセル
                        </button>

                        <button
                            type="submit"
                            class="rounded-full bg-red-400 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-red-500 hover:shadow-md"
                        >
                            空きました
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@else
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

                <form action="{{ route('shower.capacity.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="full">

                    <div class="flex justify-center gap-2">
                        <button
                            type="button"
                            @click="open = false"
                            class="rounded-full px-5 py-2.5 text-sm font-semibold text-slate-400 transition-all hover:bg-slate-100 hover:text-slate-600"
                        >
                            キャンセル
                        </button>

                        <button
                            type="submit"
                            class="rounded-full bg-green-400 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-green-500 hover:shadow-md"
                        >
                            満室です
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif