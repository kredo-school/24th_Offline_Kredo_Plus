let hoverCard = null;

// ==========================================
// Show Hover Card
// ==========================================

export function showHoverCard(store, x, y) {

    hideHoverCard();

    hoverCard = document.createElement("div");

    hoverCard.className = "hover-card";

    hoverCard.innerHTML = `

        <div class="hover-title">
            ${store.name}
        </div>

        <div class="hover-info">
            ⭐ ${store.rating}
        </div>

        <div class="hover-category">
            ${store.category}
        </div>

    `;

    hoverCard.style.left = `${x}px`;
    hoverCard.style.top = `${y - 90}px`;

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