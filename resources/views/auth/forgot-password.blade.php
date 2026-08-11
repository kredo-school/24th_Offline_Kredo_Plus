@extends('layouts.guest')

@section('content')
    <div class="mb-4 text-sm text-gray-600 text-center">
        {{ __('ご登録のメールアドレスをご入力ください。新しいパスワードを設定するためのリンクを記載したメールをお送りしますので、メール内の案内に従ってお手続きください。') }}
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('メールアドレス')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('パスワードリセットリンクを送信') }}
            </x-primary-button>
        </div>
    </form>
@endsection

@section('below-card')
    <a href="{{ route('login') }}" class="text-sm text-gray-500 underline hover:text-gray-800">
        {{ __('ログイン画面に戻る') }}
    </a>
@endsection
