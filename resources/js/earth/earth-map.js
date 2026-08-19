// 地図を作成
const map = L.map('map').setView([10.3157, 123.8854], 13);

// 地図タイル
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// 現在のピン
let marker;


// ==========================================
// 地図をクリックしたとき
// ==========================================

map.on('click', async function (e) {

    const lat = e.latlng.lat;
    const lng = e.latlng.lng;

    // 緯度・経度を保存
    document.getElementById("latitude").value = lat.toFixed(7);
    document.getElementById("longitude").value = lng.toFixed(7);

    // 既存のピンを削除
    if (marker) {
        map.removeLayer(marker);
    }

    // 新しいピンを表示
    marker = L.marker([lat, lng]).addTo(map);


    // ==========================================
    // クリックした場所の住所を取得
    // ==========================================

    try {

        const response = await fetch(
            `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&addressdetails=1`,
            {
                headers: {
                    'Accept-Language': 'en'
                }
            }
        );

        const data = await response.json();

        const addressInput = document.getElementById("address");

        if (data && data.display_name) {

            addressInput.value = data.display_name;

        } else {

            // 住所が取得できなくても空欄のまま保存可能
            addressInput.value = '';

        }

    } catch (error) {

        console.error('住所の取得に失敗しました:', error);

        // 住所取得に失敗しても保存はできる
        document.getElementById("address").value = '';

    }

});


// ==========================================
// 検索結果から呼び出す関数
// ==========================================

window.moveMap = function (lat, lng) {

    map.setView([lat, lng], 17);

    if (marker) {
        map.removeLayer(marker);
    }

    marker = L.marker([lat, lng]).addTo(map);

    document.getElementById("latitude").value = lat.toFixed(7);
    document.getElementById("longitude").value = lng.toFixed(7);

};
