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

function getPinColor(location) {

    return location.post?.category?.pin_color || "#2f5bfd";

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
        16,
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

locations.forEach((location, index) => {

    setTimeout(() => {

        // =========================
        // Category
        // =========================

        const color =
            getPinColor(location);

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

        // (以下は変更なし)
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

    // ホバーカードは閉じておく
    hideHoverCard();

    // 大きい情報カードを表示
    const post = location.post;

showStoreCard({
    postId: post?.id,
    image: post?.image_url ?? "post?.image_url ?? ",
    name: post?.title ?? location.place_name,
    price: post?.price ?? "",
    category: post?.category?.name ?? "Other",
    pinColor: post?.category?.pin_color ?? "#2f5bfd",
    description: post?.description ?? "",
    userName: post?.user?.name ?? "Unknown",
    userAvatar: post?.user?.avatar_url ?? "",
    createdAt: post?.created_at_human ?? "",
    likesCount: post?.likes_count ?? 0,
    likedByMe: post?.liked_by_me ?? false,
    bookmarkedByMe: post?.bookmarked_by_me ?? false,
});});
        }, index * 120);

    });
}
