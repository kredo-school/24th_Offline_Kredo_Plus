@props(['icon', 'title', 'description', 'href' => '#', 'progress' => null, 'actionLabel' => '学習を開始する'])

<a href="{{ $href }}" class="flex flex-col bg-white rounded-[20px] shadow-sm hover:shadow-md transition-all duration-300 p-5 no-underline text-inherit">
    <div class="flex items-center gap-3 mb-2">
        <div class="w-11 h-11 shrink-0 bg-primary-container rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-primary text-xl">{{ $icon }}</span>
        </div>
        <h3 class="font-bold text-on-surface">{{ $title }}</h3>
    </div>

    <p class="text-sm text-on-surface-variant mb-4">{{ $description }}</p>

    @if (! is_null($progress))
        <div class="flex items-center justify-between text-xs text-on-surface-variant mb-1.5">
            <span>進捗</span>
            <span class="font-bold text-on-surface">{{ $progress }}%</span>
        </div>
        <div class="w-full h-1.5 bg-primary-container rounded-full overflow-hidden mb-4">
            <div class="h-full bg-primary rounded-full" style="width: {{ min(100, max(0, $progress)) }}%"></div>
        </div>
    @endif

    <span class="mt-auto pt-3 border-t border-outline-variant inline-flex items-center gap-1 text-primary text-sm font-bold">
        {{ $actionLabel }}
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
    </span>
</a>
