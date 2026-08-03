@extends('layouts.app')
 
@section('title', 'female shower')
 
@section('content')

<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-8 md:py-12">
    {{-- usage status  おすすめシャワー表示 --}}
    <section class="relative overflow-hidden rounded-[0.75rem] mb-8 p-8 md:p-10 bg-cover"
              style="background-image: url('{{ asset('images/shower/shower-image.jpg') }}'); background-position: 70% 58%">
        <div class="absolute inset-0 bg-gradient-to-r from-white/80 from-5% via-white/25 via-40% to-transparent to-65% pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-center gap-8 md:order-1">
            <div class="flex-1">
                <h1 class="text-display font-black text-blue-950/90 mb-1">シャワー情報</h1>
                <p class="text-headline-md font-bold text-blue-700 mb-3">Shower Information</p>
                <p class="text-body-md text-blue-950 max-w-lg">
                    好みに応じたおすすめのシャワーをご案内します。
                </p>

                {{-- modals --}}
                <div class="flex gap-5 pt-12 md:order-3">
                    
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

                        {{-- 故障報告 --}}
                        <div x-data="{ open: false }">

                            <button
                                @click="open = true"
                                class="w-auto rounded-full text-center bg-yellow-400/65 text-yellow-700 hover:bg-yellow-300 transition-colors font-bold p-3 shadow-md hover:shadow-lg"
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
                                                        <input id="defected-shower-{{$i}}" type="checkbox" value="{{$i}}" class="peer hidden">
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
                    </div>
            </div>

            <div class="w-full lg:w-[380px] bg-surface-container-lowest rounded-[0.75rem] shadow-md p-6 shrink-0 md:order-2">
                <div class="flex items-center gap-3 mb-4 border-b border-outline-blue-950/30 pb-4">
                    <div class="w-11 h-11 shrink-0 bg-blue-500/10 rounded-[0.75rem] flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600">thumb_up</span>
                    </div>
                    <div class="w-full grid grid-cols-2 text-center">
                        <div class="border-e border-outline-950/30 pe-5">
                            <p class="text-caption text-blue-950 leading-none mb-1">おすすめシャワー</p>
                            <p class="text-headline-md font-black text-blue-950 leading-none">0</p>
                        </div>
                        <div class="ps-5">
                            <p class="text-caption text-blue-950 leading-none mb-1">好みとのマッチ度</p>
                            <p class="text-headline-md font-black text-blue-950 leading-none">100 %</p>
                        </div>
                    </div>
                </div>

                <x-shower-female.temperature-bar

                />

                <x-shower-female.pressure-bar

                />

            
            </div>
        </div>
    </section>

    {{-- usage status シャワー状態をグラフ表示 --}}
    {{-- x-y chart 二次元チャートで各シャワーを比較 --}}
    <section class="bg-white rounded-2xl p-6 border-none shadow-lg">
        <h3 class="text-headline-sm font-bold text-blue-950 mb-6">
            シャワー状態散布図
        </h3>

        <div class="relative h-[450px] rounded-xl bg-slate-100">

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
    </section>

    {{-- line chart 線グラフで各シャワーの状態変化を確認 --}}
    <section class="bg-white rounded-2xl p-6 border-none shadow-lg mt-10">
        <h3 class="text-headline-sm font-bold text-blue-950 mb-6">
            パフォーマンストレンド
        </h3>

        <div class="relative h-[450px] rounded-xl bg-slate-100 p-8">

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
    </section>
</div>
@endsection
