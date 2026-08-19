@extends('layouts.app')

@section('title', 'IELTS Overview')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-orange-600 transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.strategy.index') }}" class="hover:text-orange-600 transition-colors no-underline">試験概要と学習ストラテジー</a>
        <span class="mx-1">/</span>
        <span class="text-blue-950/90 font-semibold">IELTS</span>
    </x-english.breadcrumb>

    <div class="mb-8">
        <h1 class="text-headline-lg font-bold text-blue-950/90 mb-2">IELTS 概要</h1>
    </div>

    <div x-data="{ activeTab: 'listening' }" class="max-w-4xl">

        {{-- タブナビゲーション --}}
        <div class="flex flex-wrap gap-2 mb-8 border-b border-slate-200 pb-4">
            @foreach([['id'=>'listening','label'=>'Listening'],['id'=>'reading','label'=>'Reading'],['id'=>'writing','label'=>'Writing'],['id'=>'speaking','label'=>'Speaking']] as $tab)
            <button @click="activeTab = '{{ $tab['id'] }}'"
                    :class="activeTab === '{{ $tab['id'] }}' ? 'bg-[#b95827] text-white' : 'bg-surface-container-lowest border border-slate-200 text-blue-950/90 hover:bg-slate-50'"
                    class="px-5 py-2.5 rounded-[0.75rem] font-label-md text-label-md transition-all">
                {{ $tab['label'] }}
            </button>
            @endforeach
        </div>

        {{-- Listening --}}
        <div x-show="activeTab === 'listening'" x-transition>
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 space-y-6">
                <h2 class="text-headline-md font-bold text-blue-950/90">Listening セクション</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-orange-600">40</p>
                        <p class="text-caption text-blue-950/90">問題数</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-orange-600">30分</p>
                        <p class="text-caption text-blue-950/90">試験時間</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-orange-600">0-9</p>
                        <p class="text-caption text-blue-950/90">バンドスコア</p>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-blue-950/90 mb-2">セクション構成</h3>
                    <ul class="space-y-2 text-body-md text-blue-950/90">
                        <li class="flex items-start gap-2"><span class="text-orange-600">•</span> Section 1: 日常会話（2人の会話）</li>
                        <li class="flex items-start gap-2"><span class="text-orange-600">•</span> Section 2: 一般的な会話（1人のスピーチ）</li>
                        <li class="flex items-start gap-2"><span class="text-orange-600">•</span> Section 3: 教育・トレーニング場面の会話</li>
                        <li class="flex items-start gap-2"><span class="text-orange-600">•</span> Section 4: 学術的なモノローグ（大学の講義など）</li>
                    </ul>
                </div>
                <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem]">
                    <p class="font-bold text-orange-600 mb-1">注意点</p>
                    <p class="text-body-md text-blue-950/90">音声は1回のみ再生。問題を事前に読む時間が与えられるので活用しよう。</p>
                </div>
            </div>
        </div>

        {{-- Reading --}}
        <div x-show="activeTab === 'reading'" x-transition>
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 space-y-6">
                <h2 class="text-headline-md font-bold text-blue-950/90">Reading セクション</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-orange-600">40</p>
                        <p class="text-caption text-blue-950/90">問題数</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-orange-600">60分</p>
                        <p class="text-caption text-blue-950/90">試験時間</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-orange-600">3つ</p>
                        <p class="text-caption text-blue-950/90">長文パッセージ</p>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-blue-950/90 mb-2">問題形式</h3>
                    <ul class="space-y-2 text-body-md text-blue-950/90">
                        <li class="flex items-start gap-2"><span class="text-orange-600">•</span> 多肢選択問題</li>
                        <li class="flex items-start gap-2"><span class="text-orange-600">•</span> 穴埋め問題（Summary completion）</li>
                        <li class="flex items-start gap-2"><span class="text-orange-600">•</span> True / False / Not Given</li>
                        <li class="flex items-start gap-2"><span class="text-orange-600">•</span> 見出しマッチング</li>
                    </ul>
                </div>
                <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem]">
                    <p class="font-bold text-orange-600 mb-1">注意点</p>
                    <p class="text-body-md text-blue-950/90">Academic版とGeneral Training版で内容が異なる。スキャニングとスキミングのスキルが重要。</p>
                </div>
            </div>
        </div>

        {{-- Writing --}}
        <div x-show="activeTab === 'writing'" x-transition>
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 space-y-6">
                <h2 class="text-headline-md font-bold text-blue-950/90">Writing セクション</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-orange-600">60分</p>
                        <p class="text-caption text-blue-950/90">試験時間</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-orange-600">2問</p>
                        <p class="text-caption text-blue-950/90">課題数</p>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-blue-950/90 mb-2">Task 構成</h3>
                    <div class="space-y-3">
                        <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem]">
                            <p class="font-bold text-blue-950/90">Task 1（150語以上）</p>
                            <p class="text-body-md text-blue-950/90 mb-3">Academic と General Training で内容が異なります。</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="p-3 bg-surface-container-lowest border border-slate-200 rounded-[0.5rem]">
                                    <p class="text-caption font-bold text-orange-600 uppercase tracking-wider mb-1">Academic</p>
                                    <p class="text-body-md text-blue-950/90">グラフ・図・地図・プロセス図などのデータを描写・説明するレポートを書く</p>
                                </div>
                                <div class="p-3 bg-surface-container-lowest border border-slate-200 rounded-[0.5rem]">
                                    <p class="text-caption font-bold text-orange-600 uppercase tracking-wider mb-1">General Training</p>
                                    <p class="text-body-md text-blue-950/90">与えられた状況に応じて、フォーマル・セミフォーマル・インフォーマルな手紙を書く</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem]">
                            <p class="font-bold text-blue-950/90">Task 2（250語以上）</p>
                            <p class="text-body-md text-blue-950/90">社会問題について意見を述べるエッセイを書く</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem]">
                    <p class="font-bold text-orange-600 mb-1">注意点</p>
                    <p class="text-body-md text-blue-950/90">Task 1はAcademic/General Trainingで課題内容が異なりますが、Task 2のエッセイ課題はどちらも共通です。配点はTask 2の方が大きいため、時間配分は Task 1に約20分、Task 2に約40分を目安にしましょう。</p>
                </div>
            </div>
        </div>

        {{-- Speaking --}}
        <div x-show="activeTab === 'speaking'" x-transition>
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 space-y-6">
                <h2 class="text-headline-md font-bold text-blue-950/90">Speaking セクション</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-orange-600">11-14分</p>
                        <p class="text-caption text-blue-950/90">試験時間</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-orange-600">3パート</p>
                        <p class="text-caption text-blue-950/90">構成</p>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-blue-950/90 mb-2">Part 構成</h3>
                    <div class="space-y-3">
                        <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem]">
                            <p class="font-bold text-blue-950/90">Part 1 (4-5分)</p>
                            <p class="text-body-md text-blue-950/90">自己紹介・身近なトピックについての質問に答える</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem]">
                            <p class="font-bold text-blue-950/90">Part 2 (3-4分)</p>
                            <p class="text-body-md text-blue-950/90">与えられたトピックカードについて1-2分間スピーチする</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem]">
                            <p class="font-bold text-blue-950/90">Part 3 (4-5分)</p>
                            <p class="text-body-md text-blue-950/90">Part 2のトピックに関連した抽象的な質問に答える</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem]">
                    <p class="font-bold text-orange-600 mb-1">評価基準</p>
                    <p class="text-body-md text-blue-950/90">流暢さと一貫性・語彙力・文法の幅と正確性・発音の4項目で評価される。</p>
                </div>
            </div>
        </div>

        <a href="{{ route('english.strategy.index') }}"
           class="block mt-8 py-3 bg-surface-container-lowest rounded-[0.75rem] shadow-sm font-label-md text-label-md text-blue-950/90 hover:bg-slate-50 transition-all no-underline text-center flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            戻る
        </a>

    </div>

</div>
@endsection
