let card = null;

// ==========================================
// Show Store Card
// ==========================================

export function showStoreCard(store) {

    // すでに表示されているCardがあれば削除
    if (card) {
        card.remove();
        card = null;
    }

    card = document.createElement("article");

    card.id = "storeCard";

    card.className = `
        store-card
        fixed
        bottom-6
        right-6
        z-[9999]
        w-80
        relative
        bg-[#FFFFFF]
        rounded-2xl
        overflow-hidden
        shadow-[0_1px_2px_rgba(36,30,26,0.06),0_8px_24px_-12px_rgba(36,30,26,0.18)]
    `;

    // ==========================================
    // Image
    // ==========================================

    const image = store.image || "";

    // ==========================================
    // Category
    // ==========================================

    const category = store.category || "Other";

    // ==========================================
    // Price
    // ==========================================

    const price = store.price
        ? `₱${store.price}`
        : "";

    // ==========================================
    // Like
    // ==========================================

    const likesCount = store.likesCount ?? 0;

    const likeIcon = store.likedByMe
        ? "fa-solid"
        : "fa-regular";

    const likeActive = store.likedByMe
        ? "liked"
        : "";

    // ==========================================
    // Bookmark
    // ==========================================

    const bookmarkIcon = store.bookmarkedByMe
        ? "fa-solid"
        : "fa-regular";

    const bookmarkActive = store.bookmarkedByMe
        ? "saved"
        : "";

    // ==========================================
    // Card HTML
    // ==========================================

    card.innerHTML = `
        <button id="closeCard" aria-label="閉じる">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <img src="${image}" class="store-image" style="width:100%; height:220px; object-fit:cover; display:block;" alt="${store.name || ""}">

        <div class="store-content">

            <div class="store-meta">
                <span class="category-badge" style="background:${store.pinColor || "#2f5fdb"}">
                    ${category}
                </span>
                ${price ? `<span class="price-badge">${price}</span>` : ""}
            </div>

            <h3>${store.name || ""}</h3>
            <p class="description">${store.description || ""}</p>

            <div class="store-footer">
                <div class="user-info">
                    ${
                        store.userAvatar
                            ? `<img src="${store.userAvatar}" class="user-avatar" alt="${store.userName || ""}">`
                            : `<div class="user-avatar" style="background:#E3E0FF;display:flex;align-items:center;justify-content:center;">
                                 <i class="fa-regular fa-user" style="font-size:10px;color:#4736F0;"></i>
                               </div>`
                    }
                    <div class="user-details">
                        <p class="user-name">${store.userName || "Unknown"}</p>
                        <p class="created-at">${store.createdAt || ""}</p>
                    </div>
                </div>

                <div class="card-actions">
                    <button id="storeLikeBtn" class="heart-btn ${likeActive}" aria-label="お気に入り">
                        <i class="${likeIcon} fa-heart"></i>
                        <span id="storeLikeCount" class="like-count">${likesCount}</span>
                    </button>
                    <button id="storeBookmarkBtn" class="save-btn ${bookmarkActive}" aria-label="保存">
                        <i class="${bookmarkIcon} fa-bookmark"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

    // ==========================================
    // CardをBodyへ追加
    // ==========================================

    document.body.appendChild(card);

    // ==========================================
    // Animation
    // ==========================================

    requestAnimationFrame(() => {
        card.classList.add("show");
    });

    // ==========================================
    // Close
    // ==========================================

    document
        .getElementById("closeCard")
        .addEventListener("click", hideStoreCard);

    // ==========================================
    // Like
    // ==========================================

    const likeButton = document.getElementById("storeLikeBtn");

    if (likeButton) {

        likeButton.addEventListener("click", async (event) => {

            event.stopPropagation();

            if (!store.postId) return;

            try {
                const response = await fetch(`/information/posts/${store.postId}/like`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    },
                });

                if (!response.ok) throw new Error("Like request failed");

                const data = await response.json();

                const icon = likeButton.querySelector("i");

                likeButton.classList.toggle("liked", data.liked);
                icon.classList.toggle("fa-solid", data.liked);
                icon.classList.toggle("fa-regular", !data.liked);

                document.getElementById("storeLikeCount").textContent = data.likes_count;

            } catch (error) {
                console.error(error);
            }

        });

    }

    // ==========================================
    // Bookmark
    // ==========================================

    const bookmarkButton = document.getElementById("storeBookmarkBtn");

    if (bookmarkButton) {

        bookmarkButton.addEventListener("click", async (event) => {

            event.stopPropagation();

            if (!store.postId) return;

            try {
                const response = await fetch(`/information/posts/${store.postId}/bookmark`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    },
                });

                if (!response.ok) throw new Error("Bookmark request failed");

                const data = await response.json();

                const icon = bookmarkButton.querySelector("i");

                bookmarkButton.classList.toggle("saved", data.bookmarked);
                icon.classList.toggle("fa-solid", data.bookmarked);
                icon.classList.toggle("fa-regular", !data.bookmarked);

            } catch (error) {
                console.error(error);
            }

        });

    }

}

// ==========================================
// Hide Store Card
// ==========================================

export function hideStoreCard() {

    if (!card) return;

    card.classList.remove("show");

    setTimeout(() => {

        if (card) {
            card.remove();
            card = null;
        }

    }, 300);

}
