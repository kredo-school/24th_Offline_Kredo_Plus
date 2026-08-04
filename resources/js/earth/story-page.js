let storyPage = null;

// ==========================================
// Show Story Page
// ==========================================

export function showStoryPage(store) {

    if (storyPage) {

        storyPage.remove();

    }

    storyPage = document.createElement("div");

    storyPage.id = "storyPage";

    storyPage.innerHTML = `

        <button id="closeStory">

            ✕

        </button>

        <img

            src="${store.image}"

            class="story-image"

            alt="${store.name}"

        >

        <div class="story-content">

            <h1>

                ${store.name}

            </h1>

            <p class="story-location">

                📍 ${store.city}, ${store.country}

            </p>

            <h3>

                Story

            </h3>

            <p>

                ${store.story}

            </p>

            <h3>

                Recommendation

            </h3>

            <p>

                ${store.recommendation}

            </p>

        </div>

    `;

    document.body.appendChild(storyPage);

    requestAnimationFrame(() => {

        storyPage.classList.add("show");

    });

    document

        .getElementById("closeStory")

        .addEventListener(

            "click",

            hideStoryPage

        );

}

// ==========================================
// Hide Story Page
// ==========================================

export function hideStoryPage() {

    if (!storyPage) return;

    storyPage.classList.remove("show");

    setTimeout(() => {

        if (storyPage) {

            storyPage.remove();

            storyPage = null;

        }

    },300);

}