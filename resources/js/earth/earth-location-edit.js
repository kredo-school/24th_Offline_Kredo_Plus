import './earth-map.js';
import './earth-search.js';

document.addEventListener('DOMContentLoaded', () => {

    const latitude = document.getElementById('latitude');
    const longitude = document.getElementById('longitude');

    if (!latitude || !longitude) {
        return;
    }

    const lat = Number(latitude.value);
    const lng = Number(longitude.value);

    if (!Number.isNaN(lat) && !Number.isNaN(lng)) {
        moveMap(lat, lng);
    }

});
