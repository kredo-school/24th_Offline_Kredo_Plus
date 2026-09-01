@extends('layouts.app')

@section('title', 'Typing Practice - ' . $material->title)

@section('content')
<style>
  .canvas-shadow { box-shadow: 0px 10px 30px rgba(108, 91, 83, 0.05); }
  .font-mono-typing { font-family: "JetBrains Mono", monospace; }
  .current-char  { background-color: #fff1ec; border-bottom: 2px solid #a33900; }
  .correct-char  { color: #16a34a; }
  .pending-char  { opacity: 0.4; }
</style>

{{-- Pass config to typing engine before DOMContentLoaded fires --}}
<script>
window.__TYPING_CONFIG__ = {
    rawText:      @json($rawText),
    storeUrl:     '{{ route('english.typing.result.store', $material->id) }}',
    resultUrl:    '{{ route('english.typing.result', $material->id) }}',
    typeQuestion: false,
};
</script>

<div class="flex-1 flex items-center justify-center px-4 py-12">
  <div class="w-full max-w-4xl">

    <div class="mb-8 p-6 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem] shadow-sm">
      <div class="flex items-center justify-between mb-3">
        <p class="text-label-md text-orange-600 font-bold">
          {{ $material->category->name }}
        </p>
        <span id="question-progress" class="text-sm bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 text-orange-600 px-3 py-1 rounded-[0.75rem] font-bold">
          Loading...
        </span>
      </div>
      <p id="current-question-text" class="text-headline-md text-blue-950/90 font-semibold leading-snug">
        Loading question...
      </p>
      <div id="current-meaning-wrapper" class="mt-3 pt-3 border-t border-slate-200/50" style="display: none;">
        <p class="text-caption text-blue-950/90 mb-1">意味</p>
        <p id="current-meaning-text" class="text-body-md text-blue-950/90"></p>
      </div>
    </div>

    <div id="typing-box"
         class="p-8 bg-surface-container-lowest rounded-[0.5rem] canvas-shadow font-mono-typing text-body-lg leading-relaxed whitespace-pre-wrap break-words overflow-x-hidden min-h-[160px]">
    </div>

    <div class="mt-6 flex justify-end">
      <a href="{{ route('english.typing.index') }}"
         class="px-6 py-2.5 bg-[#b95827] text-white font-bold rounded-[0.5rem] shadow-sm hover:bg-[#a04c22] transition-colors text-base">
        練習を中断する
      </a>
    </div>
  </div>
</div>

{{-- リザルトモーダル --}}
<div id="result-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-[9999]">
  <div class="bg-surface w-full max-w-md rounded-[0.5rem] p-6 shadow-xl text-center">
    <h2 class="text-headline-md text-blue-950/90 mb-4 font-bold flex items-center justify-center gap-2">
      <span class="material-symbols-outlined !text-2xl text-orange-600">celebration</span>
      Practice Completed!
    </h2>
    <div class="text-left space-y-4 text-blue-950/90">

      {{-- Stats --}}
      <div class="grid grid-cols-3 gap-3 text-center">
        <div class="bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem] p-3">
          <p class="text-caption text-blue-950/90">WPM</p>
          <p id="result-wpm" class="text-headline-md font-black text-blue-950/90">-</p>
        </div>
        <div class="bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem] p-3">
          <p class="text-caption text-blue-950/90">正答率</p>
          <p id="result-accuracy" class="text-headline-md font-black text-blue-950/90">-</p>
        </div>
        <div class="bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem] p-3">
          <p class="text-caption text-blue-950/90">時間</p>
          <p id="result-time" class="text-headline-md font-black text-blue-950/90">-</p>
        </div>
      </div>

      {{-- XP --}}
      <div class="text-center py-4 bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 rounded-[0.5rem]">
        <p class="text-sm text-orange-600 font-bold">XP GAINED</p>
        <p id="gained-xp-text" class="text-4xl font-black text-orange-600">Calculating...</p>
      </div>

      {{-- Level bar --}}
      <div class="space-y-1">
        <div class="flex justify-between text-xs font-bold text-blue-950/90">
          <span id="level-text">Level 1</span>
          <span>Next Level</span>
        </div>
        <div class="w-full bg-slate-100 rounded-[0.75rem] h-3 overflow-hidden">
          <div id="xp-bar" class="bg-[#b95827] h-full transition-all duration-1000" style="width: 0%"></div>
        </div>
        <p id="xp-progress-text" class="text-right text-xs text-blue-950/90">0 / 500 XP</p>
      </div>
    </div>

    <div class="flex gap-3 mt-6">
      <a href="{{ route('english.typing.index') }}"
         class="flex-1 py-3 text-center bg-surface-container-lowest rounded-[0.5rem] shadow-sm font-bold no-underline text-blue-950/90">
        教材一覧へ
      </a>
      <button id="restart-btn"
              class="flex-1 py-3 bg-slate-50 text-blue-950/90 rounded-[0.5rem] font-bold shadow-sm">
        もう一度
      </button>
      <button id="continue-btn"
              class="flex-1 py-3 bg-[#b95827] text-white rounded-[0.5rem] font-bold">
        結果を見る
      </button>
    </div>
  </div>
</div>

@endsection
