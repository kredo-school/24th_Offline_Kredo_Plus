@extends('layouts.app')

@section('title', 'Information 編集 — Kredo Plus')
@php
    // セクションごとの表示名・戻り先ラベル。新しいセクションを追加する時はここに1行足すだけでOK。
    $sectionLabels = [
        'restaurant-cafe' => 'Restaurant & Cafe',
        'carinderia'      => 'Carinderia',
        'travel'          => 'Travel',
        'other'           => 'Other',
    ];
    $sectionLabel = $sectionLabels[$section] ?? ucfirst($section);
@endphp

@push('styles') <link rel="stylesheet" href="{{ asset('css/information.css') }}">
@endpush

@section('content')

<div class="rc-edit-wrapper">

    {{-- ============================================================
         Information 編集画面
         URL: /information/{post}/edit
         編集・更新・削除は InformationController で一括管理
         ============================================================ --}}

    <div class="rc-bar">
        <div class="rc-bar-inner">

            <div class="rc-bar-left">

                {{-- 投稿のカテゴリーに応じた一覧ページへ戻る --}}
                @php
                    $section = $post->category->section ?? 'restaurant-cafe';

                    $sectionLabels = [
                        'restaurant-cafe' => 'Restaurant & Cafe',
                        'carinderia'      => 'Carinderia',
                        'travel'          => 'Travel',
                        'other'           => 'Other',
                    ];

                    $sectionLabel = $sectionLabels[$section] ?? ucfirst($section);

                    $sectionRoutes = [
                        'restaurant-cafe' => 'restaurant-cafe.index',
                        'carinderia'      => 'carinderia.index',
                        'travel'          => 'travel.index',
                        'other'           => 'other.index',
                    ];

                    $backRoute = $sectionRoutes[$section] ?? 'restaurant-cafe.index';
                @endphp

                <a href="{{ route($backRoute) }}" class="rc-back-link" aria-label="戻る">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>

                <span class="rc-page-title">
                    {{ $sectionLabel }} 編集
                </span>

            </div>

            {{-- 削除 --}}
            <form id="deleteForm"
                action="{{ route('information.destroy', $post) }}"
                method="POST">

                @csrf
                @method('DELETE')

                <button type="button"
                    id="deleteBtn"
                    class="rc-delete-btn"
                    aria-label="削除"
                    data-title="{{ $post->title }}">

                    <i class="fa-regular fa-trash-can"></i>

                </button>

            </form>

        </div>
    </div>


    <main class="rc-main">

        {{-- 成功メッセージ --}}
        @if (session('status'))

            <div class="rc-status-box">

                <i class="fa-solid fa-circle-check"></i>

                <span>{{ session('status') }}</span>

            </div>

        @endif


        {{-- エラーメッセージ --}}
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


        {{-- ========================================================
             編集フォーム
             ======================================================== --}}

        <form action="{{ route('information.update', $post) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')


            {{-- Image --}}
            <div>

                <label for="imageInput" class="rc-image-box">

                    <img id="imagePreview"
                        src="{{ $post->image
                            ? asset('storage/' . $post->image)
                            : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=800&auto=format&fit=crop' }}"
                        alt="写真プレビュー">

                    <div class="rc-image-overlay">

                        <span class="rc-image-overlay-inner">

                            <i class="fa-solid fa-camera"></i>

                            <span>写真を変更</span>

                        </span>

                    </div>

                </label>

                <input type="file"
                    name="image"
                    id="imageInput"
                    accept="image/*"
                    class="rc-hidden-file">

            </div>


            @php
                $oldCategoryId = old('category_id', $post->category_id);
            @endphp

            {{-- Main Category --}}
            <div>

                <label class="rc-field-label">
                    MAIN CATEGORY
                </label>

                <select id="mainCategorySelect" class="rc-field-input">

                    <option value="">
                        選択してください
                    </option>

                    @foreach ($mainCategories as $mainCategory)
                        <option value="{{ $mainCategory->key }}"
                            {{ $mainCategory->key === $section ? 'selected' : '' }}>

                            {{ $mainCategory->name }}

                        </option>
                    @endforeach

                </select>

            </div>

            {{-- Category (サブカテゴリー: 上で選んだメインカテゴリーの中身だけ表示される) --}}
            <div>

                <label class="rc-field-label">
                    CATEGORY
                </label>

                <select name="category_id" id="categorySelect" class="rc-field-input">

                    <option value="">
                        まずメインカテゴリーを選んでください
                    </option>

                    @foreach ($categories as $category)
                        @php $isSubSelected = (int) $oldCategoryId === $category->id; @endphp
                        <option value="{{ $category->id }}" data-section="{{ $category->section }}"
                            {{ $isSubSelected ? 'selected' : '' }}
                            {{ $isSubSelected ? '' : 'hidden disabled' }}>

                            {{ $category->name }}

                        </option>
                    @endforeach

                </select>

            </div>


            {{-- Title --}}
            <div>

                <label class="rc-field-label">
                    TITLE
                </label>

                <input type="text"
                    name="title"
                    class="rc-field-input"
                    value="{{ old('title', $post->title) }}"
                    placeholder="例: Sunset Grill House">

            </div>


            {{-- Description --}}
            <div>

                <label class="rc-field-label">
                    DESCRIPTION
                </label>

                <textarea name="description"
                    rows="4"
                    class="rc-field-input"
                    placeholder="お店・サービスの特徴を書いてみましょう">{{ old('description', $post->description) }}</textarea>

            </div>


            {{-- Price --}}
            <div>

                <label class="rc-field-label">
                    PRICE (PHP)
                </label>

                <input type="number"
                    name="price"
                    min="0"
                    step="1"
                    class="rc-field-input"
                    value="{{ old('price', $post->price) }}"
                    placeholder="例: 450">

            </div>


            {{-- Location(投稿画面と同じ仮ボタン) --}}
            <div>

                <label class="rc-field-label">
                    LOCATION
                </label>

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

                    <button type="button" class="rc-save-btn"
                        style="background:#f8fafc;color:#4736F0;border:1px solid #4736F0;">

                        <i class="fa-solid fa-location-dot"></i>
                        <span>場所を追加</span>

                    </button>

                </div>

            </div>


            {{-- Save --}}
            <div>

                <button type="submit"
                    class="rc-save-btn"
                    style="background:#4736F0;color:#fff;">

                    <i class="fa-solid fa-check"></i>

                    <span>SAVE</span>

                </button>

            </div>

        </form>

    </main>


    {{-- Footer --}}
    <nav class="rc-footer-nav">

        <a href="{{ route($backRoute) }}"
            class="rc-nav-item active">

            <i class="fa-solid fa-house" style="font-size:20px;"></i>

            <span>Home</span>

        </a>


        <a href="{{ route('information.create') }}"
            class="rc-nav-item">

            <div class="rc-nav-post-icon">

                <i class="fa-solid fa-plus"></i>

            </div>

            <span>Post</span>

        </a>


        <a href="{{ route('earth') }}"
            class="rc-nav-item">

            <i class="fa-solid fa-globe" style="font-size:20px;"></i>

            <span>Map</span>

        </a>

    </nav>

</div>

    {{-- 削除確認モーダル --}}
    <div id="deleteModal" class="delete-modal" aria-hidden="true">

        <div class="delete-modal-content">

            <h2 id="deleteModalTitle"></h2>

            <p>
                この操作は取り消せません。
            </p>

            <div class="delete-modal-actions">

                <button type="button"
                    id="deleteCancelBtn"
                    class="delete-modal-cancel">
                    キャンセル
                </button>

                <button type="button"
                    id="deleteConfirmBtn"
                    class="delete-modal-confirm">
                    削除する
                </button>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
<script src="{{ asset('js/information.js') }}"></script>
@endpush
