<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>World Journey</title>

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet/dist/leaflet.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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


@vite([
    'resources/css/earth/style.css',
    'resources/js/earth/main.js'
])


<script>
    const locations = @json($locations);
</script>

</body>

</html>
