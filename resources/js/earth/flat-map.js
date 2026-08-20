import { showStoreCard } from "./store-card";
import { setSelectedStore } from "./selected-store";
import { showHoverCard, hideHoverCard } from "./hover-card";
import L from "leaflet";
import "leaflet/dist/leaflet.css";
import { stores } from "./stores";


let map;
const markers = [];

let locations = [];

export function setLocations(data) {
    locations = data;
}


let activeMarker = null;

// ==============================
// Cebu Center
// ==============================

const destination = {

    lat: 10.3297,

    lng: 123.9064

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
// School / IT Park Pin
// ==============================

const schoolIcon = L.divIcon({

    className: "our-school-pin",

    html: `
        <div class="school-pin-wrapper">

            <div class="school-pulse"></div>

            <div class="school-circle">
                <img
                    src="/images/earth/our-school.png"
                    alt="Our School"
                >
            </div>

            <div class="school-label">
                Kredo
            </div>

        </div>
    `,

    iconSize: [60, 70],
    iconAnchor: [30, 35],
    popupAnchor: [0, -35]

});



// ==============================
// Pin Color
// ==============================

function getPinColor(section) {

    switch (section) {

        case "carinderia":
            return "#2f5bfd";

        case "restaurant-cafe":
            return "#e05237";

        case "travel":
            return "#f5b52e";

        case "other":
            return "#5eab35";

        default:
            return "#64748b";
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

    // ==============================
    // School Pin (戻るボタン)
    // ==============================

    const schoolMarker = L.marker(

        [
            destination.lat,
            destination.lng
        ],

        {
            icon: schoolIcon,
            zIndexOffset: 1000
        }

    ).addTo(map);

    schoolMarker.on("click", () => {

        history.back();

    });


    }
// ==============================
// Show
// ==============================

export function showFlatMap() {

    const container = document.getElementById("flat-map");

    container.style.display = "block";

    // 最初は透明
    container.style.opacity = "0";

    map.invalidateSize();

    // Flat Mapをフェードイン
    requestAnimationFrame(() => {

        container.style.opacity = "1";

    });

    // Cebuへスムーズに移動
    map.flyTo(
        [
            destination.lat,
            destination.lng
        ],
        17,
        {
            duration: 1.2
        }
    );

    // ピンを早めに表示
    setTimeout(() => {

        addPins();

    }, 700);

}// ==============================
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

    locations.forEach((location, index) => {

        setTimeout(() => {

            // =========================
            // Category
            // =========================

            const section =
                location.post?.category?.section;

            console.log(
                "📍場所:",
                location.place_name,
                "カテゴリー:",
                section,
                "category:",
                location.post?.category
            );

            const color =
                getPinColor(section);
            // =========================
            // Pin Icon
            // =========================

            const locationPinIcon = L.divIcon({

                className: "drop-pin",

                html: `
                    <div class="pin-wrapper">

                        <div
                            class="pin"
                            style="background:${color};"
                        >

                            <div class="pin-center"></div>

                        </div>

                        <div class="pin-shadow"></div>

                    </div>
                `,

                iconSize: [40, 50],

                iconAnchor: [20, 50]

            });

            // =========================
            // Marker
            // =========================

            const marker = L.marker(

                [
                    Number(location.latitude),
                    Number(location.longitude)
                ],

                {
                    icon: locationPinIcon
                }

            ).addTo(map);

            markers.push(marker);

            // =========================
            // Drop Animation
            // =========================

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

                if (el) {
                    el.classList.add("hover-pin");
                }

                showHoverCard(
                    location,
                    event.originalEvent.pageX,
                    event.originalEvent.pageY
                );

            });

            marker.on("mouseout", () => {

                const el = marker.getElement();

                if (el) {
                    el.classList.remove("hover-pin");
                }

                hideHoverCard();

});

            // =========================
            // Click
            // =========================





            marker.on("click", () => {

                if (activeMarker) {

                    const oldEl =
                        activeMarker.getElement();

                    if (oldEl) {

                        oldEl.classList.remove(
                            "active-pin"
                        );

                        oldEl.classList.remove(
                            "hover-pin"
                        );

                    }

                }

                activeMarker = marker;

                const el =
                    marker.getElement();

                if (el) {

                    el.classList.remove(
                        "hover-pin"
                    );

                    el.classList.add(
                        "active-pin"
                    );

                }

                console.log(
                    "選択した場所:",
                    location
                );

            });

        }, index * 120);

    });
}
