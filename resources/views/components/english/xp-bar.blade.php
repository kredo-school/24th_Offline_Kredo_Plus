@props(['level', 'currentXp', 'nextXp'])

@php
    $percent = $nextXp > 0 ? min(100, max(0, round(($currentXp / $nextXp) * 100))) : 0;
@endphp

<div>
    <div class="flex items-center justify-between text-sm mb-1.5">
        <span class="font-bold text-primary">Level {{ $level }}</span>
        <span class="text-on-surface-variant">{{ $currentXp }} / {{ $nextXp }} XP</span>
    </div>
    <div class="w-full h-2 bg-primary-container rounded-full overflow-hidden">
        <div class="h-full bg-primary rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
    </div>
    <p class="text-xs text-on-surface-variant text-right mt-1.5">{{ $percent }}% to Level {{ $level + 1 }}</p>
</div>
