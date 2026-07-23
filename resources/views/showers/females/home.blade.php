@extends('layouts.app')
 
@section('title', 'female shower')
 
@section('content')

<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-8 md:py-12">
    {{-- usage status  おすすめシャワー表示 --}}
    <section class="relative overflow-hidden rounded-[0.75rem] mb-8 p-8 md:p-10 bg-cover"
              style="background-image: url('{{ asset('images/shower/shower-image.jpg') }}'); background-position: 70% 58%">
        <div class="absolute inset-0 bg-gradient-to-r from-white/80 from-5% via-white/25 via-40% to-transparent to-65% pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-center gap-8">
            <div class="flex-1">
                <h1 class="text-display font-black text-blue-950/90 mb-1">シャワー情報</h1>
                <p class="text-headline-md font-bold text-blue-700 mb-3">Shower Information</p>
                <p class="text-body-md text-blue-950 max-w-lg">
                    好みに応じたおすすめのシャワーをご案内します。
                </p>
                <div class="grid grid-cols-3 gap-2 pt-12">
                    <a href="" class="w-auto rounded-full text-center bg-green-400/35 text-green-700 hover:bg-green-300 transition-colors font-bold p-3">満室を報告する</a>
                    <a href="" class="w-auto rounded-full text-center bg-yellow-400/65 text-yellow-700 hover:bg-yellow-300 transition-colors font-bold p-3">故障を報告する</a>
                </div>
            </div>

            <div class="w-full lg:w-[380px] bg-surface-container-lowest rounded-[0.75rem] shadow-md p-6 shrink-0">
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
</div>
@endsection
