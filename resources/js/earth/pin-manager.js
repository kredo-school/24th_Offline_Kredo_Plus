import { showStoreCard } from "./store-card";
import { setSelectedStore } from "./selected-store";
import { showHoverCard, hideHoverCard } from "./hover-card";

// ==========================================
// Create Pin
// ==========================================

export function createPin(store) {

    const pin = document.createElement("img");

    pin.src = "/images/pin.png";

    pin.className = "earth-pin";

    pin.alt = store.name;

    pin.title = store.name;

    pin.addEventListener("mouseenter", (event) => {

        pin.classList.add("hover");

        showHoverCard(
            store,
            event.pageX,
            event.pageY
        );

    });

    pin.addEventListener("mouseleave", () => {

        pin.classList.remove("hover");

        hideHoverCard();

    });

    pin.addEventListener("click", () => {

     alert(store.name);

    });

    pin.animate(

        [
            {
                transform: "translate(-50%,-180%) scale(0)"
            },
            {
                transform: "translate(-50%,-100%) scale(1.2)"
            },
            {
                transform: "translate(-50%,-100%) scale(1)"
            }
        ],

        {
            duration: 700,
            easing: "ease-out"
        }

    );

    return pin;

}