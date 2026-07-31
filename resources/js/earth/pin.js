import { showStoreCard } from "./store-card";
import { setSelectedStore } from "./selected-store";
import { showHoverCard,hideHoverCard } from "./hover-card";


export function createPin(store){

    const pin=document.createElement("img");

    pin.src="/images/pin.png";

    pin.className = "earth-pin";

    pin.alt = store.name;

    pin.title = store.name;

    pin.style.width="48px";

    pin.style.height="48px";

    pin.style.transform="translate(-50%,-100%)";

    pin.style.pointerEvents="auto";

    pin.style.cursor="pointer";

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

    pin.addEventListener("click",()=>{

    setSelectedStore(store);

    showStoreCard(store);

    });


    pin.animate(

        [

            {

                transform:
                "translate(-50%,-180%) scale(0)"

            },

            {

                transform:
                "translate(-50%,-100%) scale(1.2)"

            },

            {

                transform:
                "translate(-50%,-100%) scale(1)"

            }

        ],

        {

            duration:700,

            easing:"ease-out"

        }

    );

    return pin;

}
