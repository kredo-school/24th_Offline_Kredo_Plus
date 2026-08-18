<!DOCTYPE html>
<html lang="ja">

<head>

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet/dist/leaflet.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>位置情報を追加 | Kredo Plus</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #F7F4EF;
            color: #241E1A;
            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        .page {
            min-height: 100vh;
            padding: 50px 20px 80px;
        }

        .container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }

        /* Header */

        .header {
            margin-bottom: 32px;
        }

        .eyebrow {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.16em;
            color: #9A7258;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        h1 {
            margin: 0;
            font-size: 32px;
            line-height: 1.2;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .subtitle {
            margin-top: 10px;
            color: rgba(36, 30, 26, 0.55);
            font-size: 14px;
            line-height: 1.7;
        }

        /* Error */

        .error-box {
            margin-bottom: 20px;
            padding: 14px 16px;
            border-radius: 14px;
            background: #FFF1F0;
            border: 1px solid #F0C5C2;
            color: #C0392B;
            font-size: 13px;
        }

        .error-box ul {
            margin: 0;
            padding-left: 20px;
        }

        /* Card */

        .card {
            background: #FFFFFF;
            border-radius: 24px;
            padding: 32px;
            box-shadow:
                0 10px 30px rgba(36, 30, 26, 0.06);
        }

        .section {
            margin-bottom: 28px;
        }

        .label {
            display: block;
            margin-bottom: 10px;
            font-size: 13px;
            font-weight: 700;
            color: #241E1A;
        }

        /* Search */

        .search-row {
            display: flex;
            gap: 10px;
        }

        .input {
            width: 100%;
            height: 50px;
            border: 1px solid #E5DED6;
            border-radius: 14px;
            padding: 0 16px;
            font-size: 14px;
            color: #241E1A;
            background: #FFFEFC;
            outline: none;
            transition: 0.2s ease;
        }

        .input:focus {
            border-color: #B58B6A;
            box-shadow: 0 0 0 4px rgba(181, 139, 106, 0.12);
        }

        .search-button {
            flex-shrink: 0;
            height: 50px;
            padding: 0 22px;
            border: none;
            border-radius: 14px;
            background: #241E1A;
            color: white;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .search-button:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }

        /* Search Results */

        #searchResults {
            margin-top: 12px;
            overflow: hidden;
            border-radius: 14px;
        }

        #searchResults > div {
            background: #FAF8F5;
            border-bottom: 1px solid #E8E1D9 !important;
            padding: 14px !important;
            font-size: 13px;
            line-height: 1.5;
            transition: 0.2s ease;
        }

        #searchResults > div:hover {
            background: #F1ECE6;
        }

        /* Map */

        .map-wrapper {
            overflow: hidden;
            border-radius: 20px;
            border: 1px solid #E5DED6;
            box-shadow:
                inset 0 0 0 1px rgba(36, 30, 26, 0.02);
        }

        #map {
            height: 450px !important;
            width: 100%;
        }

        /* Save button */

        .save-button {
            width: 100%;
            height: 56px;
            border: none;
            border-radius: 16px;
            background: #B58B6A;
            color: white;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .save-button:hover {
            transform: translateY(-1px);
            box-shadow:
                0 8px 20px rgba(181, 139, 106, 0.25);
        }

        /* Responsive */

        @media (max-width: 640px) {

            .page {
                padding: 30px 14px 50px;
            }

            .card {
                padding: 22px;
                border-radius: 20px;
            }

            h1 {
                font-size: 26px;
            }

            .search-row {
                flex-direction: column;
            }

            .search-button {
                width: 100%;
            }

            #map {
                height: 350px !important;
            }
        }
    </style>

</head>


<body>

<div class="page">

    <div class="container">

        {{-- Header --}}
        <div class="header">

            <div class="eyebrow">
                Kredo Plus
            </div>

            <h1>
                位置情報を追加
            </h1>

            <p class="subtitle">
                店舗を検索して、地図上の位置を設定してください。
            </p>

        </div>


        {{-- Validation Errors --}}
        @if ($errors->any())

            <div class="error-box">

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- Main Card --}}
        <div class="card">

            <form
                method="POST"
                action="{{ route('earth.location.store') }}">

                @csrf


                {{-- 店舗名 --}}
                {{-- 画面には表示せず、検索結果から自動設定 --}}
                <input
                    type="hidden"
                    id="place_name"
                    name="place_name"
                    value="{{ old('place_name') }}">


                {{-- Search --}}
                <div class="section">

                    <label class="label">
                        店舗を検索
                    </label>

                    <div class="search-row">

                        <input
                            type="text"
                            id="searchInput"
                            class="input"
                            placeholder="店舗名を入力してください">

                        <button
                            type="button"
                            id="searchButton"
                            class="search-button">

                            🔍 検索

                        </button>

                    </div>

                    <div id="searchResults"></div>

                </div>


                {{-- Address --}}
                <div class="section">

                    <label class="label">
                        住所
                    </label>

                    <input
                        type="text"
                        id="address"
                        name="address"
                        class="input"
                        value="{{ old('address') }}"
                        placeholder="住所">

                </div>


                {{-- Map --}}
                <div class="section">

                    <label class="label">
                        地図上の位置
                    </label>

                    <div class="map-wrapper">

                        <div id="map"></div>

                    </div>

                </div>


                {{-- Hidden Coordinates --}}

                <input
                    type="hidden"
                    id="latitude"
                    name="latitude"
                    value="{{ old('latitude') }}">

                <input
                    type="hidden"
                    id="longitude"
                    name="longitude"
                    value="{{ old('longitude') }}">


                {{-- Save --}}

                <button
                    type="submit"
                    class="save-button">

                    位置情報を保存

                </button>

            </form>

        </div>

    </div>

</div>


<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

@vite('resources/js/earth/earth-location-create.js')

</body>

</html>
