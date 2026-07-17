@extends('layouts.app')

@section('title', 'Restaurant & Cafe — Kredo Plus')

@section('content')

<style>
  body { font-family: 'Noto Sans JP', sans-serif; background: #FFFFFF; }
  .font-display { font-family: 'Poppins', 'Noto Sans JP', sans-serif; }
  .font-mono { font-family: 'IBM Plex Mono', monospace; }

  /* signature: 手書き風の下線が STORE カテゴリの選択状態にじわっと伸びる */
  /* --cat-color をカテゴリごとに変えることで、下線・選択時の文字色・バッジ色を全部統一する */
  .cat-link { position: relative; --cat-color: #4736F0; }
  .cat-link::before {
    content: '';
    position: absolute;
    left: -20px;
    top: 50%;
    width: 3px;
    height: 0%;
    background: var(--cat-color);
    border-radius: 2px;
    transform: translateY(-50%);
    transition: height .25s ease;
  }

  .cat-link.active::before,
  .cat-link:hover::before { height: 70%; }

  .cat-link.active { color: var(--cat-color); font-weight: 600; }

  .food-card { transition: transform .35s cubic-bezier(.2,.8,.2,1), box-shadow .35s ease; }
  .food-card:hover { transform: translateY(-4px); }

  .heart-btn svg { transition: transform .2s ease, fill .2s ease, stroke .2s ease; }
  .heart-btn.liked svg { fill: #CE7043; stroke: #CE7043; transform: scale(1.15); }

  ::-webkit-scrollbar { width: 10px; height: 10px; }
  ::-webkit-scrollbar-thumb { background: #E3E0FF; border-radius: 8px; }

  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}

  @keyframes riseIn {
    from { opacity: 0; transform: translateY(14px) scale(.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
  }
  .rise-in { animation: riseIn .45s cubic-bezier(.2,.8,.2,1) both; }

  #detailModal { transition: opacity .2s ease; }
  #detailModal .modal-panel { transition: transform .28s cubic-bezier(.2,.8,.2,1), opacity .28s ease; }

  #commentList::-webkit-scrollbar { width: 6px; }
  #commentList::-webkit-scrollbar-thumb { background: #E3E0FF; border-radius: 6px; }
</style>

<div class="text-[#241E1A] pb-24">


<div class="min-h-screen flex flex-col">

  {{-- デモ用: ログイン中のユーザーを切り替えるセレクター(本来はログイン情報から取得。Laravel連携後は削除予定) --}}
  <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 pt-3 flex justify-end">
    <label class="flex items-center gap-2 text-xs text-[#241E1A]/50 bg-[#241E1A]/[0.03] px-3 py-1.5 rounded-full">
      <i class="fa-regular fa-user text-[12px]"></i>
      <span class="font-mono">Login as</span>
      <select id="currentUserSelect" class="bg-transparent font-semibold text-[#241E1A] outline-none cursor-pointer">
        <option value="Guest">Guest(誰でもない人)</option>
        <option value="Mateo L.">Mateo L.</option>
        <option value="Kim C.">Kim C.</option>
        <option value="Ella R.">Ella R.</option>
      </select>
    </label>
  </div>

  <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 pt-6">
    <!-- Hero -->
    <div class="relative h-52 sm:h-64 rounded-3xl overflow-hidden shadow-[0_1px_2px_rgba(36,30,26,0.06),0_8px_24px_-12px_rgba(36,30,26,0.18)]">
      <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1600&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover" alt="レストランのテーブル">
      <div class="absolute inset-0 bg-gradient-to-t from-[#241E1A]/80 via-[#241E1A]/25 to-transparent"></div>
      <div class="relative h-full flex flex-col justify-end p-6 sm:p-8">
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-white">Restaurant & Cafe</h1>
        <p class="text-white/85 mt-1 text-sm sm:text-base">Sit-down dinners and cozy coffee corners, all in one place.</p>
      </div>
    </div>
  </div>

  <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 py-8 flex gap-6">

    <!-- Sidebar -->
    <aside id="sidebar" class="hidden md:block w-60 shrink-0">
      <div class="sticky top-24">
        <label class="relative block mb-6">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-[#241E1A]/40" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" id="searchInput" placeholder="タイトル・コメントを検索" class="w-full bg-[#FFFFFF] border border-[#241E1A]/10 rounded-xl pl-9 pr-3 py-2.5 text-sm placeholder:text-[#241E1A]/40 focus:outline-none focus:ring-2 focus:ring-[#A7A0FF]">
        </label>

        <p class="font-mono text-[11px] tracking-[0.18em] text-[#241E1A]/40 mb-3 pl-1">STORE</p>
        <nav class="flex flex-col gap-1 pl-5" id="storeNav">
          <a href="#" data-tag="Restaurant" style="--cat-color:#2f5fdb" class="cat-link active flex items-center justify-between py-2 text-sm text-[#241E1A]/70 hover:text-[#241E1A]">Restaurant</a>
          <a href="#" data-tag="Cafe" style="--cat-color:#e05237" class="cat-link flex items-center justify-between py-2 text-sm text-[#241E1A]/70 hover:text-[#241E1A]">Cafe</a>
        </nav>
      </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 min-w-0">

      <!-- Toolbar: 件数表示 + 並び替え -->
      <div class="flex items-center justify-between mb-4">
        <p id="resultCount" class="text-sm text-[#241E1A]/50"></p>
        <label class="relative">
          <select id="sortSelect" class="appearance-none bg-[#FFFFFF] border border-[#241E1A]/10 rounded-lg pl-3 pr-8 py-2 text-sm font-medium text-[#241E1A] focus:outline-none focus:ring-2 focus:ring-[#A7A0FF] cursor-pointer">
            <option value="new">Newest</option>
            <option value="likes">Most Liked</option>
          </select>
          <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[#241E1A]/40 text-[11px] pointer-events-none"></i>
        </label>
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
      <span id="modalTag" class="absolute top-3 left-3 text-white text-[11px] font-semibold px-2.5 py-1 rounded-full"></span>
      <span id="modalPrice" class="absolute bottom-3 right-3 bg-[#FFFFFF]/95 font-mono font-semibold text-sm px-2.5 py-1 rounded-lg"></span>
      <button onclick="closeModal()" class="md:hidden absolute top-3 right-3 bg-[#241E1A]/50 hover:bg-[#241E1A]/70 text-white w-7 h-7 rounded-full flex items-center justify-center" aria-label="閉じる">✕</button>
    </div>

    <!-- 右: ヘッダー + コメント + アクション -->
    <div class="w-full md:w-1/2 flex flex-col min-h-0">

      <!-- ヘッダー: 投稿者 + 編集/削除ボタン -->
      <div class="flex items-center justify-between gap-2 px-4 py-3 border-b border-[#241E1A]/10 shrink-0">
        <div class="flex items-center gap-2 min-w-0">
          <img id="modalAvatar" src="" class="w-8 h-8 rounded-full object-cover shrink-0" alt="">
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

      <!-- アクションバー: ♡ 💬 保存(左) / 🌐マップ(右) -->
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

<script>
  // ---- カテゴリごとの色（Kredoロゴと同じ4色から順番に割り当て） ----
  const categoryColors = {
    'Restaurant': '#2f5fdb', // Kredo Blue
    'Cafe': '#e05237',       // Kredo Red
  };
  function colorOf(tag){ return categoryColors[tag] || '#2f5fdb'; }

  // ---- テストデータ（自由に増減・編集OK） ----
const items = @json($posts);

  const PAGE_SIZE = 21;
  let currentPage = 1;
  let sortMode = 'new'; // 'new' | 'likes'
  let activeCategory = 'Restaurant'; // 'Restaurant' | 'Cafe'
  let searchQuery = '';

  function money(v){ return v + ' PHP'; }

  // ---- 検索語をHTML内で安全に太字ハイライトする ----
  function escapeRegExp(str){
    return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }
  function highlightMatch(text, query){
    if (!query) return text;
    const re = new RegExp('(' + escapeRegExp(query) + ')', 'ig');
    return text.replace(re, '<strong class="bg-[#F1F0FF] text-[#4736F0] rounded px-0.5">$1</strong>');
  }

  function getSortedItems(){
    let filtered = items.filter(it => it.tag === activeCategory);

    if (searchQuery) {
      const q = searchQuery.toLowerCase();
      filtered = filtered.filter(it => {
        const titleMatch = it.title.toLowerCase().includes(q);
        const commentMatch = (it.comments || []).some(c => c.text.toLowerCase().includes(q));
        return titleMatch || commentMatch;
      });
    }

    if (sortMode === 'likes') {
      return [...filtered].sort((a, b) => b.likes - a.likes);
    }
    return filtered;
  }

  function renderGrid(page){
    const sorted = getSortedItems();
    document.getElementById('resultCount').textContent = `${sorted.length} stores found`;

    const grid = document.getElementById('foodGrid');
    grid.innerHTML = '';
    const start = (page - 1) * PAGE_SIZE;
    const pageItems = sorted.slice(start, start + PAGE_SIZE);

    pageItems.forEach((it, idx) => {
      const q = searchQuery.toLowerCase();
      const matchedComment = searchQuery
        ? (it.comments || []).find(c => c.text.toLowerCase().includes(q))
        : null;
      const titleHtml = searchQuery ? highlightMatch(it.title, searchQuery) : it.title;

      const card = document.createElement('article');
      card.className = 'food-card rise-in bg-[#FFFFFF] rounded-2xl overflow-hidden shadow-[0_1px_2px_rgba(36,30,26,0.06),0_8px_24px_-12px_rgba(36,30,26,0.18)] cursor-pointer';
      card.style.animationDelay = (idx * 0.05) + 's';
      card.innerHTML = `
        <div class="relative h-40">
          <img src="${it.img}" class="w-full h-full object-cover" alt="${it.title}" loading="lazy">
          <span class="absolute top-2.5 left-2.5 text-white text-[11px] font-semibold px-2.5 py-1 rounded-full" style="background:${colorOf(it.tag)}">${it.tag}</span>

        </div>
        <div class="p-4">
          <h3 class="font-display font-semibold text-base mb-1 truncate">${titleHtml}</h3>
          <p class="text-sm text-[#241E1A]/55 line-clamp-2 mb-3">${it.desc}</p>
          ${matchedComment ? `
          <p class="text-xs text-[#241E1A]/50 mb-3 flex items-start gap-1.5 bg-[#241E1A]/[0.03] rounded-lg px-2.5 py-2">
            <i class="fa-regular fa-comment mt-0.5 shrink-0"></i>
            <span class="line-clamp-2">${highlightMatch(matchedComment.text, searchQuery)}</span>
          </p>` : ''}
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-1.5 min-w-0">
              <img src="${it.avatar}" class="w-6 h-6 rounded-full object-cover shrink-0" alt="${it.name}">
              <div class="leading-tight min-w-0">
                <p class="text-xs font-semibold truncate">${it.name}</p>
                <p class="text-[11px] text-[#241E1A]/40">${it.time}</p>
              </div>
              <a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(it.mapQuery || it.title)}" target="_blank" onclick="event.stopPropagation()" class="shrink-0 p-1.5 rounded-full hover:bg-[#F1F0FF] text-[#241E1A]/60 hover:text-[#4736F0] transition-colors" aria-label="マップで見る">
                <i class="fa-solid fa-globe text-[15px]"></i>
              </a>
            </div>
            <div class="flex items-center gap-0.5 shrink-0">
              <button class="heart-btn p-1.5 rounded-full hover:bg-[#E08A5B]/10 flex items-center gap-1" aria-label="お気に入り" onclick="event.stopPropagation(); this.classList.toggle('liked'); this.querySelector('i').classList.toggle('fa-regular'); this.querySelector('i').classList.toggle('fa-solid');">
              <i class="fa-regular fa-heart text-[17px] text-[#CE7043]"></i>
              <span class="text-xs text-[#241E1A]/40 font-mono">${it.likes}</span>
              </button>
              <button class="save-btn p-1.5 rounded-full hover:bg-[#F1F0FF] text-[#241E1A]/60 hover:text-[#4736F0] transition-colors" aria-label="保存" onclick="event.stopPropagation(); this.classList.toggle('saved'); this.querySelector('i').classList.toggle('fa-regular'); this.querySelector('i').classList.toggle('fa-solid');">
                <i class="fa-regular fa-bookmark text-[15px]"></i>
              </button>
            </div>
          </div>
        </div>
      `;
      card.addEventListener('click', () => openModal(it));
      grid.appendChild(card);
    });
  }

  function renderPagination(){
    const totalPages = Math.ceil(getSortedItems().length / PAGE_SIZE);
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

  // ---- デモ用: 現在ログインしている(ふりをする)ユーザー ----
  let currentUser = 'Guest';
  const userSelect = document.getElementById('currentUserSelect');
  userSelect.addEventListener('change', (e) => {
    currentUser = e.target.value;
  });

  // ---- コメント描画 ----
  function renderComments(it){
    if (!it.comments) it.comments = [];
    const list = document.getElementById('commentList');
    list.innerHTML = '';

    if (it.comments.length === 0) {
      list.innerHTML = `<p class="text-xs text-[#241E1A]/30 italic">まだコメントはありません</p>`;
    } else {
      it.comments.forEach(c => {
        const row = document.createElement('div');
        row.className = 'flex items-start gap-2';
        row.innerHTML = `
          <div class="w-6 h-6 rounded-full bg-[#E3E0FF] flex items-center justify-center text-[10px] font-semibold text-[#4736F0] shrink-0">${c.name[0]}</div>
          <div class="text-xs leading-snug">
            <span class="font-semibold">${c.name}</span>
            <span class="text-[#241E1A]/70"> ${c.text}</span>
          </div>
        `;
        list.appendChild(row);
      });
    }
    list.scrollTop = list.scrollHeight;
  }

  function openModal(it){
    document.getElementById('modalImg').src = it.img;
    document.getElementById('modalTag').textContent = it.tag;
    document.getElementById('modalTag').style.background = colorOf(it.tag);
    document.getElementById('modalPrice').textContent = money(it.price);
    document.getElementById('modalTitle').textContent = it.title;
    document.getElementById('modalDesc').textContent = it.desc;
    document.getElementById('modalAvatar').src = it.avatar;
    document.getElementById('modalName').textContent = it.name;
    document.getElementById('modalTime').textContent = it.time;
    document.getElementById('modalLikeCount').textContent = `${it.likes}件のいいね`;

    document.getElementById('modalMapLink').href =
      `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(it.mapQuery || it.title)}`;

    const likeIcon = document.querySelector('#modalLikeBtn i');
    likeIcon.className = 'fa-regular fa-heart text-[22px]';
    document.getElementById('modalLikeBtn').onclick = function(){
      this.classList.toggle('liked');
      likeIcon.classList.toggle('fa-regular');
      likeIcon.classList.toggle('fa-solid');
      likeIcon.classList.toggle('text-[#CE7043]');
    };
    const saveIcon = document.querySelector('#modalSaveBtn i');
    saveIcon.className = 'fa-regular fa-bookmark text-[22px]';
    document.getElementById('modalSaveBtn').onclick = function(){
      this.classList.toggle('saved');
      saveIcon.classList.toggle('fa-regular');
      saveIcon.classList.toggle('fa-solid');
      saveIcon.classList.toggle('text-[#4736F0]');
    };

    // 投稿主(it.name)と今のログインユーザーが一致する時だけ、編集・削除ボタンを表示
    const isOwner = (it.name === currentUser);
    const ownerActions = document.getElementById('ownerActions');
    ownerActions.classList.toggle('hidden', !isOwner);
    ownerActions.classList.toggle('flex', isOwner);

    document.getElementById('modalEditBtn').onclick = () => {
      alert(`「${it.title}」の編集画面へ移動します`);
    };
    document.getElementById('modalDeleteBtn').onclick = () => {
      if (confirm(`「${it.title}」を削除しますか？この操作は取り消せません。`)) {
        alert('削除しました(デモ)');
        closeModal();
      }
    };

    renderComments(it);
    document.getElementById('commentForm').onsubmit = (e) => {
      e.preventDefault();
      const input = document.getElementById('commentInput');
      const text = input.value.trim();
      if (!text) return;
      if (!it.comments) it.comments = [];
      it.comments.push({ name: currentUser === 'Guest' ? 'ゲスト' : currentUser, text });
      input.value = '';
      renderComments(it);
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

  // STOREサイドバーのカテゴリ選択（クリックで絞り込み+再描画）
  document.querySelectorAll('#storeNav .cat-link').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      document.querySelectorAll('#storeNav .cat-link').forEach(l => l.classList.remove('active'));
      link.classList.add('active');
      activeCategory = link.dataset.tag;
      currentPage = 1;
      renderGrid(currentPage);
      renderPagination();
    });
  });

  document.getElementById('sortSelect').addEventListener('change', (e) => {
    sortMode = e.target.value;
    currentPage = 1;
    renderGrid(currentPage);
    renderPagination();
  });

  // 検索ボックス:タイトル or コメント内容に一致したものだけ表示
  document.getElementById('searchInput').addEventListener('input', (e) => {
    searchQuery = e.target.value.trim();
    currentPage = 1;
    renderGrid(currentPage);
    renderPagination();
  });

  renderGrid(currentPage);
  renderPagination();

  // モバイルではサイドバーを初期表示
  if(window.innerWidth >= 768){ document.getElementById('sidebar').classList.remove('hidden'); }
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
<!-- footer nav: Kredoロゴと同じ配色・フォントに統一 -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
<nav class="fixed bottom-0 w-full z-50 bg-white shadow-[0_-4px_20px_-4px_rgba(30,58,138,0.15)] flex justify-around items-center h-20 px-4 pb-2 border-t border-slate-100">
  <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-1 text-[#2f5fdb] px-4 py-1 active:scale-90 transition-all duration-200">
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
    <i class="fa-solid fa-globe text-[20px]"></i>
    <span class="text-[10px] font-bold tracking-wide" style="font-family:'Poppins','Noto Sans JP',sans-serif;">Map</span>
  </a>
</nav>
</div>

@endsection
