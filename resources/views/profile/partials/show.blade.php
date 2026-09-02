@extends('layouts.app')

@section('title', $user->name . 'さんのプロフィール - Kredo Plus')

@section('content')
<div class="bg-slate-50 min-h-[calc(100vh-80px)] py-8 px-4 sm:px-6">
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- 1. プロフィールカード（ヘッダー） -->
<div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-100 flex items-center gap-4 sm:gap-6">
    
    <!-- アバターアイコン -->
    <div class="relative shrink-0">
        @if ($user->avatar_url)
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                 class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover shadow-sm ring-2 ring-sky-50">
        @else
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-sky-400 to-brand-blue text-white text-2xl sm:text-3xl font-extrabold flex items-center justify-center shadow-sm ring-2 ring-sky-50">
                {{ Str::of($user->name)->substr(0, 1)->upper() }}
            </div>
        @endif
    </div>

    <!-- ユーザー詳細情報 & 卒業予定日（同一行に配置） -->
    <div class="flex-1 min-w-0 flex items-center justify-between gap-4 flex-wrap sm:flex-nowrap">
        
        <!-- アカウント名（倍くらいの大きさに拡大） & 役職バッジ -->
        <div class="flex items-center gap-2.5 min-w-0">
            <h1 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight leading-none truncate">
                {{ $user->name }}
            </h1>
            @if($user->isAdmin())
                <span class="bg-indigo-50 text-indigo-600 border border-indigo-100 text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0">
                    管理者
                </span>
            @endif
        </div>

        <!-- 卒業予定日（サイズ維持・右端寄せ） -->
        <div class="inline-flex items-center gap-2.5 bg-violet-50/80 border border-violet-100/80 rounded-lg px-2.5 py-1 text-xs shrink-0 ml-auto">
            <div class="flex items-center gap-1 text-violet-600 font-bold shrink-0">
                <span class="material-symbols-outlined !text-sm">flag</span>
                <span class="text-[11px]">卒業予定</span>
            </div>

            <div class="h-3 w-[1px] bg-violet-200"></div>

            <div class="flex items-center gap-1.5">
                @if ($user->graduation_date)
                    @php $daysLeft = now()->startOfDay()->diffInDays($user->graduation_date, false); @endphp
                    <span class="text-slate-500 text-[11px] font-medium hidden sm:inline">{{ $user->graduation_date->format('Y/n/j') }}</span>
                    
                    @if ($daysLeft > 0)
                        <span class="font-extrabold text-violet-700 bg-violet-100/80 px-1.5 py-0.5 rounded text-[11px]">
                            残り{{ $daysLeft }}日
                        </span>
                    @elseif ($daysLeft === 0)
                        <span class="font-extrabold text-violet-700 bg-violet-100/80 px-1.5 py-0.5 rounded text-[11px]">
                            本日
                        </span>
                    @else
                        <span class="text-slate-400 text-[11px]">(終了)</span>
                    @endif
                @else
                    <span class="text-slate-400 text-[11px]">未設定</span>
                @endif
            </div>
        </div>

    </div>
</div>

        <!-- 2. 留学情報の投稿一覧（本人の投稿のみ） -->
        <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-100 space-y-5">
            <div class="flex items-center gap-2 text-brand-blue font-extrabold text-base sm:text-lg">
                <span class="material-symbols-outlined">grid_view</span>
                留学情報の投稿 ({{ $posts->count() }})
            </div>

            <!-- 投稿グリッド -->
            @if ($posts->isEmpty())
                <div class="flex flex-col items-center justify-center text-center py-10 gap-2">
                    <span class="material-symbols-outlined text-slate-300 !text-4xl">post_add</span>
                    <p class="text-sm text-slate-400">まだ投稿がありません。</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3.5 sm:gap-4">
                    @foreach ($posts as $post)
                        <a href="#" data-post-id="{{ $post->id }}" onclick="event.preventDefault(); openPostModal({{ $post->id }})"
                           class="post-card block rounded-xl overflow-hidden border border-slate-100 hover:shadow-md transition-all bg-white">
                            <div class="relative h-24 sm:h-28 bg-slate-100">
                                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                @if ($post->category)
                                    <span class="absolute top-1.5 left-1.5 text-[10px] font-bold px-2 py-0.5 rounded-full"
                                          style="background:{{ $post->category->backgroundColor() }}; color:{{ $post->category->textColor() }}">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="p-2.5">
                                <p class="text-xs font-bold text-slate-700 truncate">{{ $post->title }}</p>
                                <div class="flex items-center justify-between mt-1.5">
                                    <span class="text-[11px] text-slate-400 truncate">{{ $post->user->name ?? '不明なユーザー' }}</span>
                                    <span class="flex items-center gap-2 shrink-0 text-slate-400">
                                        <span class="post-card-like flex items-center gap-0.5 text-[11px]">
                                            <i class="{{ $post->liked_by_me ? 'fa-solid' : 'fa-regular' }} fa-heart text-[#CE7043] text-[11px]"></i>
                                            <span class="post-card-like-count">{{ $post->likes_count }}</span>
                                        </span>
                                        <i class="post-card-bookmark {{ $post->bookmarked_by_me ? 'fa-solid' : 'fa-regular' }} fa-bookmark text-[11px]"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

<!-- 3. 投稿詳細ポップアップ -->
<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
    <div class="modal-panel bg-white rounded-2xl w-full max-w-3xl overflow-hidden shadow-2xl opacity-0 translate-y-3 transition-all duration-200 flex flex-col md:flex-row md:h-[520px]">

        <!-- 左: 写真(固定) -->
        <div class="relative w-full md:w-1/2 h-64 md:h-full shrink-0">
            <img id="modalImg" src="" class="w-full h-full object-cover" alt="">
            <span id="modalTag" class="absolute top-3 left-3 text-[11px] font-semibold px-2.5 py-1 rounded-full"></span>
            <span id="modalPrice" class="absolute bottom-3 right-3 bg-white/95 font-mono font-semibold text-sm px-2.5 py-1 rounded-lg"></span>
            <button onclick="closePostModal()" class="md:hidden absolute top-3 right-3 bg-slate-900/50 hover:bg-slate-900/70 text-white w-7 h-7 rounded-full flex items-center justify-center" aria-label="閉じる">✕</button>
        </div>

        <!-- 右: ヘッダー + 説明 + アクション -->
        <div class="w-full md:w-1/2 flex flex-col min-h-0">

            <!-- ヘッダー: 投稿者 -->
            <div class="flex items-center justify-between gap-2 px-4 py-3 border-b border-slate-100 shrink-0">
                <div class="flex items-center gap-2 min-w-0">
                    <div id="modalAvatarWrap" class="shrink-0"></div>
                    <div class="leading-tight min-w-0">
                        <p id="modalName" class="text-sm font-semibold truncate"></p>
                        <p id="modalTime" class="text-xs text-slate-400"></p>
                    </div>
                </div>

                <div class="flex items-center gap-1 shrink-0">
                    <button onclick="closePostModal()" class="hidden md:flex w-8 h-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors" aria-label="閉じる">
                        <i class="fa-solid fa-xmark text-[16px]"></i>
                    </button>
                </div>
            </div>

            <!-- スクロール領域: タイトル・説明・コメント一覧 -->
            <div class="flex-1 overflow-y-auto px-4 py-3 min-h-0">
                <h3 id="modalTitle" class="font-bold text-lg mb-1 text-slate-800"></h3>
                <p id="modalDesc" class="text-sm text-slate-500 mb-4"></p>

                <p class="text-xs font-mono tracking-[0.1em] text-slate-400 mb-2">COMMENTS</p>
                <div id="postModalCommentList" class="space-y-3"></div>
            </div>

            <!-- アクションバー -->
            <div class="px-4 pt-2 pb-1 border-t border-slate-100 shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button id="postModalLikeBtn" class="text-slate-700 hover:text-[#CE7043] transition-colors active:scale-90" aria-label="いいね">
                            <i class="fa-regular fa-heart text-[22px]"></i>
                        </button>
                        <button onclick="document.getElementById('postModalCommentInput').focus()" class="text-slate-700 hover:text-brand-blue transition-colors active:scale-90" aria-label="コメント">
                            <i class="fa-regular fa-comment text-[22px]"></i>
                        </button>
                        <button id="postModalSaveBtn" class="text-slate-700 hover:text-brand-blue transition-colors active:scale-90" aria-label="保存">
                            <i class="fa-regular fa-bookmark text-[22px]"></i>
                        </button>
                    </div>
                    <a id="postModalMapLink" href="#" target="_blank" class="text-slate-700 hover:text-brand-blue transition-colors active:scale-90" aria-label="マップで見る">
                        <i class="fa-solid fa-globe text-[22px]"></i>
                    </a>
                </div>
                <p id="postModalLikeCount" class="text-xs font-semibold mt-1.5 mb-2 text-slate-500"></p>
            </div>

            <!-- コメント投稿フォーム -->
            <form id="postModalCommentForm" class="flex items-center gap-2 px-4 py-3 border-t border-slate-100 shrink-0">
                <input type="text" id="postModalCommentInput" class="flex-1 border border-slate-200 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30" placeholder="コメントを書く...">
                <button type="submit" class="w-10 h-10 shrink-0 flex items-center justify-center rounded-full bg-brand-blue text-white hover:bg-blue-600 transition-colors active:scale-90">
                    <i class="fa-solid fa-paper-plane text-sm"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const profilePosts = @json($posts->keyBy('id'));
    const categoryColors = @json($categoryColors);
    function colorOf(tag) { return categoryColors[tag] || { bg: '#2f5fdb1a', text: '#2f5fdb' }; }
    const profileCurrentUserId = {{ auth()->id() ?? 'null' }};
    const profileInfoRouteBase = @json(url('information'));
    const profilePostsRouteBase = @json(url('information/posts'));
    const profileCsrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let currentPostModalItem = null;

    function postInitials(name) {
        return (name || '?').trim().charAt(0).toUpperCase();
    }

    function postAvatarHtml(user, sizeClass, textSizeClass) {
        const name = user ? user.name : '不明なユーザー';
        if (user && user.avatar_url) {
            return `<img src="${user.avatar_url}" alt="${name}" class="${sizeClass} rounded-full object-cover shrink-0">`;
        }
        return `<div class="${sizeClass} rounded-full bg-brand-blue text-white font-bold flex items-center justify-center shrink-0 ${textSizeClass}">${postInitials(name)}</div>`;
    }

    function postMoney(v) {
        return (v === null || v === undefined) ? '' : Math.trunc(v) + ' PHP';
    }

    function postTimeAgo(dateStr) {
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

    function openPostModal(id) {
        const it = profilePosts[id];
        if (!it) return;
        currentPostModalItem = it;

        const tag = it.category ? it.category.name : '';
        const userName = it.user ? it.user.name : '不明なユーザー';

        document.getElementById('modalImg').src = it.image_url;
        const modalTagEl = document.getElementById('modalTag');
        modalTagEl.textContent = tag;
        modalTagEl.style.background = colorOf(tag).bg;
        modalTagEl.style.color = colorOf(tag).text;
        document.getElementById('modalPrice').textContent = postMoney(it.price);
        document.getElementById('modalTitle').textContent = it.title;
        document.getElementById('modalDesc').textContent = it.description ?? '';
        document.getElementById('modalAvatarWrap').innerHTML = postAvatarHtml(it.user, 'w-8 h-8', 'text-xs');
        document.getElementById('modalName').textContent = userName;
        document.getElementById('modalTime').textContent = postTimeAgo(it.created_at);

        updatePostLikeUI(it);
        updatePostBookmarkUI(it);
        renderPostModalComments(it);

        document.getElementById('postModalMapLink').href =
            `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
                it.earth_location
                    ? `${it.earth_location.latitude},${it.earth_location.longitude}`
                    : it.title
            )}`;

        const modal = document.getElementById('detailModal');
        const panel = modal.querySelector('.modal-panel');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            panel.classList.remove('opacity-0', 'translate-y-3');
        });
    }

    function closePostModal() {
        const modal = document.getElementById('detailModal');
        const panel = modal.querySelector('.modal-panel');
        panel.classList.add('opacity-0', 'translate-y-3');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }

    document.getElementById('detailModal').addEventListener('click', (e) => {
        if (e.target.id === 'detailModal') closePostModal();
    });

    function updatePostLikeUI(it) {
        const cardLike = document.querySelector(`.post-card[data-post-id="${it.id}"] .post-card-like`);
        if (cardLike) {
            const icon = cardLike.querySelector('i');
            icon.classList.toggle('fa-solid', !!it.liked_by_me);
            icon.classList.toggle('fa-regular', !it.liked_by_me);
            cardLike.querySelector('.post-card-like-count').textContent = it.likes_count;
        }
        if (currentPostModalItem && currentPostModalItem.id === it.id) {
            const modalIcon = document.querySelector('#postModalLikeBtn i');
            modalIcon.className = (it.liked_by_me ? 'fa-solid' : 'fa-regular') + ' fa-heart text-[22px]' + (it.liked_by_me ? ' text-[#CE7043]' : '');
            document.getElementById('postModalLikeCount').textContent = `${it.likes_count ?? 0}件のいいね`;
        }
    }

    function updatePostBookmarkUI(it) {
        const cardBookmark = document.querySelector(`.post-card[data-post-id="${it.id}"] .post-card-bookmark`);
        if (cardBookmark) {
            cardBookmark.classList.toggle('fa-solid', !!it.bookmarked_by_me);
            cardBookmark.classList.toggle('fa-regular', !it.bookmarked_by_me);
        }
        if (currentPostModalItem && currentPostModalItem.id === it.id) {
            const modalIcon = document.querySelector('#postModalSaveBtn i');
            modalIcon.classList.toggle('fa-solid', !!it.bookmarked_by_me);
            modalIcon.classList.toggle('fa-regular', !it.bookmarked_by_me);
        }
    }

    document.getElementById('postModalLikeBtn').addEventListener('click', () => {
        if (!currentPostModalItem) return;
        if (profileCurrentUserId === null) { window.location.href = '{{ route('login') }}'; return; }

        fetch(`${profilePostsRouteBase}/${currentPostModalItem.id}/like`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': profileCsrfToken, 'Accept': 'application/json' },
        })
            .then(r => r.json())
            .then(data => {
                currentPostModalItem.liked_by_me = data.liked;
                currentPostModalItem.likes_count = data.likes_count;
                updatePostLikeUI(currentPostModalItem);
            })
            .catch(() => alert('通信エラーが発生しました。もう一度お試しください。'));
    });

    document.getElementById('postModalSaveBtn').addEventListener('click', () => {
        if (!currentPostModalItem) return;
        if (profileCurrentUserId === null) { window.location.href = '{{ route('login') }}'; return; }

        fetch(`${profilePostsRouteBase}/${currentPostModalItem.id}/bookmark`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': profileCsrfToken, 'Accept': 'application/json' },
        })
            .then(r => r.json())
            .then(data => {
                currentPostModalItem.bookmarked_by_me = data.bookmarked;
                updatePostBookmarkUI(currentPostModalItem);
            })
            .catch(() => alert('通信エラーが発生しました。もう一度お試しください。'));
    });

    function renderPostModalComments(it) {
        const list = document.getElementById('postModalCommentList');
        list.innerHTML = '';
        const comments = it.comments || [];

        if (comments.length === 0) {
            list.innerHTML = `<p class="text-xs text-slate-300 italic">まだコメントはありません</p>`;
        } else {
            comments.forEach(c => {
                const name = c.user ? c.user.name : (c.user_name || 'ゲスト');
                const isOwnComment = profileCurrentUserId !== null && c.user_id === profileCurrentUserId;
                const row = document.createElement('div');
                row.className = 'flex items-start gap-2';
                row.innerHTML = `
                    <div class="w-6 h-6 rounded-full bg-sky-100 flex items-center justify-center text-[10px] font-semibold text-brand-blue shrink-0">${postInitials(name)}</div>
                    <div class="text-xs leading-snug flex-1 min-w-0">
                        <span class="font-semibold">${name}</span>
                        <span class="text-slate-500"> ${c.body}</span>
                    </div>
                    ${isOwnComment ? `<button type="button" class="post-comment-delete-btn text-slate-300 hover:text-rose-500 transition-colors shrink-0" aria-label="コメントを削除" data-comment-id="${c.id}"><i class="fa-solid fa-trash-can text-[11px]"></i></button>` : ''}
                `;
                list.appendChild(row);
            });
            list.querySelectorAll('.post-comment-delete-btn').forEach(btn => {
                btn.addEventListener('click', () => deletePostComment(btn.dataset.commentId));
            });
        }
        list.scrollTop = list.scrollHeight;
    }

    function deletePostComment(commentId) {
        fetch(`${profilePostsRouteBase}/comments/${commentId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': profileCsrfToken, 'Accept': 'application/json' },
        })
            .then(r => r.json())
            .then(data => {
                if (!currentPostModalItem) return;
                currentPostModalItem.comments = (currentPostModalItem.comments || []).filter(c => String(c.id) !== String(commentId));
                renderPostModalComments(currentPostModalItem);
            })
            .catch(() => alert('コメントの削除に失敗しました。もう一度お試しください。'));
    }

    document.getElementById('postModalCommentForm').addEventListener('submit', (e) => {
        e.preventDefault();
        if (!currentPostModalItem) return;
        if (profileCurrentUserId === null) { window.location.href = '{{ route('login') }}'; return; }

        const input = document.getElementById('postModalCommentInput');
        const body = input.value.trim();
        if (!body) return;

        fetch(`${profilePostsRouteBase}/${currentPostModalItem.id}/comments`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': profileCsrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ body }),
        })
            .then(r => r.json())
            .then(data => {
                if (!currentPostModalItem.comments) currentPostModalItem.comments = [];
                currentPostModalItem.comments.push({
                    id: data.comment.id,
                    body: data.comment.body,
                    user_id: data.comment.user_id,
                    user: { name: data.comment.user_name },
                    created_at: data.comment.created_at,
                });
                input.value = '';
                renderPostModalComments(currentPostModalItem);
            })
            .catch(() => alert('コメントの送信に失敗しました。もう一度お試しください。'));
    });
</script>
@endsection