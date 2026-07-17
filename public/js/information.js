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

    if (deleteBtn && deleteForm) {
        deleteBtn.addEventListener('click', () => {
            if (confirm('この投稿を削除しますか？この操作は取り消せません。')) {
                deleteForm.submit();
            }
        });
    }

});
