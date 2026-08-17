@extends('layouts.app')

@section('title', 'Carinderia — Kredo Plus')

@section('content')

<style>
  body { font-family: 'Noto Sans JP', sans-serif; background: #FFFFFF; }
  .font-display { font-family: 'Poppins', 'Noto Sans JP', sans-serif; }
  .font-mono { font-family: 'IBM Plex Mono', monospace; }

  /* signature: 手書き風の下線が STORE カテゴリの選択状態にじわっと伸びる */
  .cat-link { position: relative; }
  .cat-link::before {
    content: '';
    position: absolute;
    left: -20px;
    top: 50%;
    width: 3px;
    height: 0%;
    background: #2f5fdb;
    border-radius: 2px;
    transform: translateY(-50%);
    transition: height .25s ease;
  }
  .cat-link.active::before,
  .cat-link:hover::before { height: 70%; }

  .cat-link-mobile { --cat-color: #2f5fdb; background: rgba(36,30,26,0.05); color: rgba(36,30,26,0.6); }
  .cat-link-mobile.active { background: var(--cat-color); color: #fff; }

  .food-card { transition: transform .35s cubic-bezier(.2,.8,.2,1), box-shadow .35s ease; }
  .food-card:hover { transform: translateY(-4px); }

  .heart-btn i { transition: transform .2s ease; }
  .heart-btn.liked i { transform: scale(1.15); }

  ::-webkit-scrollbar { width: 10px; height: 10px; }
  ::-webkit-scrollbar-thumb { background: #E3E0FF; border-radius: 8px; }

  @keyframes riseIn {
    from { opacity: 0; transform: translateY(14px) scale(.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
  }
  .rise-in { animation: riseIn .45s cubic-bezier(.2,.8,.2,1) both; }

  #detailModal { transition: opacity .2s ease; }
  #detailModal .modal-panel { transition: transform .28s cubic-bezier(.2,.8,.2,1), opacity .28s ease; }

  .avatar-initial { display:flex; align-items:center; justify-content:center; background:#E3E0FF; color:#4736F0; font-weight:600; }
</style>

<div class="text-[#241E1A] pb-24">


<div class="min-h-screen flex flex-col">

  <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 pt-8 md:pt-12">
    <!-- ヒーローバナー: $sectionの内容(アドミンが登録した画像・タイトル・説明文)をそのまま表示。
         STOREでサブカテゴリーを選ぶと、JSでそのサブカテゴリー専用のヒーローに切り替わる(All選択時は元に戻る) -->
    @php
      $heroImage = $section->hero_image ?? 'images/carinderia/sisig.jpg';
      $heroImage = \Illuminate\Support\Str::startsWith($heroImage, ['http://', 'https://']) ? $heroImage : asset($heroImage);
      // @jsonディレクティブは式中のカンマで引数を区切ってしまうため、カンマを含む文字列は
      // 先にPHP変数に入れてから@jsonに渡す(直接?? '...'をカンマ入りで書くと壊れる)
      $defaultHeroDescription = $section->description ?? 'シンプルで美味しい、家庭的な味。近所のカリンデリアの美味しさをお届けします。';
      // サブカテゴリーごとのヒーロー画像・説明文をJSに渡す用(未設定のサブカテゴリーはnullのままでOK。JS側でデフォルトにフォールバックする)
      $subCategoryHero = $subCategories->mapWithKeys(function ($c) {
        $img = $c->hero_image;
        if ($img) {
          $img = \Illuminate\Support\Str::startsWith($img, ['http://', 'https://']) ? $img : asset($img);
        }
        return [$c->name => ['image' => $img, 'description' => $c->description]];
      });
    @endphp
    <section id="heroImg" class="relative overflow-hidden rounded-3xl mb-0 min-h-[280px] sm:min-h-[340px] md:min-h-[380px] p-8 md:p-10 bg-cover bg-center shadow-[0_1px_2px_rgba(36,30,26,0.06),0_8px_24px_-12px_rgba(36,30,26,0.18)]"
      style="background-image: url('{{ $heroImage }}');">
      <div class="absolute inset-0 bg-gradient-to-r from-white/80 from-5% via-white/25 via-40% to-transparent to-65% pointer-events-none"></div>
      <div class="relative flex flex-col lg:flex-row gap-8 w-full">
        <div class="flex-1">
          <h1 class="text-display font-black text-blue-950/90 mb-1">留学情報</h1>
          <p id="heroTitle" class="text-headline-md font-bold text-brand-green mb-3">{{ $section->name ?? 'Carinderia' }}</p>
          <p id="heroDesc" class="text-body-md text-blue-950/90 max-w-md">{{ $defaultHeroDescription }}</p>
        </div>
      </div>
    </section>
  </div>

  <br>

  <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 pt-4">
  {{--
      メインカテゴリー一覧ボタン。main_categoriesテーブルを動的にループするので、
      アドミンが5つ目以降を追加しても、ここに自動で表示される。
      1行のみの横スライド形式。携帯は1画面に2つ、PCは1画面に4つ表示、それ以降は横スライドで見る。
  --}}
  @php
    $fixedRoutes = [
      'carinderia'      => 'carinderia.index',
      'restaurant-cafe' => 'restaurant-cafe.index',
      'travel'          => 'travel.index',
      'other'           => 'other.index',
    ];
  @endphp
  <div class="grid grid-flow-col grid-rows-1 auto-cols-[calc(50%-0.375rem)] sm:auto-cols-[calc(25%-0.5625rem)] gap-3 overflow-x-auto snap-x snap-mandatory pb-1" style="scrollbar-width:none;">
    @foreach ($allMainCategories as $mc)
      @php
        $href = isset($fixedRoutes[$mc->key])
          ? route($fixedRoutes[$mc->key])
          : route('information.dynamic', $mc->key);
      @endphp
      <a href="{{ $href }}" class="snap-start flex items-center justify-center text-white rounded-xl py-3 px-3 font-semibold text-sm shadow-sm hover:opacity-90 transition-opacity" style="background:{{ $mc->color() }}">
        {{ $mc->name }}
      </a>
    @endforeach
  </div>
</div>

  <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 pt-3 md:pt-6">
    <!-- 検索(スマホ用。PCは下のサイドバー内に別途表示) -->
    <label class="relative block mb-4 md:hidden">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-[#241E1A]/40" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="searchInputMobile" placeholder="タイトルを検索" class="w-full bg-[#FFFFFF] border border-[#241E1A]/10 rounded-xl pl-9 pr-3 py-2.5 text-sm placeholder:text-[#241E1A]/40 focus:outline-none focus:ring-2 focus:ring-[#A7A0FF]">
    </label>

    <!-- STORE選択(スマホ用: 横スクロールできるチップ) -->
    <div class="flex md:hidden gap-2 overflow-x-auto pb-1 mb-2" id="storeNavMobile" style="scrollbar-width:none;">
      <a href="#" style="--cat-color:rgba(36,30,26,0.6)" class="cat-link-mobile active shrink-0 whitespace-nowrap rounded-full px-4 py-1.5 text-xs font-semibold transition-colors">All</a>
      {{-- サブカテゴリー一覧はDBから動的に取得。アドミンが追加しても、この@foreachがそのまま対応する --}}
      @foreach ($subCategories as $cat)
        <a href="#" data-tag="{{ $cat->name }}" style="--cat-color:{{ $cat->color() }}" class="cat-link-mobile shrink-0 whitespace-nowrap rounded-full px-4 py-1.5 text-xs font-semibold transition-colors">{{ $cat->name }}</a>
      @endforeach
    </div>
  </div>

  <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 py-4 flex gap-6">
<!-- Sidebar(PC/タブレットのみ。スマホは上の検索欄+チップに置き換え) -->
    <aside id="sidebar" class="hidden md:block w-60 shrink-0">
      <div class="sticky top-24">
        <label class="relative block mb-6">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-[#241E1A]/40" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" id="searchInput" placeholder="タイトルを検索" class="w-full bg-[#FFFFFF] border border-[#241E1A]/10 rounded-xl pl-9 pr-3 py-2.5 text-sm placeholder:text-[#241E1A]/40 focus:outline-none focus:ring-2 focus:ring-[#A7A0FF]">
        </label>
        <p class="font-mono text-[11px] tracking-[0.18em] text-[#241E1A]/40 mb-3 pl-1">STORE</p>
        <nav class="flex flex-col gap-1 pl-5" id="storeNav">
          <a href="#" style="--cat-color:rgba(36,30,26,0.6)" class="cat-link active flex items-center justify-between py-2 text-sm text-[#241E1A]/70 hover:text-[#241E1A]">All</a>
          {{-- サブカテゴリー一覧はDBから動的に取得。アドミンが追加しても、この@foreachがそのまま対応する --}}
          @foreach ($subCategories as $cat)
            <a href="#" data-tag="{{ $cat->name }}" style="--cat-color:{{ $cat->color() }}" class="cat-link flex items-center justify-between py-2 text-sm text-[#241E1A]/70 hover:text-[#241E1A]">{{ $cat->name }}</a>
          @endforeach
        </nav>
      </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 min-w-0">

      <!-- Toolbar: 件数表示 -->
      <div class="flex items-center justify-between mb-4">
        <p id="resultCount" class="text-sm text-[#241E1A]/50"></p>
      </div>

      <!-- Grid -->
      <div id="foodGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5"></div>

      <!-- Pagination -->
      <div class="flex items-center justify-center gap-2 mt-8" id="pagination"></div>
    </main>
  </div>
</div>

<!-- Detail modal -->
<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-[#241E1A]/40 backdrop-blur-sm">
  <div class="modal-panel bg-[#FFFFFF] rounded-2xl w-full max-w-3xl overflow-hidden shadow-[0_24px_60px_-12px_rgba(36,30,26,0.35)] opacity-0 translate-y-3 flex flex-col md:flex-row md:h-[520px]">

    <!-- 左: 写真(固定) -->
    <div class="relative w-full md:w-1/2 h-64 md:h-full shrink-0">
      <img id="modalImg" src="" class="w-full h-full object-cover" alt="">
      <span id="modalTag" class="absolute top-3 left-3 text-white text-[11px] font-semibold px-2.5 py-1 rounded-full" style="background:#2f5fdb"></span>
      <span id="modalPrice" class="absolute bottom-3 right-3 bg-[#FFFFFF]/95 font-mono font-semibold text-sm px-2.5 py-1 rounded-lg"></span>
      <button onclick="closeModal()" class="md:hidden absolute top-3 right-3 bg-[#241E1A]/50 hover:bg-[#241E1A]/70 text-white w-7 h-7 rounded-full flex items-center justify-center" aria-label="閉じる">✕</button>
    </div>

    <!-- 右: ヘッダー + 説明 + アクション -->
    <div class="w-full md:w-1/2 flex flex-col min-h-0">

      <!-- ヘッダー: 投稿者 + 編集/削除ボタン -->
      <div class="flex items-center justify-between gap-2 px-4 py-3 border-b border-[#241E1A]/10 shrink-0">
        <div class="flex items-center gap-2 min-w-0">
          <div id="modalAvatar" class="avatar-initial w-8 h-8 rounded-full shrink-0 text-xs"></div>
          <div class="leading-tight min-w-0">
            <p id="modalName" class="text-sm font-semibold truncate"></p>
            <p id="modalTime" class="text-xs text-[#241E1A]/40"></p>
          </div>
        </div>

        <div class="flex items-center gap-1 shrink-0">
          <!-- 投稿主だけに表示される操作ボタン(アイコンのみ) -->
          <div id="ownerActions" class="hidden items-center gap-1">
            <button id="modalEditBtn" class="w-8 h-8 flex items-center justify-center rounded-full text-[#4736F0] hover:bg-[#F1F0FF] transition-colors active:scale-90" aria-label="編集">
              <i class="fa-solid fa-edit text-[15px]"></i>
            </button>
            <button id="modalDeleteBtn" class="w-8 h-8 flex items-center justify-center rounded-full text-red-600 hover:bg-red-50 transition-colors active:scale-90" aria-label="削除">
              <i class="fa-solid fa-trash text-[14px]"></i>
            </button>
          </div>
          <button onclick="closeModal()" class="hidden md:flex w-8 h-8 items-center justify-center rounded-full text-[#241E1A]/40 hover:bg-[#241E1A]/5 hover:text-[#241E1A] transition-colors" aria-label="閉じる">
            <i class="fa-solid fa-xmark text-[16px]"></i>
          </button>
        </div>
      </div>

      <!-- スクロール領域: タイトル・説明・コメント一覧 -->
      <div class="flex-1 overflow-y-auto px-4 py-3 min-h-0">
        <h3 id="modalTitle" class="font-display font-semibold text-lg mb-1"></h3>
        <p id="modalDesc" class="text-sm text-[#241E1A]/60 mb-4"></p>

        <p class="text-xs font-mono tracking-[0.1em] text-[#241E1A]/40 mb-2">COMMENTS</p>
        <div id="commentList" class="space-y-3"></div>
      </div>

      <!-- アクションバー: ♡ 💬(左) / 🌐マップ(右) -->
      <div class="px-4 pt-2 pb-1 border-t border-[#241E1A]/10 shrink-0">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <button id="modalLikeBtn" class="heart-btn text-[#241E1A] hover:text-[#CE7043] transition-colors active:scale-90" aria-label="いいね">
              <i class="fa-regular fa-heart text-[22px]"></i>
            </button>
            <button onclick="document.getElementById('commentInput').focus()" class="text-[#241E1A] hover:text-[#4736F0] transition-colors active:scale-90" aria-label="コメント">
              <i class="fa-regular fa-comment text-[22px]"></i>
            </button>
            <button id="modalSaveBtn" class="save-btn text-[#241E1A] hover:text-[#4736F0] transition-colors active:scale-90" aria-label="保存">
              <i class="fa-regular fa-bookmark text-[22px]"></i>
            </button>
          </div>
          <a id="modalMapLink" href="#" target="_blank" class="text-[#241E1A] hover:text-[#4736F0] transition-colors active:scale-90" aria-label="マップで見る">
            <i class="fa-solid fa-globe text-[22px]"></i>
          </a>
        </div>
        <p id="modalLikeCount" class="text-xs font-semibold mt-1.5 mb-2"></p>
      </div>

      <!-- コメント投稿フォーム -->
      <form id="commentForm" class="flex items-center gap-2 px-4 py-3 border-t border-[#241E1A]/10 shrink-0">
        <input type="text" id="commentInput" class="flex-1 border border-[#241E1A]/10 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#A7A0FF]" placeholder="コメントを書く...">
        <button type="submit" class="w-10 h-10 shrink-0 flex items-center justify-center rounded-full bg-[#4736F0] text-white hover:bg-[#372AC2] transition-colors active:scale-90">
          <i class="fa-solid fa-paper-plane text-sm"></i>
        </button>
      </form>
    </div>
  </div>
</div>

<!-- 削除用の隠しフォーム(投稿ごとにactionだけJSで差し替える) -->
<form id="deleteForm" method="POST" class="hidden">
  @csrf
  @method('DELETE')
</form>

<script>
  // ---- カテゴリごとの色(Category::color()と同じ値をサーバーから受け取る) ----
  const categoryColors = @json($categoryColors);
  function colorOf(tag){ return categoryColors[tag] || '#2f5fdb'; }

  // ---- サブカテゴリーごとのヒーロー画像・説明文(adminが設定していれば使う。無ければデフォルトに戻す) ----
  const subCategoryHero = @json($subCategoryHero);
  const defaultHero = {
    image: @json($heroImage),
    title: @json($section->name ?? 'Carinderia'),
    description: @json($defaultHeroDescription),
  };
  function updateHero(tag){
    const heroImg = document.getElementById('heroImg');
    const heroTitle = document.getElementById('heroTitle');
    const heroDesc = document.getElementById('heroDesc');
    if (!heroImg || !heroTitle || !heroDesc) return;
    const custom = tag ? subCategoryHero[tag] : null;
    const nextImage = (custom && custom.image) ? custom.image : defaultHero.image;
    heroImg.style.backgroundImage = `url('${nextImage}')`;
    heroTitle.textContent = tag || defaultHero.title;
    heroDesc.textContent = (custom && custom.description) ? custom.description : defaultHero.description;
  }

  // ---- 実データ(Post::whereHas('category', section=carinderia)->latest()->get()) ----
  const items = @json($posts);

  // ---- 現在ログイン中のユーザー(Auth::id()。未ログインならnull) ----
  const currentUserId = {{ auth()->id() ?? 'null' }};
  const routeBase = @json(url('information'));
  const postsRouteBase = @json(url('information/posts'));
  const loginUrl = @json(route('login'));
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  const PAGE_SIZE = 21;
  let currentPage = 1;
  let searchQuery = '';
  let activeCategory = null; // nullは「All(すべて表示)」を意味する
  let currentModalItem = null;

  function money(v){ return v === null || v === undefined ? '' : Math.trunc(v) + ' PHP'; }

  function initials(name){
    return (name || '?').trim().charAt(0).toUpperCase();
  }

  function timeAgo(dateStr){
    if (!dateStr) return '';
    const diffSec = Math.max(0, Math.floor((Date.now() - new Date(dateStr)) / 1000));
    if (diffSec < 60) return 'たった今';
    const diffMin = Math.floor(diffSec / 60);
    if (diffMin < 60) return diffMin + '分前';
    const diffHour = Math.floor(diffMin / 60);
    if (diffHour < 24) return diffHour + '時間前';
    const diffDay = Math.floor(diffHour / 24);
    return diffDay + '日前';
  }

  // ---- 検索語をHTML内で安全に太字ハイライトする ----
  function escapeRegExp(str){
    return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }
  function highlightMatch(text, query){
    if (!query) return text;
    const re = new RegExp('(' + escapeRegExp(query) + ')', 'ig');
    return text.replace(re, '<strong class="bg-[#F1F0FF] text-[#4736F0] rounded px-0.5">$1</strong>');
  }

  // ---- いいねのトグル(サーバーに保存。未ログインならログイン画面へ) ----
  function toggleLike(it){
    if (currentUserId === null) { window.location.href = loginUrl; return; }

    fetch(`${postsRouteBase}/${it.id}/like`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
    })
      .then(r => r.json())
      .then(data => {
        it.liked_by_me = data.liked;
        it.likes_count = data.likes_count;
        updateLikeUI(it);
      })
      .catch(() => alert('通信エラーが発生しました。もう一度お試しください。'));
  }

  // ---- いいねボタンの見た目を、カード側・モーダル側の両方で更新 ----
  function updateLikeUI(it){
    const cardBtn = document.querySelector(`.food-card[data-post-id="${it.id}"] .card-like-btn`);
    if (cardBtn) {
      const icon = cardBtn.querySelector('i');
      icon.classList.toggle('fa-solid', !!it.liked_by_me);
      icon.classList.toggle('fa-regular', !it.liked_by_me);
      cardBtn.querySelector('.card-like-count').textContent = it.likes_count;
    }
    if (currentModalItem && currentModalItem.id === it.id) {
      const modalIcon = document.querySelector('#modalLikeBtn i');
      modalIcon.className = (it.liked_by_me ? 'fa-solid' : 'fa-regular') + ' fa-heart text-[22px]' + (it.liked_by_me ? ' text-[#CE7043]' : '');
      document.getElementById('modalLikeBtn').classList.toggle('liked', !!it.liked_by_me);
      document.getElementById('modalLikeCount').textContent = `${it.likes_count}件のいいね`;
    }
  }

  // ---- お気に入り(保存)のトグル(サーバーに保存。未ログインならログイン画面へ) ----
  function toggleBookmark(it){
    if (currentUserId === null) { window.location.href = loginUrl; return; }

    fetch(`${postsRouteBase}/${it.id}/bookmark`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
    })
      .then(r => r.json())
      .then(data => {
        it.bookmarked_by_me = data.bookmarked;
        updateBookmarkUI(it);
      })
      .catch(() => alert('通信エラーが発生しました。もう一度お試しください。'));
  }

  // ---- 保存ボタンの見た目を、カード側・モーダル側の両方で更新 ----
  function updateBookmarkUI(it){
    const cardBtn = document.querySelector(`.food-card[data-post-id="${it.id}"] .card-save-btn`);
    if (cardBtn) {
      const icon = cardBtn.querySelector('i');
      icon.classList.toggle('fa-solid', !!it.bookmarked_by_me);
      icon.classList.toggle('fa-regular', !it.bookmarked_by_me);
    }
    if (currentModalItem && currentModalItem.id === it.id) {
      const modalIcon = document.querySelector('#modalSaveBtn i');
      modalIcon.classList.toggle('fa-solid', !!it.bookmarked_by_me);
      modalIcon.classList.toggle('fa-regular', !it.bookmarked_by_me);
      document.getElementById('modalSaveBtn').classList.toggle('saved', !!it.bookmarked_by_me);
    }
  }

  // ---- コメント一覧の描画 ----
  function renderComments(it){
    const list = document.getElementById('commentList');
    list.innerHTML = '';
    const comments = it.comments || [];

    if (comments.length === 0) {
      list.innerHTML = `<p class="text-xs text-[#241E1A]/30 italic">まだコメントはありません</p>`;
    } else {
      comments.forEach(c => {
        const name = c.user ? c.user.name : (c.user_name || 'ゲスト');
        const isOwnComment = currentUserId !== null && c.user_id === currentUserId;
        const row = document.createElement('div');
        row.className = 'flex items-start gap-2';
        row.innerHTML = `
          <div class="w-6 h-6 rounded-full bg-[#E3E0FF] flex items-center justify-center text-[10px] font-semibold text-[#4736F0] shrink-0">${initials(name)}</div>
          <div class="text-xs leading-snug flex-1 min-w-0">
            <span class="font-semibold">${name}</span>
            <span class="text-[#241E1A]/70"> ${c.body}</span>
          </div>
          ${isOwnComment ? `<button type="button" class="comment-delete-btn text-[#241E1A]/30 hover:text-red-500 transition-colors shrink-0" aria-label="コメントを削除" data-comment-id="${c.id}"><i class="fa-solid fa-trash-can text-[11px]"></i></button>` : ''}
        `;
        list.appendChild(row);
      });
      list.querySelectorAll('.comment-delete-btn').forEach(btn => {
        btn.addEventListener('click', () => deleteComment(btn.dataset.commentId));
      });
    }
    list.scrollTop = list.scrollHeight;
  }

  // ---- コメント削除(本人のコメントのみ。サーバー側でも権限チェック済み) ----
  function deleteComment(commentId){
    fetch(`${postsRouteBase}/comments/${commentId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
    })
      .then(r => r.json())
      .then(data => {
        if (!currentModalItem) return;
        currentModalItem.comments = (currentModalItem.comments || []).filter(c => String(c.id) !== String(commentId));
        currentModalItem.comments_count = data.comments_count;
        renderComments(currentModalItem);
      })
      .catch(() => alert('コメントの削除に失敗しました。もう一度お試しください。'));
  }

  // ---- STOREの絞り込み + タイトル検索 ----
  function getFilteredItems(){
    let list = activeCategory
      ? items.filter(it => it.category && it.category.name === activeCategory)
      : items; // activeCategoryがnull(=All)の時は絞り込まない
    if (searchQuery) {
      const q = searchQuery.toLowerCase();
      list = list.filter(it => (it.title || '').toLowerCase().includes(q));
    }
    return list; // 新着順 = Controllerの latest() 順のまま
  }

  function renderGrid(page){
    const sorted = getFilteredItems();
    document.getElementById('resultCount').textContent = `${sorted.length} stores found`;

    const grid = document.getElementById('foodGrid');
    grid.innerHTML = '';
    const start = (page - 1) * PAGE_SIZE;
    const pageItems = sorted.slice(start, start + PAGE_SIZE);

    pageItems.forEach((it, idx) => {
      const titleHtml = searchQuery ? highlightMatch(it.title, searchQuery) : it.title;
      const tag = it.category ? it.category.name : '';
      const userName = it.user ? it.user.name : '不明なユーザー';

      const card = document.createElement('article');
      card.className = 'food-card rise-in bg-[#FFFFFF] rounded-2xl overflow-hidden shadow-[0_1px_2px_rgba(36,30,26,0.06),0_8px_24px_-12px_rgba(36,30,26,0.18)] cursor-pointer';
      card.style.animationDelay = (idx * 0.05) + 's';
      card.dataset.postId = it.id;
      card.innerHTML = `
        <div class="relative h-40">
          <img src="${it.image_url}" class="w-full h-full object-cover" alt="${it.title}" loading="lazy">
          <span class="absolute top-2.5 left-2.5 text-white text-[11px] font-semibold px-2.5 py-1 rounded-full" style="background:#2f5fdb">${tag}</span>
          <span class="absolute bottom-2.5 right-2.5 bg-[#FFFFFF]/95 font-mono font-semibold text-xs px-2.5 py-1 rounded-lg">${money(it.price)}</span>
        </div>
        <div class="p-4">
          <h3 class="font-display font-semibold text-base mb-1 truncate">${titleHtml}</h3>
          <p class="text-sm text-[#241E1A]/55 line-clamp-2 mb-3">${it.description ?? ''}</p>
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-1.5 min-w-0">
              <div class="avatar-initial w-6 h-6 rounded-full shrink-0 text-[10px]">${initials(userName)}</div>
              <div class="leading-tight min-w-0">
                <p class="text-xs font-semibold truncate">${userName}</p>
                <p class="text-[11px] text-[#241E1A]/40">${timeAgo(it.created_at)}</p>
              </div>
              <a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
            it.earth_location
                ? `${it.earth_location.latitude},${it.earth_location.longitude}`
                : it.title
                )}"
                 target="_blank" onclick="event.stopPropagation()" class="shrink-0 p-1.5 rounded-full hover:bg-[#F1F0FF] text-[#241E1A]/60 hover:text-[#4736F0] transition-colors" aria-label="マップで見る">
                <i class="fa-solid fa-globe text-[15px]"></i>
              </a>
            </div>
            <div class="flex items-center gap-0.5 shrink-0">
              <button class="card-like-btn heart-btn p-1.5 rounded-full hover:bg-[#E08A5B]/10 flex items-center gap-1" aria-label="お気に入り">
                <i class="${it.liked_by_me ? 'fa-solid' : 'fa-regular'} fa-heart text-[17px] text-[#CE7043]"></i>
                <span class="card-like-count text-xs text-[#241E1A]/40 font-mono">${it.likes_count ?? 0}</span>
              </button>
              <button class="card-save-btn save-btn p-1.5 rounded-full hover:bg-[#F1F0FF] text-[#241E1A]/60 hover:text-[#4736F0] transition-colors" aria-label="保存">
                <i class="${it.bookmarked_by_me ? 'fa-solid' : 'fa-regular'} fa-bookmark text-[15px]"></i>
              </button>
            </div>
          </div>
        </div>
      `;
      const likeBtn = card.querySelector('.card-like-btn');
      likeBtn.addEventListener('click', (e) => { e.stopPropagation(); toggleLike(it); });
      const saveBtn = card.querySelector('.card-save-btn');
      saveBtn.addEventListener('click', (e) => { e.stopPropagation(); toggleBookmark(it); });
      card.addEventListener('click', () => openModal(it));
      grid.appendChild(card);
    });
  }

  function renderPagination(){
    const totalPages = Math.ceil(getFilteredItems().length / PAGE_SIZE);
    const wrap = document.getElementById('pagination');
    wrap.innerHTML = '';

    for(let p = 1; p <= totalPages; p++){
      const btn = document.createElement('button');
      btn.textContent = p;
      btn.className = 'w-9 h-9 rounded-lg text-sm font-semibold transition ' +
        (p === currentPage ? 'bg-[#4736F0] text-white' : 'bg-[#FFFFFF] border border-[#241E1A]/10 text-[#241E1A]/60 hover:border-[#A7A0FF]');
      btn.onclick = () => { currentPage = p; renderGrid(currentPage); renderPagination(); window.scrollTo({top:0, behavior:'smooth'}); };
      wrap.appendChild(btn);
    }

    const next = document.createElement('button');
    next.textContent = 'Next';
    next.className = 'ml-1 px-4 h-9 rounded-lg text-sm font-semibold bg-[#FFFFFF] border border-[#241E1A]/10 text-[#241E1A]/60 hover:border-[#A7A0FF] disabled:opacity-40';
    next.disabled = currentPage === totalPages;
    next.onclick = () => { if(currentPage < totalPages){ currentPage++; renderGrid(currentPage); renderPagination(); window.scrollTo({top:0, behavior:'smooth'}); } };
    wrap.appendChild(next);
  }

  function openModal(it){
    currentModalItem = it;
    const tag = it.category ? it.category.name : '';
    const userName = it.user ? it.user.name : '不明なユーザー';

    document.getElementById('modalImg').src = it.image_url;
    document.getElementById('modalTag').textContent = tag;
    document.getElementById('modalPrice').textContent = money(it.price);
    document.getElementById('modalTitle').textContent = it.title;
    document.getElementById('modalDesc').textContent = it.description ?? '';
    document.getElementById('modalAvatar').textContent = initials(userName);
    document.getElementById('modalName').textContent = userName;
    document.getElementById('modalTime').textContent = timeAgo(it.created_at);

    // いいねボタンの初期状態
    const modalIcon = document.querySelector('#modalLikeBtn i');
    modalIcon.className = (it.liked_by_me ? 'fa-solid' : 'fa-regular') + ' fa-heart text-[22px]' + (it.liked_by_me ? ' text-[#CE7043]' : '');
    document.getElementById('modalLikeBtn').classList.toggle('liked', !!it.liked_by_me);
    document.getElementById('modalLikeCount').textContent = `${it.likes_count ?? 0}件のいいね`;

    // 保存(お気に入り)ボタンの初期状態
    const modalSaveIcon = document.querySelector('#modalSaveBtn i');
    modalSaveIcon.classList.toggle('fa-solid', !!it.bookmarked_by_me);
    modalSaveIcon.classList.toggle('fa-regular', !it.bookmarked_by_me);
    document.getElementById('modalSaveBtn').classList.toggle('saved', !!it.bookmarked_by_me);

    renderComments(it);

    document.getElementById('modalMapLink').href =
    `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
    it.earth_location
        ? `${it.earth_location.latitude},${it.earth_location.longitude}`
        : it.title
  )}`;
    // 投稿主(user_id)と今ログインしているユーザー(Auth::id())が一致する時だけ、編集・削除ボタンを表示
    const isOwner = currentUserId !== null && it.user_id === currentUserId;
    const ownerActions = document.getElementById('ownerActions');
    ownerActions.classList.toggle('hidden', !isOwner);
    ownerActions.classList.toggle('flex', isOwner);

    document.getElementById('modalEditBtn').onclick = () => {
      window.location.href = `${routeBase}/${it.id}/edit`;
    };
    document.getElementById('modalDeleteBtn').onclick = () => {
      if (confirm(`「${it.title}」を削除しますか？この操作は取り消せません。`)) {
        const form = document.getElementById('deleteForm');
        form.action = `${routeBase}/${it.id}`;
        form.submit();
      }
    };

    const modal = document.getElementById('detailModal');
    const panel = modal.querySelector('.modal-panel');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    requestAnimationFrame(() => {
      panel.classList.remove('opacity-0', 'translate-y-3');
    });
  }

  function closeModal(){
    const modal = document.getElementById('detailModal');
    const panel = modal.querySelector('.modal-panel');
    panel.classList.add('opacity-0', 'translate-y-3');
    setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
  }

  document.getElementById('detailModal').addEventListener('click', (e) => {
    if(e.target.id === 'detailModal') closeModal();
  });

  // モーダルのいいねボタン(開いている投稿=currentModalItemに対して動作)
  document.getElementById('modalLikeBtn').addEventListener('click', () => {
    if (currentModalItem) toggleLike(currentModalItem);
  });

  // モーダルの保存ボタン
  document.getElementById('modalSaveBtn').addEventListener('click', () => {
    if (currentModalItem) toggleBookmark(currentModalItem);
  });

  // コメント投稿フォーム(サーバーに保存し、成功したら一覧に追加)
  document.getElementById('commentForm').addEventListener('submit', (e) => {
    e.preventDefault();
    if (!currentModalItem) return;
    if (currentUserId === null) { window.location.href = loginUrl; return; }

    const input = document.getElementById('commentInput');
    const body = input.value.trim();
    if (!body) return;

    fetch(`${postsRouteBase}/${currentModalItem.id}/comments`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ body }),
    })
      .then(r => r.json())
      .then(data => {
        if (!currentModalItem.comments) currentModalItem.comments = [];
        currentModalItem.comments.push({
          id: data.comment.id,
          body: data.comment.body,
          user_id: data.comment.user_id,
          user: { name: data.comment.user_name },
          created_at: data.comment.created_at,
        });
        currentModalItem.comments_count = data.comments_count;
        input.value = '';
        renderComments(currentModalItem);
      })
      .catch(() => alert('コメントの送信に失敗しました。もう一度お試しください。'));
  });

  // STOREカテゴリ選択(デスクトップの縦サイドバー・スマホの横チップ、両方に対応。クリックで絞り込み+再描画)
  document.querySelectorAll('#storeNav .cat-link, #storeNavMobile .cat-link-mobile').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const tag = link.dataset.tag || null; // "All"リンクはdata-tagが無いのでnull
      document.querySelectorAll('#storeNav .cat-link, #storeNavMobile .cat-link-mobile').forEach(l => {
        l.classList.toggle('active', (l.dataset.tag || null) === tag);
      });
      activeCategory = tag;
      currentPage = 1;
      updateHero(tag);
      renderGrid(currentPage);
      renderPagination();
    });
  });

  // 検索ボックス:タイトルに一致したものだけ表示
  // 検索ボックス:PC用・スマホ用の2つを同期させる(どちらに入力しても両方に反映)
  document.querySelectorAll('#searchInput, #searchInputMobile').forEach(input => {
    input.addEventListener('input', (e) => {
      searchQuery = e.target.value.trim();
      document.querySelectorAll('#searchInput, #searchInputMobile').forEach(other => {
        if (other !== e.target) other.value = e.target.value;
      });
      currentPage = 1;
      renderGrid(currentPage);
      renderPagination();
    });
  });

  renderGrid(currentPage);
  renderPagination();
</script>

<!-- Back to top -->
<button id="backToTop" onclick="window.scrollTo({top:0, behavior:'smooth'})" aria-label="上に戻る"
  class="fixed right-5 bottom-24 z-40 w-11 h-11 rounded-full bg-[#4736F0] text-white shadow-[0_24px_60px_-12px_rgba(36,30,26,0.35)] flex items-center justify-center opacity-0 pointer-events-none translate-y-2 transition-all duration-300 hover:bg-[#372AC2]">
  <i class="fa-solid fa-arrow-up text-[16px]"></i>
</button>
<script>
  const backToTop = document.getElementById('backToTop');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
      backToTop.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-2');
    } else {
      backToTop.classList.add('opacity-0', 'pointer-events-none', 'translate-y-2');
    }
  });
</script>

@include('information.footer.footer_nav')
</div>

<audio autoplay>
    <source src="{{ asset('videos/misic/chicken.MP4') }}" type="video/mp4">
</audio>

@endsection
