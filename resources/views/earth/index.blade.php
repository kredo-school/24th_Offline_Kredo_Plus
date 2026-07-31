<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>World Journey</title>

@vite([
    'resources/css/earth.css',
    'resources/js/earth/main.js'
])



</head>

<body>

    <!-- Globe -->
    <div id="globeViz"></div>

    <!-- Traveling -->
    <div id="travelMessage">
        ✈️ Traveling to Cebu...
    </div>

    <!-- Flat Map Button -->
    <button id="mapToggle">
        🗺 Flat Map
    </button>

    <!-- Earthへ戻る -->
    <button id="earthToggle">
        🌍 Earth
    </button>

    <!-- Leaflet -->
    <div id="flat-map"></div>

    <!-- Search -->
    <div id="searchBox">

        <input
            id="searchInput"
            type="text"
            placeholder="Search destination...">

        <button id="searchButton">
            🔍
        </button>

    </div>

    <!-- Store Card -->
    <div id="storeCard"></div>

</body>

</html>
