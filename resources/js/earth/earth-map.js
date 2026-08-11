// 地図を作成
const map = L.map('map').setView([10.3157, 123.8854], 13);

// 地図タイル
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// 現在のピン
let marker;

// 地図をクリックしたとき
map.on('click', function (e) {

    const lat = e.latlng.lat;
    const lng = e.latlng.lng;

    document.getElementById("latitude").value = lat.toFixed(7);
    document.getElementById("longitude").value = lng.toFixed(7);

    if (marker) {
        map.removeLayer(marker);
    }

    marker = L.marker([lat, lng]).addTo(map);

});

// 検索結果から呼び出す関数
window.moveMap = function (lat, lng) {

    map.setView([lat, lng], 17);

    if (marker) {
        map.removeLayer(marker);
    }

    marker = L.marker([lat, lng]).addTo(map);

    document.getElementById("latitude").value = lat.toFixed(7);
    document.getElementById("longitude").value = lng.toFixed(7);

};
