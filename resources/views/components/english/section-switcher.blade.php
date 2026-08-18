@props(['current' => null])

@php
$sections = [
    'toeic'      => ['label' => 'TOEIC',        'icon' => 'menu_book',         'route' => 'english.toeic.index'],
    'ielts'      => ['label' => 'IELTS',         'icon' => 'record_voice_over', 'route' => 'english.ielts.index'],
    'vocabulary' => ['label' => '英単語',        'icon' => 'translate',         'route' => 'english.vocabulary.index'],
    'typing'     => ['label' => 'タイピング練習', 'icon' => 'keyboard',          'route' => 'english.typing.index'],
    'quiz'       => ['label' => 'クイズ',        'icon' => 'quiz',              'route' => 'english.quiz.index'],
];
$otherSections = collect($sections)->except($current);
@endphp

<div class="relative shrink-0" x-data="{ open: false }" @click.outside="open = false">
    <button @click="open = !open" type="button"
            class="inline-flex items-center gap-2 bg-surface-container-lowest border border-slate-200 shadow-sm rounded-[0.75rem] px-4 py-2.5 text-label-md font-label-md font-semibold text-blue-950/90 hover:bg-slate-50 transition-all">
        <span class="material-symbols-outlined text-orange-600 text-lg">apps</span>
        別の学習を選ぶ
        <span class="material-symbols-outlined text-sm transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
    </button>

    <div x-show="open" x-transition
         class="absolute right-0 mt-2 w-56 bg-surface-container-lowest rounded-[0.75rem] shadow-card border border-slate-100 overflow-hidden z-40"
         style="display: none;">
        @foreach($otherSections as $key => $s)
        <a href="{{ route($s['route']) }}"
           class="flex items-center gap-3 px-4 py-3 text-body-md no-underline text-blue-950/90 hover:bg-slate-50 transition-colors">
            <span class="material-symbols-outlined text-lg text-orange-600">{{ $s['icon'] }}</span>
            {{ $s['label'] }}
        </a>
        @endforeach
    </div>
</div>
