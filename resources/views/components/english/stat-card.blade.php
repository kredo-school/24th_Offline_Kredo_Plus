@props(['icon' => 'star', 'value' => '0', 'label' => '', 'color' => 'primary'])

<div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-4 flex flex-col items-center gap-2 text-center">
    <div class="p-2 bg-{{ $color }}/10 rounded-[0.75rem]">
        <span class="material-symbols-outlined text-{{ $color }} text-2xl">{{ $icon }}</span>
    </div>
    <span class="font-bold text-headline-md text-on-surface">{{ $value }}</span>
    <span class="text-caption text-on-surface-variant">{{ $label }}</span>
</div>
