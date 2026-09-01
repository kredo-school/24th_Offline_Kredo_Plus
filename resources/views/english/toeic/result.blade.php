@extends('layouts.app')

@section('title', "TOEIC Part {$part} - 結果")

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-orange-600 transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.toeic.index') }}" class="hover:text-orange-600 transition-colors no-underline">TOEIC</a>
        <span class="mx-1">/</span>
        <span class="text-blue-950/90 font-semibold">Part {{ $part }} 結果</span>
    </x-english.breadcrumb>

    <div class="max-w-2xl mx-auto space-y-6">

        {{-- スコアサマリーカード --}}
        <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 text-center">
            <div class="mb-4">
                <span class="material-symbols-outlined !text-5xl text-orange-600">
                    @if($result->accuracy >= 80) workspace_premium @elseif($result->accuracy >= 60) thumb_up @else trending_up @endif
                </span>
            </div>
            <h1 class="text-headline-lg font-bold text-blue-950/90 mb-6">Part {{ $part }} 完了！</h1>

            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="text-center">
                    <p class="text-display font-black text-orange-600">{{ $result->correct_count }}/{{ $result->total_questions }}</p>
                    <p class="text-caption text-blue-950/90">正答数</p>
                </div>
                <div class="text-center">
                    <p class="text-display font-black text-orange-600">{{ $result->accuracy }}%</p>
                    <p class="text-caption text-blue-950/90">正答率</p>
                </div>
                <div class="text-center">
                    <p class="text-display font-black text-orange-600">+{{ $result->xp_gained }}</p>
                    <p class="text-caption text-blue-950/90">XP 獲得</p>
                </div>
            </div>

            <div class="bg-orange-600/10 rounded-[0.5rem] p-4 mb-4">
                <p class="text-label-md font-bold text-orange-600 mb-1">獲得 XP</p>
                <p class="text-headline-lg font-black text-orange-600">+{{ $result->xp_gained }} XP</p>
            </div>
        </div>

        {{-- XPバー --}}
        <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-6">
            <x-english.xp-bar
                :level="$levelInfo['level']"
                :currentXp="$levelInfo['xp_in_level']"
                :nextXp="500" />
        </div>

        {{-- 間違えた問題 --}}
        @if(count($wrongAnswers) > 0)
        <div x-data="{ open: false }" class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm overflow-hidden">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between p-6 text-left hover:bg-slate-50 transition-colors">
                <span class="font-bold text-blue-950/90 flex items-center gap-2">
                    <span class="material-symbols-outlined text-error">error_outline</span>
                    間違えた問題 ({{ count($wrongAnswers) }}問)
                </span>
                <span class="material-symbols-outlined text-blue-950/90 transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="open" x-transition class="px-6 pb-6 space-y-4 border-t border-slate-200">
                @foreach($wrongAnswers as $wq)
                <div class="p-4 bg-error-container/20 rounded-[0.5rem] border border-error/20 mt-4">
                    @if(!empty($wq['image_url']))
                        <img src="{{ $wq['image_url'] }}" alt="問題の写真"
                             class="w-full rounded-[0.5rem] border border-slate-200 mb-3">
                    @elseif(!empty($wq['question']))
                        <p class="text-body-md text-blue-950/90 font-semibold mb-2">{{ $wq['question'] }}</p>
                    @endif

                    @if(!empty($wq['options']))
                    <div class="mb-3 space-y-1">
                        @foreach($wq['options'] as $opt)
                        @php
                            $isCorrect = $opt['is_correct'];
                            $isYours   = $opt['id'] === ($wq['your_option_id'] ?? null);
                        @endphp
                        <div @class([
                            'text-caption flex items-start gap-2 rounded-[0.375rem] px-2 py-1',
                            'bg-green-100 text-green-800' => $isCorrect,
                            'bg-error/10 text-error' => $isYours && !$isCorrect,
                            'text-blue-950/90' => !$isCorrect && !$isYours,
                        ])>
                            <span class="font-bold shrink-0">{{ $opt['label'] }}.</span>
                            <span class="flex-1">{{ $opt['option_text'] }}</span>
                            @if($isCorrect)
                                <span class="shrink-0 font-semibold">正解</span>
                            @elseif($isYours)
                                <span class="shrink-0 font-semibold">あなたの回答</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                        <p class="text-caption text-error mb-1">あなたの回答: {{ $wq['your_answer'] }}</p>
                        <p class="text-caption text-green-700 mb-2">正解: {{ $wq['correct_answer'] }}</p>
                    @endif

                    @if(!empty($wq['explanation']))
                        <p class="text-caption text-blue-950/90">{{ $wq['explanation'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ボタン --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('english.toeic.slides', $part) }}"
               class="flex-1 py-3 bg-surface-container-lowest rounded-[0.75rem] shadow-sm font-label-md text-label-md text-blue-950/90 hover:bg-slate-50 transition-all no-underline text-center flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">refresh</span>
                もう一度挑戦
            </a>
            <a href="{{ route('english.toeic.index') }}"
               class="flex-1 py-3 bg-[#b95827] text-white rounded-[0.75rem] font-label-md text-label-md hover:opacity-90 transition-all no-underline text-center flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Part選択へ戻る
            </a>
        </div>

    </div>

</div>
@endsection
