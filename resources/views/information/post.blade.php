@extends('layouts.app')

@section('title', 'Restaurant & Cafe 投稿 — Kredo Plus')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/information.css') }}">
@endpush

@section('content')

    <div class="rc-edit-wrapper">

        <!-- Header -->
        <div class="rc-bar">
            <div class="rc-bar-inner">
                <div class="rc-bar-left">
                    <a href="{{ route('restaurant-cafe.index') }}" class="rc-back-link" aria-label="戻る">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <span class="rc-page-title">Restaurant & Cafe 投稿</span>
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

            <form action="{{ route('restaurant-cafe.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Image -->
                <div>
                    <label for="imageInput" class="rc-image-box">
                        <img id="imagePreview"
                            src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=800&auto=format&fit=crop"
                            alt="写真プレビュー">

                        <div class="rc-image-overlay">
                            <span class="rc-image-overlay-inner">
                                <i class="fa-solid fa-camera"></i>
                                <span>写真を選択</span>
                            </span>
                        </div>
                    </label>

                    <input type="file" name="image" id="imageInput" accept="image/*" class="rc-hidden-file">
                </div>


            @endsection

            @push('scripts')
                <script src="{{ asset('js/information.js') }}"></script>
            @endpush
