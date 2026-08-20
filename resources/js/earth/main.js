import "../../css/earth/style.css";
import Globe from "globe.gl";
import { gsap } from "gsap";

import {
    initializeFlatMap,
    showFlatMap,
    hideFlatMap,
    setLocations
} from "./flat-map";

import { showStoreCard } from "./store-card";
import { stores } from "./stores";
import { setSelectedStore } from "./selected-store";

import { createPin } from "./pin-manager";

// ==========================================
// Flat Map
// ==========================================

setLocations(locations);
initializeFlatMap();

// ==========================================
// Globe
// ==========================================

const globe = Globe();

globe(document.getElementById("globeViz"))
    .globeImageUrl(
        "//unpkg.com/three-globe/example/img/earth-blue-marble.jpg"
    )
    .backgroundImageUrl(
        "//unpkg.com/three-globe/example/img/night-sky.png"
    )
    .showAtmosphere(true)
    .atmosphereColor("#7EC8FF")
    .atmosphereAltitude(0.28)
    .htmlElement(d => d.element);

// ==========================================
// Camera
// ==========================================

globe.pointOfView(

    {

        lat:20,

        lng:120,

        altitude:2.8

    },

    0

);

// ==========================================
// Controls
// ==========================================

const controls = globe.controls();

controls.autoRotate = true;
controls.autoRotateSpeed = 0.22;

// ==========================================
// Audio
// ==========================================

const audio = new Audio("/videos/world.mp4");

// ==========================================
// Destination
// ==========================================

const destination = {

    lat:10.3157,

    lng:123.8854,

    altitude:0.65

};

// ==========================================
// Search Destinations
// ==========================================

const destinations = {

    cebu:{

        lat:10.3157,

        lng:123.8854,

        altitude:0.65

    }

};


// ==========================================
// UI
// ==========================================

function showTravelMessage(){

    document
        .getElementById("travelMessage")
        .classList.add("show");

}

function hideTravelMessage(){

    document
        .getElementById("travelMessage")
        .classList.remove("show");

}

function showSearchBox(){

    const box=document.getElementById("searchBox");

    if(box){

        box.classList.add("show");

    }

}

// ==========================================
// Fly
// ==========================================

function flyToDestination(name){

    const place=

        destinations[name.toLowerCase()];

    if(!place){

        alert("Destination not found.");

        return;

    }

    globe.pointOfView(

        place,

        3500

    );

}

// ==========================================
// Journey
// ==========================================

function startJourney() {

    audio.play().catch(() => {});

    showTravelMessage();

    controls.autoRotate = false;

    // セブへ移動
    globe.pointOfView(
        destination,
        2000
    );

    // 少し早めにFlat Mapへ切り替え
    setTimeout(() => {

        hideTravelMessage();

        // Flat Mapを先に表示
        showFlatMap();

        // 地球をフェードアウト
        globeDiv.style.opacity = "0";

        // 地球が消えるのと同時にFlat Mapを表示

        setTimeout(() => {

            globeDiv.style.display = "none";

            isFlatMap = true;

        }, 600);

    }, 2000);
}
// ==========================================
// Auto Start
// ==========================================

window.addEventListener("load", () => {

    setTimeout(() => {

        startJourney();

    }, 500);

});

// ==========================================
// Globe ⇔ Flat Map
// ==========================================

let isFlatMap = false;

const globeDiv = document.getElementById("globeViz");

const mapButton = document.getElementById("mapToggle");

mapButton.addEventListener("click", () => {

    if (!isFlatMap) {

        showFlatMap();

        requestAnimationFrame(() => {
            globeDiv.style.opacity = "0";
        });        mapButton.style.display = "none";

        isFlatMap = true;

    }

});

// ==========================================
// Globe Button
// ==========================================

window.addEventListener("showGlobe", () => {

    hideFlatMap();

    globeDiv.style.display = "block";

    requestAnimationFrame(() => {

        globeDiv.style.opacity = "1";

    });

    mapButton.style.display = "block";

    isFlatMap = false;

});


// ==========================================
// Export
// ==========================================

export {

    globe,

    controls

};
