@extends('layouts.app')

@section('title', "TOEIC Part {$part} - 練習問題")

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.toeic.index') }}" class="hover:text-primary transition-colors no-underline">TOEIC</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">Part {{ $part }} 練習問題</span>
    </x-english.breadcrumb>

    <div x-data="toeicPractice({
            questions:   {{ json_encode($questionsJson) }},
            part:        {{ $part }},
            submitUrl:   '{{ route('english.toeic.answer',   $part) }}',
            completeUrl: '{{ route('english.toeic.complete', $part) }}',
            resultUrl:   '{{ route('english.toeic.result',   $part) }}'
        })">

        {{-- プログレスバー --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-headline-md font-bold text-on-surface">Part {{ $part }} 練習問題</h1>
                <span class="text-label-md text-on-surface-variant font-semibold"
                      x-text="`問題 ${currentIndex + 1} / ${questions.length}`"></span>
            </div>
            <div class="w-full bg-surface-container-high rounded-[0.75rem] h-2 overflow-hidden">
                <div class="bg-primary h-full rounded-[0.75rem] transition-all duration-500"
                     :style="`width: ${progressPercent}%`"></div>
            </div>
        </div>

        {{-- クイズ表示 --}}
        <div x-show="!isComplete">
            {{-- 長文パッセージ（Part6/7） --}}
            <template x-if="current.passage">
                <div class="bg-surface-container rounded-[0.75rem] shadow-sm p-8 mb-6 max-w-3xl mx-auto max-h-[420px] overflow-y-auto">
                    <p class="text-label-md font-bold text-primary mb-4" x-text="current.passage.title"></p>
                    <template x-for="(doc, docIndex) in current.passage.documents" :key="docIndex">
                        <div class="mb-5 last:mb-0">
                            <p class="font-semibold text-on-surface mb-2" x-show="doc.heading" x-text="doc.heading"></p>
                            <p class="whitespace-pre-line text-body-md text-on-surface-variant leading-relaxed" x-text="doc.body"></p>
                        </div>
                    </template>
                </div>
            </template>

            {{-- 写真描写問題の画像 + 音声リピート（Part1） --}}
            <template x-if="current.image_url">
                <div class="max-w-3xl mx-auto mb-6">
                    <img :src="current.image_url" alt="TOEIC Part1 photograph"
                         class="w-full max-h-[420px] object-contain rounded-[0.75rem] shadow-sm mb-3 bg-surface-container">
                    <button
                        @click="repeatAudio()"
                        type="button"
                        class="w-full py-2.5 bg-primary/10 text-primary rounded-[0.75rem] font-label-md text-label-md flex items-center justify-center gap-2 hover:bg-primary/20 transition-all"
                    >
                        <span class="material-symbols-outlined text-sm">replay</span>
                        Repeat Audio (A–D)
                    </button>
                </div>
            </template>

            {{-- 質問応答問題（Part2：本番同様、質問文・選択肢とも一切表示せず音声のみで判断） --}}
            @if($part == 2)
            <div class="max-w-3xl mx-auto mb-6">
                <div class="bg-surface-container rounded-[0.75rem] shadow-sm p-8 flex flex-col items-center text-center gap-4">
                    <span class="material-symbols-outlined text-5xl text-primary">headphones</span>
                    <p class="text-body-md text-on-surface-variant leading-relaxed max-w-xl">
                        Directions: You will hear a question or statement and three responses spoken in English.
                        They will not be printed in your test book and will be spoken only one time.
                        Select the best response to the question or statement and mark the letter (A), (B), or (C).
                    </p>
                    <button
                        @click="repeatAudio()"
                        type="button"
                        class="w-full md:w-auto px-8 py-2.5 bg-primary/10 text-primary rounded-[0.75rem] font-label-md text-label-md flex items-center justify-center gap-2 hover:bg-primary/20 transition-all"
                    >
                        <span class="material-symbols-outlined text-sm">replay</span>
                        Repeat Audio
                    </button>
                </div>
            </div>
            @endif

            {{-- 問題文カード（Part2は本番同様、問題文を画面に表示しない） --}}
            @if($part != 2)
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 mb-6 max-w-3xl mx-auto">
                <p class="text-body-lg text-on-surface font-semibold leading-relaxed"
                   x-text="current.question_text"></p>
            </div>
            @endif

            {{-- 4択ボタン（Part5〜7：本文つき） --}}
            @if($part != 2)
            <template x-if="!current.image_url">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-w-3xl mx-auto mb-6">
                    <template x-for="option in current.options" :key="option.id">
                        <button
                            @click="selectOption(option.id)"
                            :disabled="isAnswered"
                            :class="optionClass(option.id)"
                            class="p-4 rounded-[0.75rem] border-2 text-left font-label-md transition-all"
                        >
                            <span class="font-bold uppercase mr-2" x-text="option.label + '.'"></span>
                            <span x-text="option.option_text"></span>
                            <span x-show="isAnswered && option.id === correctOptionId" class="ml-2">✅</span>
                            <span x-show="isAnswered && selectedId === option.id && option.id !== correctOptionId" class="ml-2">❌</span>
                        </button>
                    </template>
                </div>
            </template>
            @endif

            {{-- 4択ボタン（Part1：本番同様、文字は表示せず音声のみで判断） --}}
            <template x-if="current.image_url">
                <div class="grid grid-cols-4 gap-3 max-w-3xl mx-auto mb-6">
                    <template x-for="option in current.options" :key="option.id">
                        <button
                            @click="selectOption(option.id)"
                            :disabled="isAnswered"
                            :class="optionClass(option.id)"
                            class="p-6 rounded-[0.75rem] border-2 text-center font-label-md transition-all"
                        >
                            <span class="block text-headline-md font-bold uppercase" x-text="option.label"></span>
                            <span x-show="isAnswered && option.id === correctOptionId" class="block mt-1">✅</span>
                            <span x-show="isAnswered && selectedId === option.id && option.id !== correctOptionId" class="block mt-1">❌</span>
                        </button>
                    </template>
                </div>
            </template>

            {{-- 3択ボタン（Part2：本番同様、文字は表示せず音声のみで判断） --}}
            @if($part == 2)
            <div class="grid grid-cols-3 gap-3 max-w-md mx-auto mb-6">
                <template x-for="option in current.options" :key="option.id">
                    <button
                        @click="selectOption(option.id)"
                        :disabled="isAnswered"
                        :class="optionClass(option.id)"
                        class="p-6 rounded-[0.75rem] border-2 text-center font-label-md transition-all"
                    >
                        <span class="block text-headline-md font-bold uppercase" x-text="option.label"></span>
                        <span x-show="isAnswered && option.id === correctOptionId" class="block mt-1">✅</span>
                        <span x-show="isAnswered && selectedId === option.id && option.id !== correctOptionId" class="block mt-1">❌</span>
                    </button>
                </template>
            </div>
            @endif

            {{-- 回答ボタン / フィードバック --}}
            <div class="max-w-3xl mx-auto">
                <div x-show="!isAnswered">
                    <button
                        @click="submitAnswer()"
                        :disabled="!selectedId || isLoading"
                        :class="selectedId && !isLoading
                            ? 'bg-primary text-on-primary hover:opacity-90'
                            : 'bg-surface-container text-on-surface-variant cursor-not-allowed'"
                        class="w-full py-3 rounded-[0.75rem] font-label-md text-label-md transition-all flex items-center justify-center gap-2">
                        <span x-show="isLoading" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                        <span x-text="isLoading ? '送信中...' : '回答する'"></span>
                    </button>
                </div>
                <div x-show="isAnswered" class="space-y-4">
                    <div :class="isCorrect ? 'bg-green-50 border-green-200' : 'bg-error-container/30 border-error/20'"
                         class="border rounded-[0.5rem] p-4">
                        <p class="font-bold mb-1" :class="isCorrect ? 'text-green-700' : 'text-error'">
                            <span x-text="isCorrect ? '✅ 正解！' : '❌ 不正解'"></span>
                        </p>
                        <p class="text-body-md text-on-surface" x-text="explanation"></p>

                        {{-- Part1：回答後に音声の文章（A〜D）を表示し、リピート再生と合わせて振り返れるようにする --}}
                        <template x-if="current.image_url">
                            <div class="mt-3 pt-3 border-t border-outline-variant/40 space-y-1.5">
                                <template x-for="option in current.options" :key="option.id">
                                    <p class="text-body-md" :class="option.id === correctOptionId ? 'text-green-700 font-semibold' : 'text-on-surface-variant'">
                                        <span class="font-bold uppercase mr-1" x-text="option.label + '.'"></span>
                                        <span x-text="option.option_text"></span>
                                        <span x-show="option.id === correctOptionId">✅</span>
                                        <span x-show="selectedId === option.id && option.id !== correctOptionId">❌</span>
                                    </p>
                                </template>
                            </div>
                        </template>

                        {{-- Part2：回答後に質問文と応答（A〜C）を表示し、リピート再生と合わせて振り返れるようにする --}}
                        @if($part == 2)
                        <div class="mt-3 pt-3 border-t border-outline-variant/40 space-y-1.5">
                            <p class="text-body-md font-semibold text-on-surface mb-2">"<span x-text="current.question_text"></span>"</p>
                            <template x-for="option in current.options" :key="option.id">
                                <p class="text-body-md" :class="option.id === correctOptionId ? 'text-green-700 font-semibold' : 'text-on-surface-variant'">
                                    <span class="font-bold uppercase mr-1" x-text="option.label + '.'"></span>
                                    <span x-text="option.option_text"></span>
                                    <span x-show="option.id === correctOptionId">✅</span>
                                    <span x-show="selectedId === option.id && option.id !== correctOptionId">❌</span>
                                </p>
                            </template>
                        </div>
                        @endif
                    </div>
                    <button @click="nextQuestion()"
                            :disabled="isLoading"
                            class="w-full py-3 bg-primary text-on-primary rounded-[0.75rem] font-label-md text-label-md hover:opacity-90 transition-all flex items-center justify-center gap-2">
                        <span x-text="currentIndex < questions.length - 1 ? '次の問題' : '結果を見る'"></span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                </div>
            </div>

            <div class="mt-6 flex justify-end max-w-3xl mx-auto">
                <a href="{{ route('english.toeic.index') }}"
                   class="px-6 py-2.5 bg-orange-600 text-white font-bold rounded-[0.5rem] shadow-sm hover:bg-orange-700 transition-colors text-base no-underline">
                    Quit Practice
                </a>
            </div>
        </div>

        {{-- 全問完了（complete() が form submit するため、ここは loading 表示のみ） --}}
        <div x-show="isComplete" class="text-center max-w-md mx-auto">
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 mb-6">
                <div class="text-5xl mb-4">🎉</div>
                <h2 class="text-headline-lg font-bold text-on-surface mb-2">完了！</h2>
                <p class="text-body-md text-on-surface-variant mb-4">スコア</p>
                <p class="text-display font-black text-primary" x-text="`${score} / ${questions.length}`"></p>
                <p class="text-caption text-on-surface-variant mt-4">結果を保存中...</p>
            </div>
        </div>

    </div>

</div>
@endsection
