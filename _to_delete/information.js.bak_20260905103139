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

// ============================================================
// 場所追加前に投稿内容を一時保存
// ============================================================

document.addEventListener('DOMContentLoaded', () => {

    const addLocationButton =
        document.getElementById('addLocationButton');

    if (!addLocationButton) {
        return;
    }

    addLocationButton.addEventListener('click', async (event) => {

        event.preventDefault();

        const form = addLocationButton.closest('form');

        if (!form) {
            window.location.href = addLocationButton.href;
            return;
        }

        const formData = new FormData();

        const mainCategory = form.querySelector('#mainCategorySelect');
        const category = form.querySelector('[name="category_id"]');
        const title = form.querySelector('[name="title"]');
        const description = form.querySelector('[name="description"]');
        const price = form.querySelector('[name="price"]');
        const image = form.querySelector('[name="image"]');

        if (mainCategory) {
        formData.append('main_category', mainCategory.value);
        }

        if (category) {
            formData.append('category_id', category.value);
        }

        if (title) {
            formData.append('title', title.value);
        }

        if (description) {
            formData.append('description', description.value);
        }

        if (price) {
            formData.append('price', price.value);
        }

        // 画像を選択している場合だけ保存
        if (image && image.files.length > 0) {
            formData.append('image', image.files[0]);
        }

        const csrfToken =
            form.querySelector('input[name="_token"]').value;

        try {

            const response = await fetch(
                '/information/draft',
                {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData,
                }
            );

            if (!response.ok) {
                throw new Error('下書き保存に失敗しました');
            }

            // 保存成功後、位置情報画面へ移動
            window.location.href = addLocationButton.href;

        } catch (error) {

            console.error(error);

            alert('入力内容の保存に失敗しました。');
        }

    });

});
