import Alpine from 'alpinejs';

// noticeAdmin 関数をグローバルスコープ（window）に登録する
window.noticeAdmin = function(initialNotices = []) {
    return {
        sentHistory: (initialNotices || []).map(n => ({ ...n, expanded: false })),
        isModalOpen: false,
        // ... (先ほどの JS コード全体)
    };
};

Alpine.start();