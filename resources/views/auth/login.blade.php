@extends('layouts.guest')

@section('welcome', 'おかえりなさい、ログインして続けましょう。')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1.5">
            <label class="text-sm font-bold text-slate-600 ml-1" for="email">メールアドレス</label>
            <div class="relative">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                <input class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue outline-none transition-all text-sm"
                       id="email" name="email" type="email" placeholder="student@kredo.edu"
                       value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <div class="flex justify-between items-center px-1">
                <label class="text-sm font-bold text-slate-600" for="password">パスワード</label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-brand-blue hover:underline transition-all" href="{{ route('password.request') }}">お忘れですか？</a>
                @endif
            </div>
            <div class="relative">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                <input class="w-full pl-10 pr-12 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-blue/40 focus:border-brand-blue outline-none transition-all text-sm"
                       id="password" name="password" type="password" placeholder="••••••••"
                       required autocomplete="current-password">
                <button class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-brand-blue transition-colors" type="button" data-toggle-password="password" aria-label="パスワードを表示">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <label for="remember_me" class="flex items-center gap-2 px-1 text-sm text-slate-500">
            <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-brand-blue focus:ring-brand-blue/40">
            {{ __('Remember me') }}
        </label>

        <!-- Submit -->
        <button class="w-full py-3.5 bg-brand-blue text-white rounded-full font-bold shadow-soft hover:bg-indigo-700 active:scale-95 transition-all flex items-center justify-center gap-2 mt-6" type="submit">
            <span>Sign In</span>
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-slate-500">
            アカウントをお持ちでないですか？
            <a href="{{ route('register') }}" class="text-brand-blue font-bold hover:underline">新規登録</a>
        </p>
    </div>
@endsection
