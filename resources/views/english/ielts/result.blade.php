@extends('layouts.app')

@section('title', 'IELTS - 結果')

@section('content')
@php
$clearTimeSec = (int) $record->clear_time;
$clearTimeFormatted = sprintf('%d:%02d', intdiv($clearTimeSec, 60), $clearTimeSec % 60);
@endphp

<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-orange-600 transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.ielts.index') }}" class="hover:text-orange-600 transition-colors no-underline">IELTS</a>
        <span class="mx-1">/</span>
        <span class="text-blue-950/90 font-semibold capitalize">{{ $topicMeta['name'] ?? $topic }} × {{ $score }} 結果</span>
    </x-english.breadcrumb>

    <div class="max-w-2xl mx-auto space-y-6">

        {{-- 結果カード --}}
        <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 text-center">
            <div class="mb-3"><span class="material-symbols-outlined !text-5xl text-orange-600">celebration</span></div>
            <h1 class="text-headline-lg font-bold text-blue-950/90 mb-2">
                {{ $topicMeta['name'] ?? ucfirst($topic) }} × IELTS {{ $score }}
            </h1>
            <p class="text-body-md text-blue-950/90 mb-6">タイピング練習完了！</p>

            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="text-center p-3 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem]">
                    <p class="text-headline-lg font-black text-orange-600">{{ $record->wpm }}</p>
                    <p class="text-caption text-blue-950/90">WPM</p>
                </div>
                <div class="text-center p-3 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem]">
                    <p class="text-headline-lg font-black text-orange-600">{{ $record->accuracy }}%</p>
                    <p class="text-caption text-blue-950/90">正確率</p>
                </div>
                <div class="text-center p-3 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem]">
                    <p class="text-headline-lg font-black text-orange-600">{{ $clearTimeFormatted }}</p>
                    <p class="text-caption text-blue-950/90">タイム</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem] p-4">
                <p class="text-label-md font-bold text-orange-600 mb-1">獲得 XP</p>
                <p class="text-headline-lg font-black text-orange-600">+{{ $record->xp_gained }} XP</p>
            </div>
        </div>

        {{-- XPバー --}}
        <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-6">
            <x-english.xp-bar
                :level="$levelInfo['level']"
                :currentXp="$levelInfo['xp_in_level']"
                :nextXp="500" />
        </div>

        {{-- ボタン --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('english.ielts.typing', [$part, $topic, $score]) }}"
               class="flex-1 py-3 bg-surface-container-lowest rounded-[0.75rem] shadow-sm font-label-md text-label-md text-blue-950/90 hover:bg-slate-50 transition-all no-underline text-center flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">refresh</span>
                もう一度
            </a>
            <a href="{{ route('english.ielts.topic', $part) }}"
               class="flex-1 py-3 bg-surface-container-lowest rounded-[0.75rem] shadow-sm font-label-md text-label-md text-blue-950/90 hover:bg-slate-50 transition-all no-underline text-center flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">list</span>
                別のトピックを選ぶ
            </a>
            <a href="{{ route('english.ielts.index') }}"
               class="flex-1 py-3 bg-[#b95827] text-white rounded-[0.75rem] font-label-md text-label-md hover:opacity-90 transition-all no-underline text-center flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                IELTSメニューへ
            </a>
        </div>

    </div>

</div>
@endsection
