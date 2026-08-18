document.addEventListener('DOMContentLoaded', () => {

    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];

            if (file) {
                imagePreview.src = URL.createObjectURL(file);
            }
        });
    }

    const deleteBtn = document.getElementById('deleteBtn');
    const deleteForm = document.getElementById('deleteForm');
    const deleteModal = document.getElementById('deleteModal');
    const deleteModalTitle = document.getElementById('deleteModalTitle');
    const deleteCancelBtn = document.getElementById('deleteCancelBtn');
    const deleteConfirmBtn = document.getElementById('deleteConfirmBtn');

    if (deleteBtn && deleteForm && deleteModal) {

        deleteBtn.addEventListener('click', () => {
            const title = deleteBtn.dataset.title || '';

            deleteModalTitle.textContent = `「${title}」を削除しますか？`;

            deleteModal.classList.add('is-open');
        });

        deleteCancelBtn.addEventListener('click', () => {
            deleteModal.classList.remove('is-open');
        });

        deleteConfirmBtn.addEventListener('click', () => {
            deleteForm.submit();
        });

        deleteModal.addEventListener('click', (e) => {
            if (e.target === deleteModal) {
                deleteModal.classList.remove('is-open');
            }
        });
    }

    // ============================================================
    // メインカテゴリー → サブカテゴリー の2段階選択
    // (投稿フォーム・編集フォーム共通、どちらもプルダウン)
    // ============================================================

    const mainCategorySelect = document.getElementById('mainCategorySelect');
    const subCategorySelect = document.getElementById('categorySelect');

    if (mainCategorySelect && subCategorySelect) {
        const subOptions = Array.from(subCategorySelect.options).filter(opt => opt.value !== '');

        const filterSubOptions = (section) => {
            subOptions.forEach(opt => {
                const matches = opt.dataset.section === section;
                opt.hidden = !matches;
                opt.disabled = !matches;
            });

            // 選択中のサブカテゴリーが、選んだメインカテゴリーと合わなくなったらリセット
            const current = subCategorySelect.options[subCategorySelect.selectedIndex];
            if (current && current.dataset.section !== undefined && current.dataset.section !== section) {
                subCategorySelect.value = '';
            }
        };

        mainCategorySelect.addEventListener('change', () => {
            filterSubOptions(mainCategorySelect.value);
            subCategorySelect.style.color = subCategorySelect.value ? 'var(--ink)' : '#9ca3af';
        });

        subCategorySelect.addEventListener('change', () => {
            subCategorySelect.style.color = subCategorySelect.value ? 'var(--ink)' : '#9ca3af';
        });

        subCategorySelect.style.color = subCategorySelect.value ? 'var(--ink)' : '#9ca3af';

        // バリデーションエラーで戻ってきた時や、編集画面で最初から選ばれている場合
        if (mainCategorySelect.value) {
            filterSubOptions(mainCategorySelect.value);
        }
    }

});
