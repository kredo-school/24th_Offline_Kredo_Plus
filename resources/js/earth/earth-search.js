console.log("Earth Search Loaded");

const searchButton = document.getElementById("searchButton");
const searchInput = document.getElementById("searchInput");
const searchResults = document.getElementById("searchResults");

searchButton.addEventListener("click", async () => {

    const keyword = searchInput.value;

    if (!keyword) {
        alert("店舗名を入力してください");
        return;
    }

    const url =
        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(keyword)}`;

    const response = await fetch(url);

    const data = await response.json();

searchResults.innerHTML = "";

if (data.length === 0) {

    searchResults.innerHTML = "<p>検索結果がありません。</p>";

    return;

}

data.forEach(place => {

    const item = document.createElement("div");

    item.textContent = place.display_name;

    item.style.cursor = "pointer";
    item.style.padding = "8px";
    item.style.borderBottom = "1px solid #ddd";

    item.addEventListener("click", () => {

    const lat = Number(place.lat);
    const lng = Number(place.lon);

    moveMap(lat, lng);

    document.getElementById("place_name").value = searchInput.value;
    document.getElementById("address").value = place.display_name;

    searchResults.innerHTML = "";

});


    searchResults.appendChild(item);

});
});
