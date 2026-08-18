<!DOCTYPE html>
<html lang="ja">
<head>

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet/dist/leaflet.css">

    <meta charset="UTF-8">
    <title>位置情報編集</title>
</head>

<body>

    <h1>位置情報編集</h1>

    <form method="POST"
        action="{{ route('earth.location.update', $earthLocation) }}">

        @csrf
        @method('PUT')

        <div>
            <label>店舗名</label><br>

            <input
                type="text"
                id="place_name"
                name="place_name"
                value="{{ old('place_name', $earthLocation->place_name) }}">
        </div>

        <br>

        <div>

            <label>店舗名検索</label><br>

            <input
                type="text"
                id="searchInput"
                placeholder="店舗名を入力">

            <button
                type="button"
                id="searchButton">
                🔍検索
            </button>

            <div id="searchResults"></div>

        </div>

        <br>

        <div>

            <label>住所</label><br>

            <input
                type="text"
                id="address"
                name="address"
                value="{{ old('address', $earthLocation->address) }}">

        </div>

        <br>

        <div>

            <label>地図</label>

            <div
                id="map"
                style="height:500px; width:100%; border:1px solid #ccc;">
            </div>

        </div>

        <br>

        <div>

            <label>Latitude</label><br>

            <input
                type="text"
                id="latitude"
                name="latitude"
                value="{{ old('latitude', $earthLocation->latitude) }}"
                readonly>

        </div>

        <br>

        <div>

            <label>Longitude</label><br>

            <input
                type="text"
                id="longitude"
                name="longitude"
                value="{{ old('longitude', $earthLocation->longitude) }}"
                readonly>

        </div>

        <br>

        <button type="submit">
            保存
        </button>

    </form>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    @vite('resources/js/earth/earth-location-edit.js')

</body>
</html>
