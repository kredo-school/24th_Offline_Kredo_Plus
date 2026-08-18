@extends('layouts.app')

@section('title', '語彙クイズ')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-orange-600 transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.quiz.index') }}" class="hover:text-orange-600 transition-colors no-underline">クイズ</a>
        <span class="mx-1">/</span>
        <span class="text-blue-950/90 font-semibold">語彙クイズ</span>
    </x-english.breadcrumb>

    <div x-data="quizVocabularyApp({
            questions: {{ json_encode($questions) }},
            submitUrl: '{{ route('english.quiz.vocabulary.submit') }}'
        })">

        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-headline-md font-bold text-blue-950/90">語彙クイズ</h1>
                <span class="text-label-md text-blue-950/90"
                      x-text="`問題 ${currentIndex + 1} / ${questions.length}`"></span>
            </div>
            <div class="w-full bg-slate-100 rounded-[0.75rem] h-2 overflow-hidden">
                <div class="bg-[#b95827] h-full rounded-[0.75rem] transition-all duration-500"
                     :style="`width: ${progressPercent}%`"></div>
            </div>
        </div>

        <div x-show="!isComplete" class="max-w-2xl mx-auto">
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 mb-6 text-center">
                <p class="text-caption text-blue-950/90 mb-2">次の単語の意味は？</p>
                <p class="text-display font-black text-orange-600" x-text="current.word"></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
                <template x-for="(option, idx) in current.options" :key="idx">
                    <button
                        @click="selectOption(idx)"
                        :disabled="isAnswered"
                        :class="optionClass(idx)"
                        class="p-4 rounded-[0.75rem] border-2 text-left font-label-md transition-all"
                    >
                        <span x-text="option"></span>
                        <span x-show="isAnswered && option === current.correct" class="ml-2">✅</span>
                        <span x-show="isAnswered && selectedOption === idx && option !== current.correct" class="ml-2">❌</span>
                    </button>
                </template>
            </div>

            <div x-show="!isAnswered">
                <button @click="submitAnswer()"
                        :disabled="selectedOption === null"
                        :class="selectedOption !== null
                            ? 'bg-[#b95827] text-white hover:opacity-90'
                            : 'bg-slate-50 text-blue-950/90 cursor-not-allowed'"
                        class="w-full py-3 rounded-[0.75rem] font-label-md text-label-md transition-all">
                    回答する
                </button>
            </div>
            <div x-show="isAnswered">
                <button @click="nextQuestion()"
                        :disabled="isLoading"
                        class="w-full py-3 bg-[#b95827] text-white rounded-[0.75rem] font-label-md text-label-md hover:opacity-90 transition-all flex items-center justify-center gap-2">
                    <span x-show="isLoading" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                    <span x-text="currentIndex < questions.length - 1 ? '次の問題' : '結果を見る'"></span>
                    <span x-show="!isLoading" class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
            </div>
        </div>

        <div x-show="isComplete" class="text-center max-w-md mx-auto">
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 mb-6">
                <div class="text-5xl mb-4">🎉</div>
                <h2 class="text-headline-lg font-bold text-blue-950/90 mb-2">クイズ完了！</h2>
                <p class="text-body-md text-blue-950/90 mb-2">スコア</p>
                <p class="text-display font-black text-orange-600" x-text="`${score} / ${questions.length}`"></p>
                <p class="text-body-md text-orange-600 font-bold mt-3" x-text="`+${gainedXp} XP 獲得！`"></p>
            </div>
            <div class="flex gap-3">
                <button @click="restart()"
                        class="flex-1 py-3 bg-surface-container-lowest rounded-[0.75rem] shadow-sm font-label-md text-label-md text-blue-950/90 hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">refresh</span>
                    もう一度
                </button>
                <a href="{{ route('english.quiz.index') }}"
                   class="flex-1 py-3 bg-[#b95827] text-white rounded-[0.75rem] font-label-md text-label-md hover:opacity-90 transition-all no-underline text-center flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    戻る
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
