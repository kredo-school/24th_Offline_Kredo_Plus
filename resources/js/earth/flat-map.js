import { showStoreCard } from "./store-card";
import { setSelectedStore } from "./selected-store";
import { showHoverCard, hideHoverCard } from "./hover-card";
import L from "leaflet";
import "leaflet/dist/leaflet.css";
import { stores } from "./stores";


let map;
const markers = [];


let activeMarker = null;

// ==============================
// Cebu Center
// ==============================

const destination = {

    lat: 10.3157,

    lng: 123.8854

};


// ==============================
// Google Pin
// ==============================

const pinIcon = L.divIcon({

    className: "drop-pin",

    html: `
        <div class="pin-wrapper">
            <div class="pin">
                <div class="pin-center"></div>
            </div>
            <div class="pin-shadow"></div>
        </div>
    `,

    iconSize: [40, 50],

    iconAnchor: [20, 50]

});

// ==============================
// Pin Color
// ==============================

function getPinColor(type){

    switch(type){

        case "Carinderia":
            return "#4285F4";

        case "Restaurant & Cafe":
            return "#EA4335";

        case "Travel":
            return "#FBBC05";

        default:
            return "#34A853";

    }

}

// ==============================
// Initialize
// ==============================

export function initializeFlatMap() {

    map = L.map("flat-map", {

        zoomControl: true,

        attributionControl: false

    });

    L.tileLayer(

        "https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png",

        {

            subdomains: "abcd",

            maxZoom: 20

        }

    ).addTo(map);

    map.setView(

        [

            destination.lat,

            destination.lng

        ],

        13

    );

}

// ==============================
// Show
// ==============================

export function showFlatMap() {

    const container = document.getElementById("flat-map");

    container.style.display = "block";

    setTimeout(() => {

        map.invalidateSize();

        map.flyTo(

            [

                destination.lat,

                destination.lng

            ],

            17,

            {

                duration: 2

            }

        );

    }, 200);

    setTimeout(() => {

        addPins();

    }, 2200);

}

// ==============================
// Hide
// ==============================

export function hideFlatMap() {

    document.getElementById("flat-map").style.display = "none";

}

// ==============================
// Add Pins
// ==============================

function addPins() {

    // 古いピンを削除
    markers.forEach(marker => map.removeLayer(marker));

    markers.length = 0;

    activeMarker = null;

    stores.forEach((store, index) => {

        setTimeout(() => {

            const marker = L.marker(

                [

                    store.lat,

                    store.lng

                ],

                {

                    icon: pinIcon

                }

            ).addTo(map);

            markers.push(marker);

            // 落下アニメーション
            setTimeout(() => {

                const el = marker.getElement();

                if (el) {

                    el.classList.add("drop");

                }

            }, 30);

            // =========================
            // Hover
            // =========================

    marker.on("mouseover", (event) => {

        const el = marker.getElement();

        if (!el) return;

        if (marker !== activeMarker) {

            el.classList.add("hover-pin");

        }

        const position = map.latLngToContainerPoint(marker.getLatLng());

        const rect = map.getContainer().getBoundingClientRect();

        showHoverCard(

        store,

        event.originalEvent.pageX,
        event.originalEvent.pageY


    );


    });


            marker.on("mouseout", () => {

                const el = marker.getElement();

                if (!el) return;

                el.classList.remove("hover-pin");

                hideHoverCard();

            });

            // =========================
            // Click
            // =========================

            marker.on("click", () => {

                // 前に選択されていたピンを戻す
                if (activeMarker) {

                    const oldEl = activeMarker.getElement();

                    if (oldEl) {

                        oldEl.classList.remove("active-pin");

                        oldEl.classList.remove("hover-pin");

                    }

                }

                activeMarker = marker;

                const el = marker.getElement();

                if (el) {

                    el.classList.remove("hover-pin");

                    el.classList.add("active-pin");

                }

            setSelectedStore(store);
            showStoreCard(store);

            });




        }, index * 350);

    });

}