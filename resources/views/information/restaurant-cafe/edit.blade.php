<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Restaurant & Cafe 編集 — Kredo Plus</title>

<!-- Tailwind CDN: index.blade と同じ構成 -->
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
            50: '#F1F0FF',
            100: '#E3E0FF',
            300: '#A7A0FF',
            500: '#5A4CFF',
            600: '#4736F0',
            700: '#372AC2',
          },
          clay: {
            400: '#E08A5B',
            500: '#CE7043',
            600: '#B15A32',
          },
        },
        fontFamily: {
          display: ['"Poppins"', '"Noto Sans JP"', 'sans-serif'],
          body: ['"Noto Sans JP"', 'sans-serif'],
          mono: ['"IBM Plex Mono"', 'monospace'],
        },
        boxShadow: {
          card: '0 1px 2px rgba(36,30,26,0.06), 0 8px 24px -12px rgba(36,30,26,0.18)',
          cardHover: '0 4px 8px rgba(36,30,26,0.08), 0 20px 40px -16px rgba(36,30,26,0.28)',
          pop: '0 24px 60px -12px rgba(36,30,26,0.35)',
        }
      }
    }
  }
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;800&family=Poppins:wght@600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">

<!-- fontawesome cdn -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
  body { font-family: 'Noto Sans JP', sans-serif; background: #FFFFFF; }
  .font-display { font-family: 'Poppins', 'Noto Sans JP', sans-serif; }
  .font-mono { font-family: 'IBM Plex Mono', monospace; }

  ::-webkit-scrollbar { width: 10px; height: 10px; }
  ::-webkit-scrollbar-thumb { background: #E3E0FF; border-radius: 8px; }

  @keyframes riseIn {
    from { opacity: 0; transform: translateY(14px) scale(.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
  }
  .rise-in { animation: riseIn .45s cubic-bezier(.2,.8,.2,1) both; }

  .field-input {
    width: 100%;
    background: #FFFFFF;
    border: 1px solid rgba(36,30,26,0.1);
    border-radius: 0.75rem;
    padding: 0.7rem 1rem;
    font-size: 0.9rem;
    transition: box-shadow .2s ease, border-color .2s ease;
  }
  .field-input:focus {
    outline: none;
    border-color: #5A4CFF;
    box-shadow: 0 0 0 3px rgba(90,76,255,0.15);
  }
  .field-input::placeholder { color: rgba(36,30,26,0.35); }

  .image-drop { transition: border-color .2s ease, background .2s ease; }
  .image-drop:hover { border-color: #5A4CFF; background: #F1F0FF; }

  .toast { transition: opacity .3s ease, transform .3s ease; }

  /* カテゴリーごとの色は --chip-color で指定、index.bladeのcat-linkと同じ考え方 */
  .cat-chip {
    --chip-color: #4736F0;
    background: rgba(36,30,26,0.05);
    color: rgba(36,30,26,0.6);
    border: 1px solid transparent;
  }
  .cat-chip:hover {
    background: color-mix(in srgb, var(--chip-color) 15%, white);
    color: var(--chip-color);
  }
  .cat-chip.active {
    background: var(--chip-color);
    color: #ffffff;
    box-shadow: 0 4px 10px -4px rgba(0,0,0,0.35);
  }
</style>
</head>

<body class="text-ink">

<div class="min-h-screen flex flex-col">

  {{-- ヘッダーは共通ファイルからインクルード --}}
  {{-- @include('layouts.header') --}}

  <!-- Top bar -->
  <header class="sticky top-0 z-30 bg-paper/90 backdrop-blur border-b border-ink/10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="{{ route('restaurant-cafe.index') }}" class="p-2 -ml-2 rounded-lg hover:bg-ink/5 transition-colors" aria-label="戻る">
          <i class="fa-solid fa-arrow-left text-[18px]"></i>
        </a>
        <span class="font-display font-bold text-xl tracking-tight text-brand-600">Restaurant & Cafe 編集</span>
      </div>

      <!-- 削除ボタン(投稿全体の削除フォーム) -->
      <form id="deleteForm" action="{{ route('restaurant-cafe.destroy', $post) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="button" id="deleteBtn" class="p-2 rounded-full hover:bg-ink/5" aria-label="削除">
          <i class="fa-regular fa-trash-can text-clay-500 text-[18px]"></i>
        </button>
      </form>
    </div>
  </header>

  <!-- pb-28: 下の固定フッターナビ(h-20)に保存ボタンが隠れないよう余白を確保 -->
  <main class="flex-1 max-w-3xl w-full mx-auto px-4 sm:px-6 py-8 pb-28">

    @if ($errors->any())
      <div class="rise-in mb-6 bg-clay-400/10 border border-clay-400/30 text-clay-600 text-sm rounded-xl px-4 py-3">
        <p class="font-semibold mb-1">入力内容をご確認ください</p>
        <ul class="list-disc list-inside space-y-0.5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form id="editForm" class="rise-in space-y-6" action="{{ route('restaurant-cafe.update', $post) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <!-- Image -->
      <div>
        <label class="block text-xs font-mono tracking-[0.1em] text-ink/40 mb-2">写真</label>
        <label for="imageInput" class="image-drop relative block h-48 sm:h-56 rounded-2xl border-2 border-dashed border-ink/15 overflow-hidden cursor-pointer group">
          <img id="imagePreview"
               src="{{ $post->image ? asset('storage/'.$post->image) : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=800&auto=format&fit=crop' }}"
               class="w-full h-full object-cover" alt="写真プレビュー">
          <div class="absolute inset-0 bg-ink/0 group-hover:bg-ink/40 transition-colors flex items-center justify-center">
            <span class="opacity-0 group-hover:opacity-100 transition-opacity bg-paper/95 px-4 py-2 rounded-full flex items-center gap-2 shadow-pop">
              <i class="fa-solid fa-camera text-brand-600"></i>
              <span class="text-sm font-semibold">写真を変更</span>
            </span>
          </div>
        </label>
        <input type="file" name="image" id="imageInput" accept="image/*" class="hidden">
      </div>

      <!-- Category -->
      <div>
        <label class="block text-xs font-mono tracking-[0.1em] text-ink/40 mb-2">CATEGORY</label>
        <div id="categoryChips" class="flex flex-wrap gap-2">
          @foreach ($categories as $category)
            <button type="button"
                    data-value="{{ $category->id }}"
                    style="--chip-color:{{ $category->color() }}"
                    class="cat-chip {{ $post->category_id === $category->id ? 'active' : '' }} px-4 py-2 rounded-full text-sm font-semibold transition-all">
              {{ $category->name }}
            </button>
          @endforeach
        </div>
        <input type="hidden" name="category_id" id="category_id" value="{{ old('category_id', $post->category_id) }}">
      </div>

      <!-- Title -->
      <div>
        <label class="block text-xs font-mono tracking-[0.1em] text-ink/40 mb-2">TITLE</label>
        <input type="text" name="title" id="title" class="field-input"
               value="{{ old('title', $post->title) }}" placeholder="例: Sunset Grill House">
      </div>

      <!-- Description -->
      <div>
        <label class="block text-xs font-mono tracking-[0.1em] text-ink/40 mb-2">DESCRIPTION</label>
        <textarea name="description" id="description" rows="4" class="field-input resize-none"
                  placeholder="お店・サービスの特徴を書いてみましょう">{{ old('description', $post->description) }}</textarea>
      </div>

      <!-- Price -->
      <div>
        <label class="block text-xs font-mono tracking-[0.1em] text-ink/40 mb-2">PRICE (PHP)</label>
        <input type="number" name="price" id="price" min="0" step="1" class="field-input"
               value="{{ old('price', $post->price) }}" placeholder="例: 450">
      </div>

      <!-- Actions -->
      <div class="flex flex-col gap-3 pt-4">
        <button type="submit" id="saveBtn" class="w-full h-13 py-3.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-semibold shadow-card active:scale-[0.98] transition-all flex items-center justify-center gap-2">
          <i class="fa-solid fa-check"></i>
          <span>SAVE</span>
        </button>
      </div>

    </form>
  </main>
</div>

<!-- Toast -->
<div id="toast" class="toast fixed bottom-6 left-1/2 -translate-x-1/2 opacity-0 translate-y-3 pointer-events-none bg-ink text-white px-5 py-3 rounded-full shadow-pop flex items-center gap-2 z-50">
  <i class="fa-solid fa-circle-check text-brand-300"></i>
  <span id="toastText" class="text-sm font-medium">SAVE</span>
</div>

<script>
  // カテゴリーチップの選択切り替え
  document.querySelectorAll('.cat-chip').forEach(chip => {
    chip.addEventListener('click', () => {
      document.querySelectorAll('.cat-chip').forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      document.getElementById('category_id').value = chip.dataset.value;
    });
  });

  // 画像プレビュー切り替え
  document.getElementById('imageInput').addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
      document.getElementById('imagePreview').src = URL.createObjectURL(file);
    }
  });

  function showToast(text, icon = 'fa-circle-check', iconColor = 'text-brand-300') {
    const toast = document.getElementById('toast');
    const toastText = document.getElementById('toastText');
    const toastIcon = toast.querySelector('i');
    toastText.textContent = text;
    toastIcon.className = `fa-solid ${icon} ${iconColor}`;
    toast.classList.remove('opacity-0', 'translate-y-3', 'pointer-events-none');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => {
      toast.classList.add('opacity-0', 'translate-y-3', 'pointer-events-none');
    }, 2200);
  }

  // 保存: 送信自体はフォームのaction(PUT /information/restaurant-cafe/{post})に任せる
  document.getElementById('editForm').addEventListener('submit', () => {
    const btn = document.getElementById('saveBtn');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span>Saving...</span>';
    btn.disabled = true;
  });

  // 削除: confirmでOKされた時だけ deleteForm を送信する
  document.getElementById('deleteBtn').addEventListener('click', () => {
    if (confirm('この投稿を削除しますか？この操作は取り消せません。')) {
      document.getElementById('deleteForm').submit();
    }
  });

  @if (session('status'))
    showToast(@json(session('status')));
  @endif
</script>

<!-- footer nav: index.blade と同じKredo配色・フォントに統一 -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
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
