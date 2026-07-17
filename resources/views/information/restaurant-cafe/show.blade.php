<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $post->title }} — Kredo Plus</title>

<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          ink: '#241E1A',
          cream: '#FFFFFF',
          paper: '#FFFFFF',
          brand: {
            50: '#F1F0FF', 100: '#E3E0FF', 300: '#A7A0FF',
            500: '#5A4CFF', 600: '#4736F0', 700: '#372AC2',
          },
          clay: { 400: '#E08A5B', 500: '#CE7043', 600: '#B15A32' },
        },
        fontFamily: {
          display: ['"Poppins"', '"Noto Sans JP"', 'sans-serif'],
          body: ['"Noto Sans JP"', 'sans-serif'],
          mono: ['"IBM Plex Mono"', 'monospace'],
        },
        boxShadow: {
          card: '0 1px 2px rgba(36,30,26,0.06), 0 8px 24px -12px rgba(36,30,26,0.18)',
          pop: '0 24px 60px -12px rgba(36,30,26,0.35)',
        }
      }
    }
  }
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;800&family=Poppins:wght@600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
  body { font-family: 'Noto Sans JP', sans-serif; background: #FFFFFF; }
  .font-display { font-family: 'Poppins', 'Noto Sans JP', sans-serif; }
  .font-mono { font-family: 'IBM Plex Mono', monospace; }

  @keyframes riseIn {
    from { opacity: 0; transform: translateY(14px) scale(.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
  }
  .rise-in { animation: riseIn .45s cubic-bezier(.2,.8,.2,1) both; }
</style>
</head>

<body class="text-ink">

<div class="min-h-screen flex flex-col pb-24">

  {{-- ヘッダーは共通ファイルからインクルード --}}
  {{-- @include('layouts.header') --}}

  <!-- Top bar -->
  <header class="sticky top-0 z-30 bg-paper/90 backdrop-blur border-b border-ink/10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="{{ route('restaurant-cafe.index') }}" class="p-2 -ml-2 rounded-lg hover:bg-ink/5 transition-colors" aria-label="戻る">
          <i class="fa-solid fa-arrow-left text-[18px]"></i>
        </a>
        <span class="font-display font-bold text-xl tracking-tight text-brand-600">投稿詳細</span>
      </div>

      @if ($post->user_id === auth()->id() || (bool) auth()->user()?->is_admin)
        <div class="flex items-center gap-1">
          @if ($post->user_id === auth()->id())
            <a href="{{ route('restaurant-cafe.edit', $post) }}"
               class="w-9 h-9 flex items-center justify-center rounded-full text-brand-600 hover:bg-brand-50 transition-colors" aria-label="編集">
              <i class="fa-solid fa-edit text-[16px]"></i>
            </a>
          @endif
          <form id="deleteForm" action="{{ route('restaurant-cafe.destroy', $post) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" id="deleteBtn"
                    class="w-9 h-9 flex items-center justify-center rounded-full text-clay-600 hover:bg-clay-400/10 transition-colors" aria-label="削除">
              <i class="fa-regular fa-trash-can text-[15px]"></i>
            </button>
          </form>
        </div>
      @endif
    </div>
  </header>

  <main class="flex-1 max-w-3xl w-full mx-auto px-4 sm:px-6 py-8">
    <article class="rise-in bg-paper rounded-3xl overflow-hidden shadow-card">

      <!-- 写真 -->
      <div class="relative h-64 sm:h-80">
        <img src="{{ $post->image ? asset('storage/'.$post->image) : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1200&auto=format&fit=crop' }}"
             class="w-full h-full object-cover" alt="{{ $post->title }}">

        @if ($post->category)
          <span class="absolute top-3 left-3 text-white text-[11px] font-semibold px-2.5 py-1 rounded-full"
                style="background:{{ $post->category->color() }}">
            {{ $post->category->name }}
          </span>
        @endif

        @if (!is_null($post->price))
          <span class="absolute bottom-3 right-3 bg-paper/95 font-mono font-semibold text-sm px-2.5 py-1 rounded-lg">
            {{ number_format($post->price) }} PHP
          </span>
        @endif
      </div>

      <div class="p-5 sm:p-6">
        <!-- 投稿者 -->
        <div class="flex items-center gap-2 mb-4">
          <div class="w-9 h-9 rounded-full bg-brand-100 flex items-center justify-center text-sm font-semibold text-brand-600 shrink-0">
            {{ mb_substr($post->user->name ?? '?', 0, 1) }}
          </div>
          <div class="leading-tight">
            <p class="text-sm font-semibold">{{ $post->user->name ?? '不明なユーザー' }}</p>
            <p class="text-xs text-ink/40">{{ $post->created_at?->diffForHumans() }}</p>
          </div>
        </div>

        <h1 class="font-display font-bold text-2xl mb-3">{{ $post->title }}</h1>

        <p class="text-sm text-ink/60 leading-relaxed whitespace-pre-line mb-6">
          {{ $post->description }}
        </p>

        @if ($post->map_query)
          <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($post->map_query) }}"
             target="_blank"
             class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 hover:text-brand-700 transition-colors">
            <i class="fa-solid fa-globe"></i>
            マップで見る
          </a>
        @endif
      </div>
    </article>
  </main>
</div>

<script>
  document.getElementById('deleteBtn')?.addEventListener('click', () => {
    if (confirm('この投稿を削除しますか？この操作は取り消せません。')) {
      document.getElementById('deleteForm').submit();
    }
  });
</script>

<!-- footer nav -->
<nav class="fixed bottom-0 w-full z-50 bg-white shadow-[0_-4px_20px_-4px_rgba(30,58,138,0.15)] flex justify-around items-center h-20 px-4 pb-2 border-t border-slate-100">
  <a href="{{ route('restaurant-cafe.index') }}" class="flex flex-col items-center justify-center gap-1 text-[#2f5fdb] px-4 py-1 active:scale-90 transition-all duration-200">
    <i class="fa-solid fa-house text-[20px]"></i>
    <span class="text-[10px] font-bold tracking-wide" style="font-family:'Poppins','Noto Sans JP',sans-serif;">Home</span>
  </a>
  <a href="#" class="flex flex-col items-center justify-center gap-1 text-slate-400 hover:text-[#2f5fdb] px-4 py-1 active:scale-90 transition-all duration-200">
    <div class="w-14 h-14 -mt-8 rounded-full flex items-center justify-center shadow-[0_12px_32px_-12px_rgba(30,58,138,0.35)] border-4 border-white"
         style="background: linear-gradient(135deg, #2f5fdb 0%, #e05237 33%, #f5b52e 66%, #5eab35 100%);">
      <i class="fa-solid fa-plus text-white text-[20px]"></i>
    </div>
    <span class="text-[10px] font-bold tracking-wide mt-1" style="font-family:'Poppins','Noto Sans JP',sans-serif;">Post</span>
  </a>
  <a href="#" class="flex flex-col items-center justify-center gap-1 text-slate-400 hover:text-[#2f5fdb] px-4 py-1 active:scale-90 transition-all duration-200">
    <i class="fa-solid fa-user text-[20px]"></i>
    <span class="text-[10px] font-bold tracking-wide" style="font-family:'Poppins','Noto Sans JP',sans-serif;">Profile</span>
  </a>
</nav>
</body>
</html>
