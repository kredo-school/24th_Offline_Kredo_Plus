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

});
