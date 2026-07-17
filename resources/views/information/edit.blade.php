@extends('layouts.app')

@section('title', 'Restaurant & Cafe 編集 — Kredo Plus')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/information.css') }}">
@endpush

@section('content')

    <div class="rc-edit-wrapper">

        <div class="rc-bar">
            <div class="rc-bar-inner">
                <div class="rc-bar-left">
                    <a href="{{ route('restaurant-cafe.index') }}" class="rc-back-link" aria-label="戻る">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <span class="rc-page-title">Restaurant & Cafe 編集</span>
                </div>

                <form id="deleteForm" action="{{ route('restaurant-cafe.destroy', $post) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" id="deleteBtn" class="rc-delete-btn" aria-label="削除">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </form>
            </div>
        </div>

        <main class="rc-main">

            @if (session('status'))
                <div class="rc-status-box">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

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

            <form action="{{ route('restaurant-cafe.update', $post) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Image -->
                <div>
                    <label for="imageInput" class="rc-image-box">
                        <img id="imagePreview"
                            src="{{ $post->image ? asset('storage/' . $post->image) : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=800&auto=format&fit=crop' }}"
                            alt="写真プレビュー">
                        <div class="rc-image-overlay">
                            <span class="rc-image-overlay-inner">
                                <i class="fa-solid fa-camera"></i>
                                <span>写真を変更</span>
                            </span>
                        </div>
                    </label>
                    <input type="file" name="image" id="imageInput" accept="image/*" class="rc-hidden-file">
                </div>

                <!-- Category -->
                <div>
                    <label class="rc-field-label">CATEGORY</label>
                    <div class="rc-cat-chips">
                        @foreach ($categories as $category)
                            <input type="radio" name="category_id" id="cat-{{ $category->id }}"
                                value="{{ $category->id }}" class="rc-cat-radio"
                                style="--chip-color:{{ $category->color() }}"
                                {{ (int) old('category_id', $post->category_id) === $category->id ? 'checked' : '' }}>
                            <label for="cat-{{ $category->id }}" class="rc-cat-chip"
                                style="--chip-color:{{ $category->color() }}">
                                {{ $category->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Title -->
                <div>
                    <label class="rc-field-label">TITLE</label>
                    <input type="text" name="title" class="rc-field-input" value="{{ old('title', $post->title) }}"
                        placeholder="例: Sunset Grill House">
                </div>

                <!-- Description -->
                <div>
                    <label class="rc-field-label">DESCRIPTION</label>
                    <textarea name="description" rows="4" class="rc-field-input" placeholder="お店・サービスの特徴を書いてみましょう">{{ old('description', $post->description) }}</textarea>
                </div>

                <!-- Price -->
                <div>
                    <label class="rc-field-label">PRICE (PHP)</label>
                    <input type="number" name="price" min="0" step="1" class="rc-field-input"
                        value="{{ old('price', $post->price) }}" placeholder="例: 450">
                </div>

                <!-- Actions -->
                <div>
                    <button type="submit" class="rc-save-btn" style="background:#4736F0;color:#fff;">
                        <i class="fa-solid fa-check"></i>
                        <span>SAVE</span>
                    </button>
                </div>

            </form>
        </main>

        <nav class="rc-footer-nav">
            <a href="{{ route('restaurant-cafe.index') }}" class="rc-nav-item active">
                <i class="fa-solid fa-house" style="font-size:20px;"></i>
                <span>Home</span>
            </a>

            <a href="#" class="rc-nav-item">
                <div class="rc-nav-post-icon"><i class="fa-solid fa-plus"></i></div>
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
