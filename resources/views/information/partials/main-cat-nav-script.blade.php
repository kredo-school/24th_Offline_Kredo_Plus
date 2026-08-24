{{--
    #mainCatNav(上部のメインカテゴリー一覧ボタン)の位置調整。

    ここは通常のリンク遷移(SPAではなく毎回フルページ読み込み)なので、ブラウザは基本的に
    ページが変わるたびに横スクロール位置を0(Carinderiaが先頭)に戻してしまう。
    それだと「Restaurant & Cafeを一番左に持ってきてクリックしたのに、次のページでは
    またCarinderiaが先頭に戻っている」という、実質的な巻き戻りが起きてしまう。

    これを防ぐため、sessionStorageに今のスクロール位置を常に保存しておき、
    次のページが読み込まれた瞬間(描画される前)にその位置を復元する。
    こうすると、どのボタンをどこに持ってきていても、次のページでも同じ並びのまま維持される。

    ただし、それでも今見ているカテゴリーのボタンが画面外になってしまうケース
    (保存された位置が無い初回アクセスや、カテゴリー数が変わった場合など)だけは
    フォールバックとして自動で見える位置まで動かす。
--}}
<script>
  (function () {
    var STORAGE_KEY = 'mainCatNavScrollLeft';
    var nav = document.getElementById('mainCatNav');
    if (!nav) return;

    // 前のページで見ていた横スクロール位置を、描画される前に復元する
    var saved = sessionStorage.getItem(STORAGE_KEY);
    if (saved !== null) {
      nav.scrollLeft = parseInt(saved, 10) || 0;
    }

    // スクロールするたびに位置を保存しておき、次のページ遷移時に復元できるようにする
    nav.addEventListener('scroll', function () {
      sessionStorage.setItem(STORAGE_KEY, nav.scrollLeft);
    });

    // 復元してもなお、今のカテゴリーのボタンが画面外なら、そこだけ見える位置まで動かす
    var active = nav.querySelector('[data-active="true"]');
    if (!active) return;
    var navRect = nav.getBoundingClientRect();
    var activeRect = active.getBoundingClientRect();
    if (activeRect.left >= navRect.left && activeRect.right <= navRect.right) return;
    active.scrollIntoView({ inline: 'center', block: 'nearest' });
  })();
</script>
