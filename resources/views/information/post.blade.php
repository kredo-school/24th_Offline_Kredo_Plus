@extends('layouts.app')

@section('title', 'Information 投稿 — Kredo Plus')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/information.css') }}">
@endpush

@section('content')

    <div class="rc-edit-wrapper">

        <!-- Header -->
        <div class="rc-bar">
            <div class="rc-bar-inner">
                <div class="rc-bar-left">
                    <a href="{{ route('restaurant-cafe.index') }}" class="rc-back-link">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>

                    <span class="rc-page-title">
                        Information 投稿
                    </span>
                </div>
            </div>
        </div>

        <main class="rc-main">

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

            <form action="{{ route('information.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <!-- Image -->
                <div>

                    <label for="imageInput" class="rc-image-box">

                        <img id="imagePreview"
                            src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=800&auto=format&fit=crop"
                            alt="preview">

                        <div class="rc-image-overlay">
                            <span class="rc-image-overlay-inner">
                                <i class="fa-solid fa-camera"></i>
                                <span>写真を追加</span>
                            </span>
                        </div>

                    </label>

                    <input type="file" id="imageInput" name="image" accept="image/*" class="rc-hidden-file">

                </div>

                <!-- Category -->
                <div>

                    <label class="rc-field-label">
                        CATEGORY
                    </label>

                    <select name="category_id" id="categorySelect" class="rc-field-input">

                        <option value="">
                            選択してください
                        </option>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" data-name="{{ strtolower($category->name) }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>

                                {{ $category->name }}

                            </option>
                        @endforeach

                    </select>

                </div>

                <!-- Title -->
                <div>

                    <label class="rc-field-label">

                        TITLE

                    </label>

                    <input type="text" name="title" class="rc-field-input" value="{{ old('title') }}"
                        placeholder="店名・タイトル">

                </div>

                <!-- Description -->
                <div>

                    <label class="rc-field-label">

                        DESCRIPTION

                    </label>

                    <textarea name="description" rows="4" class="rc-field-input" placeholder="説明を入力してください">{{ old('description') }}</textarea>

                </div>
                <!-- Price -->
                <div>
                    <label class="rc-field-label">
                        PRICE (PHP)
                    </label>

                    <input type="number" name="price" min="0" step="1" class="rc-field-input"
                        value="{{ old('price') }}" placeholder="例：450">
                </div>

                <!-- Location -->
                <div>

                    <label class="rc-field-label">
                        LOCATION
                    </label>

                    @if($earthLocation)

                    <input
                    type="hidden"
                    name="earth_location_id"
                    value="{{ $earthLocation->id }}">

                    <div style="margin-top:10px;padding:10px;border:1px solid #ddd;border-radius:8px;">

                    <strong>📍 {{ $earthLocation->place_name }}</strong><br>

                    <small>{{ $earthLocation->address }}</small>

                    </div>

                    @endif

                    <!-- Carinderiaのみ表示 -->
                    <div id="carinderiaLocation" style="display:none;">
                        <button type="button" class="rc-save-btn"
                            style="background:#f8fafc;color:#4736F0;border:1px solid #4736F0;">

                            <i class="fa-solid fa-store"></i>
                            <span>店舗を選択</span>

                        </button>
                    </div>

                    <!-- Restaurant / Travel / Other -->
                    <div id="mapLocation">

                        <a href="{{ route('earth.location.create') }}"  class="rc-save-btn"
                            style="background:#f8fafc;color:#4736F0;border:1px solid #4736F0;">

                            <i class="fa-solid fa-location-dot"></i>
                            <span>場所を追加</span>
                        </a>

                    </div>

                </div>

                <!-- Action -->
                <div class="flex gap-3">

                    <button type="submit" class="rc-save-btn" style="background:#4736F0;color:#fff;">

                        <span>SAVE</span>

                    </button>

                </div>

            </form>

        </main>

        <!-- Footer -->

        <nav class="rc-footer-nav">

            <a href="{{ route('restaurant-cafe.index') }}" class="rc-nav-item">

                <i class="fa-solid fa-house" style="font-size:20px;"></i>

                <span>Home</span>

            </a>

            <a href="{{ route('information.create') }}" class="rc-nav-item active">

                <div class="rc-nav-post-icon">

                    <i class="fa-solid fa-plus"></i>

                </div>

                <span>Post</span>

            </a>

            <a href="#" class="rc-nav-item">

                <i class="fa-solid fa-user" style="font-size:20px;"></i>

                <span>Profile</span>

            </a>

        </nav>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/information.js') }}"></script>
@endpush
