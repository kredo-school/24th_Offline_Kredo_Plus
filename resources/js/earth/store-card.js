let card = null;

// ==========================================
// Show Store Card
// ==========================================

export function showStoreCard(store) {

    if (card) {

        card.remove();

    }

    card = document.createElement("div");

    card.id = "storeCard";

    card.innerHTML = `

        <button id="closeCard">

            ✕

        </button>

        <img

            src="${store.image}"

            class="store-image"

            alt="${store.name}"

        >

        <div class="store-content">

            <h2>

                ${store.name}

            </h2>

            <div class="info-row">

                <span>

                    ${store.price}

                </span>

            </div>

            <p class="category">

                ${store.category}

            </p>

            <p class="location">

                📍 ${store.city || "ー"}
            </p>

            <p class="description">

                ${store.description}

            </p>

        </div>

    `;

    document.body.appendChild(card);

    requestAnimationFrame(() => {

        card.classList.add("show");

    });

    document
        .getElementById("closeCard")
        .addEventListener("click", hideStoreCard);

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

    }, 400);

}
