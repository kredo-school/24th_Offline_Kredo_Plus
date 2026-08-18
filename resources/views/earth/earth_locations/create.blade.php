<!DOCTYPE html>
<html lang="ja">
<head>

    <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet/dist/leaflet.css">

    <meta charset="UTF-8">
    <title>位置情報入力</title>
</head>
<body>

    <h1>位置情報入力</h1>

    @if ($errors->any())
        <div style="color:#c0392b; border:1px solid #c0392b; padding:10px; margin-bottom:10px;">
            <ul style="margin:0; padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<form method="POST" action="{{ route('earth.location.store') }}">
    @csrf
        <div>
            <label>店舗名</label><br>
            <input type="text" id="place_name" name="place_name" value="{{ old('place_name') }}">
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

<br>

</div>

<br>

        <div>
            <label>住所</label><br>
            <input type="text" id="address" name="address" value="{{ old('address') }}">
        </div>
        <div>

        <label>地図</label>

        <div id="map"
            style="height:500px; width:100%; border:1px solid #ccc;">
        </div>

        </div>

        <br>

        <div>
            <label>Latitude</label><br>
            <input type="text" id="latitude" name="latitude" readonly>
        </div>

        <br>

        <div>
            <label>Longitude</label><br>
            <input type="text" id="longitude" name="longitude" readonly>
        </div>

        <br>

        <button type="submit">
            保存
        </button>

    </form>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

@vite('resources/js/earth/earth-location-create.js')

</body>
</html>
