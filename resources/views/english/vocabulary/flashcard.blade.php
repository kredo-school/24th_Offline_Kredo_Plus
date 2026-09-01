@extends('layouts.app')

@section('title', 'フラッシュカード - ' . strtoupper(explode('-', $level)[0]) . ' ' . explode('-', $level)[1])

@section('content')
<style>
  .flashcard-inner { transition: transform 0.6s; transform-style: preserve-3d; will-change: transform; }
  .flashcard-inner.flipped { transform: rotateY(180deg); }
  {{-- iOS Safari対策: backface-visibility:hidden な要素はGPUレイヤーが確定するまで
       中の動的テキストが初期描画されず、タップ(=repaint)後にしか表示されないことがあるため、
       translateZ(0) で最初からレイヤーを確定させる --}}
  .flashcard-front, .flashcard-back {
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
  }
  .flashcard-back { -webkit-transform: rotateY(180deg) translateZ(0); transform: rotateY(180deg) translateZ(0); }
</style>

@php
$levelLabel = strtoupper(explode('-', $level)[0]) . ' ' . explode('-', $level)[1];
$wordsJson  = $words->map(fn($w) => [
    'id'         => $w->id,
    'word'       => $w->word,
    'pos'        => $w->part_of_speech,
    'meaning'    => $w->meaning_ja,
    'example'    => $w->example_sentence,
    'example_ja' => $w->example_sentence_ja,
])->values()->all();
@endphp

<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-orange-600 transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.vocabulary.index') }}" class="hover:text-orange-600 transition-colors no-underline">英単語</a>
        <span class="mx-1">/</span>
        <span class="text-blue-950/90 font-semibold">{{ $levelLabel }}</span>
    </x-english.breadcrumb>

    <div x-data="flashcardApp({
            words:             {{ json_encode($wordsJson) }},
            level:             '{{ $level }}',
            favoriteIds:       {{ json_encode($favoriteIds ?? []) }},
            toggleFavoriteUrl: '{{ route('english.vocabulary.favorite') }}',
            progressUrl:       '{{ route('english.vocabulary.progress') }}'
        })">

        {{-- ヘッダー --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-headline-md font-bold text-blue-950/90">{{ $levelLabel }} フラッシュカード</h1>
                <div class="flex items-center gap-4">
                    <span class="text-label-md text-blue-950/90" x-text="`${currentIndex + 1} / ${words.length}`"></span>
                    <span class="flex items-center gap-1 text-label-md text-red-500">
                        <span class="material-symbols-outlined text-base">favorite</span>
                        <span x-text="favoriteIds.length"></span>
                    </span>
                </div>
            </div>
            <div class="w-full bg-slate-100 rounded-[0.75rem] h-2 overflow-hidden">
                <div class="bg-[#b95827] h-full rounded-[0.75rem] transition-all duration-500"
                     :style="`width: ${progress}%`"></div>
            </div>
        </div>

        {{-- 完了メッセージ --}}
        <div x-show="isDone" class="text-center max-w-md mx-auto">
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 mb-6">
                <div class="mb-4"><span class="material-symbols-outlined !text-5xl text-orange-600">celebration</span></div>
                <h2 class="text-headline-lg font-bold text-blue-950/90 mb-2">完了！</h2>
                <p class="text-body-md text-blue-950/90 mb-2">
                    全 <span x-text="words.length"></span> 単語を学習しました
                </p>
                <p class="text-body-md text-blue-950/90">
                    お気に入り: <span x-text="favoriteIds.length"></span>語
                </p>
            </div>
            <div class="flex gap-3">
                <button @click="restart()"
                        class="flex-1 py-3 bg-surface-container-lowest rounded-[0.75rem] shadow-sm font-label-md text-label-md text-blue-950/90 hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">refresh</span>
                    もう一度
                </button>
                <a href="{{ route('english.vocabulary.index') }}"
                   class="flex-1 py-3 bg-[#b95827] text-white rounded-[0.75rem] font-label-md text-label-md hover:opacity-90 transition-all no-underline text-center flex items-center justify-center">
                    レベル選択へ
                </a>
            </div>
        </div>

        {{-- フラッシュカード --}}
        <div x-show="!isDone" class="max-w-xl mx-auto">
            <div style="perspective: 1000px;" class="mb-6 cursor-pointer" @click="flip()">
                <div class="relative h-64 flashcard-inner" :class="{ 'flipped': isFlipped }">
                    {{-- 表面 --}}
                    <div class="flashcard-front absolute inset-0 bg-surface-container-lowest border-2 border-slate-200 rounded-[0.75rem] flex flex-col items-center justify-center p-8">
                        <p class="text-display font-black text-blue-950/90 text-center mb-3" x-text="current.word"></p>
                        <p class="text-caption text-blue-950/90">クリックして意味を確認</p>
                    </div>
                    {{-- 裏面 --}}
                    <div class="flashcard-back absolute inset-0 bg-orange-600/5 border-2 border-orange-600/30 rounded-[0.75rem] flex flex-col justify-center p-8">
                        <span class="bg-orange-600/10 text-orange-600 text-caption font-bold px-3 py-1 rounded-[0.75rem] self-start mb-3" x-text="current.pos"></span>
                        <p class="text-headline-md font-bold text-blue-950/90 mb-3" x-text="current.meaning"></p>
                        <p class="text-body-md text-blue-950/90 font-mono mb-1" x-text="current.example"></p>
                        <p class="text-caption text-blue-950/90" x-text="current.example_ja"></p>
                    </div>
                </div>
            </div>

            {{-- 操作ボタン --}}
            <div class="grid grid-cols-3 gap-3">
                <button @click="toggleFavorite()"
                        :class="isFavorite(current.id)
                            ? 'bg-red-50 border-red-200 text-red-500'
                            : 'bg-surface-container-lowest border-slate-200 text-blue-950/90'"
                        class="py-3 rounded-[0.75rem] border-2 font-label-md text-label-md flex items-center justify-center gap-1 transition-all">
                    <span class="material-symbols-outlined text-base">favorite</span>
                    <span x-text="isFavorite(current.id) ? 'お気に入り済' : 'お気に入り'"></span>
                </button>
                <button @click="markLearned()"
                        class="py-3 bg-green-500 text-white rounded-[0.75rem] font-label-md text-label-md flex items-center justify-center gap-1 hover:bg-green-600 transition-all">
                    <span class="material-symbols-outlined text-base">check_circle</span>
                    覚えた
                </button>
                <button @click="next()"
                        class="py-3 bg-surface-container-lowest border-2 border-slate-200 rounded-[0.75rem] font-label-md text-label-md text-blue-950/90 flex items-center justify-center gap-1 hover:bg-slate-50 transition-all">
                    次へ
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
            </div>
        </div>

    </div>

</div>
@endsection
