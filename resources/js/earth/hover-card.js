let hoverCard = null;

// ==========================================
// Show Hover Card
// ==========================================

export function showHoverCard(location, x, y) {

    hideHoverCard();

    const post = location.post;
    const category = post?.category;

    hoverCard = document.createElement("div");

    hoverCard.className = "hover-card";

    hoverCard.innerHTML = `

        ${
            post?.image
                ? `
                    <img
                        src="/storage/${post.image}"
                        class="hover-image"
                        alt="${post.title ?? location.place_name}"
                    >
                `
                : ""
        }

        <div class="hover-content">

            <div class="hover-title">
                ${post?.title ?? location.place_name}
            </div>

            <div class="hover-location">
                📍 ${location.place_name || "ー"}
            </div>

            <div class="hover-category">
                ${category?.name ?? "Other"}
            </div>

        </div>

    `;

    hoverCard.style.left = `${x + 15}px`;
    hoverCard.style.top = `${y - 120}px`;

    document.body.appendChild(hoverCard);

}


// ==========================================
// Hide Hover Card
// ==========================================

export function hideHoverCard() {

    if (hoverCard) {

        hoverCard.remove();

        hoverCard = null;

    }

}
