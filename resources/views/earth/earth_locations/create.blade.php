@extends('layouts.app')

@section('title', '位置情報を追加 — Kredo Plus')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/information.css') }}">
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet/dist/leaflet.css">
@endpush

@section('content')

<div class="rc-edit-wrapper">

    {{-- Header --}}
    <div class="rc-bar">

        <div class="rc-bar-inner">

            <div class="rc-bar-left">

                <a
                    href="javascript:void(0)"
                    onclick="history.back()"
                    class="rc-back-link">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <span class="rc-page-title">
                    位置情報を追加
                </span>

            </div>

        </div>

    </div>


    <main class="rc-main">

        {{-- Error --}}
        @if ($errors->any())

            <div class="rc-error-box">

                <p>入力内容をご確認ください</p>

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('earth.location.store') }}">

            @csrf


            {{-- 場所 --}}
            <input
                type="hidden"
                name="post_id"
                value="{{ request('post_id', old('post_id')) }}">

            {{-- 画面には表示しない --}}
            <input
                type="hidden"
                id="place_name"
                name="place_name"
                value="{{ old('place_name') }}">


            {{-- Search --}}
            <div>

                <label class="rc-field-label">
                    SEARCH LOCATION
                </label>

                <div style="
                    display:flex;
                    gap:10px;
                    align-items:stretch;
                ">

                    <input
                        type="text"
                        id="searchInput"
                        class="rc-field-input"
                        placeholder="位置情報を入力してください">

                    <button
                        type="button"
                        id="searchButton"
                        class="rc-save-btn"
                        style="
                            width:auto;
                            padding:0 20px;
                            background:#4736F0;
                            color:#fff;
                            white-space:nowrap;
                        ">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        <span>検索</span>

                    </button>

                </div>


                {{-- Search Results --}}
                <div
                    id="searchResults"
                    style="
                        margin-top:10px;
                    ">
                </div>

            </div>


            {{-- Address --}}
            <div>

                <label class="rc-field-label">
                    ADDRESS
                </label>

                <input
                    type="text"
                    id="address"
                    name="address"
                    class="rc-field-input"
                    value="{{ old('address') }}"
                    placeholder="住所">

            </div>


            {{-- Map --}}
            <div>

                <label class="rc-field-label">
                    LOCATION
                </label>

                <div
                    style="
                        overflow:hidden;
                        border-radius:14px;
                        border:1px solid #e5e7eb;
                        margin-top:8px;
                    ">

                    <div
                        id="map"
                        style="
                            width:100%;
                            height:450px;
                        ">
                    </div>

                </div>

                <p style="
                    margin:8px 0 0;
                    font-size:12px;
                    color:#9ca3af;
                ">
                    検索結果を選択すると、地図がその場所へ移動します。
                </p>

            </div>


            {{-- Hidden coordinates --}}

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

            <div style="margin-top:30px;">

                <button
                    type="submit"
                    class="rc-save-btn"
                    style="
                        width:100%;
                        background:#4736F0;
                        color:#fff;
                    ">

                    <i class="fa-solid fa-location-dot"></i>

                    <span>
                        位置情報を保存
                    </span>

                </button>

            </div>

        </form>

    </main>


    {{-- Footer --}}

    <nav class="rc-footer-nav">

        <a
            href="{{ route('restaurant-cafe.index') }}"
            class="rc-nav-item">

            <i
                class="fa-solid fa-house"
                style="font-size:20px;">
            </i>

            <span>
                Home
            </span>

        </a>


        <a
            href="{{ route('information.create') }}"
            class="rc-nav-item active">

            <div class="rc-nav-post-icon">

                <i class="fa-solid fa-plus"></i>

            </div>

            <span>
                Post
            </span>

        </a>


        <a
            href="{{ route('earth') }}"
            class="rc-nav-item">

            <i
                class="fa-solid fa-globe"
                style="font-size:20px;">
            </i>

            <span>
                Map
            </span>

        </a>

    </nav>

</div>

@endsection


@push('scripts')

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

@vite('resources/js/earth/earth-location-create.js')

@endpush
