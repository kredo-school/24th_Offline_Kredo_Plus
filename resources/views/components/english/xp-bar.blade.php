@props(['level' => 1, 'currentXp' => 0, 'nextXp' => 100])

@php
    $percent = $nextXp > 0 ? min(round(($currentXp / $nextXp) * 100), 100) : 0;

    // 英語学習モジュール全体で統一したオレンジ基調のアクセント
    // トラック（未達成部分）もorange系の淡色にまとめ、Material3のピンク下地との色喧嘩を防止
    $accentClasses = ['text' => 'text-orange-600', 'bar' => 'bg-[#b95827]', 'track' => 'bg-orange-100/70'];
@endphp

<div class="w-full">
    <div class="flex items-center justify-between mb-1">
        <span class="text-label-md font-bold {{ $accentClasses['text'] }}">Level {{ $level }}</span>
        <span class="text-caption text-blue-950/90">{{ number_format($currentXp) }} / {{ number_format($nextXp) }} XP</span>
    </div>
    <div class="w-full {{ $accentClasses['track'] }} rounded-[0.75rem] h-3 overflow-hidden">
        <div class="{{ $accentClasses['bar'] }} h-full rounded-[0.75rem] transition-all duration-700"
             style="width: {{ $percent }}%"></div>
    </div>
    <p class="text-caption text-blue-950/90 mt-1 text-right">{{ $percent }}% to Level {{ $level + 1 }}</p>
</div>
