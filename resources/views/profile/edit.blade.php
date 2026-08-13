@extends('layouts.app')

@section('title', 'マイプロフィール - Kredo Plus')

@section('content')
<div class="bg-slate-50 min-h-[calc(100vh-80px)] py-10 px-4 sm:px-6">
    <div class="max-w-4xl mx-auto space-y-8">

        <!-- フラッシュメッセージ -->
        @if (session('status') === 'profile-updated' || session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                <span class="text-sm font-bold">
                    {{ session('status') === 'profile-updated' ? 'プロフィールを更新しました！' : session('status') }}
                </span>
            </div>
        @endif

        <!-- 1. プロフィールカード（ヘッダー） -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-card border border-slate-100 flex flex-col sm:flex-row items-center sm:items-start gap-6">
            
            <!-- アバターアイコン -->
            <div class="relative group">
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-gradient-to-br from-sky-400 to-brand-blue text-white text-3xl sm:text-4xl font-extrabold flex items-center justify-center shadow-md ring-4 ring-sky-50">
                    {{ Str::of($user->name)->substr(0, 1)->upper() }}
                </div>
            </div>

            <!-- ユーザー詳細情報 -->
            <div class="flex-1 text-center sm:text-left space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="flex items-center justify-center sm:justify-start gap-2">
                            <h1 class="text-2xl font-extrabold text-slate-800">{{ $user->name }}</h1>
                            @if($user->isAdmin())
                                <span class="bg-indigo-100 text-indigo-700 text-xs font-extrabold px-2.5 py-0.5 rounded-full">管理者</span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-400 font-medium">{{ $user->email }}</p>
                    </div>

                    <!-- 操作ボタン群 -->
                    <div class="flex items-center justify-center gap-2 pt-2 sm:pt-0">
                        <!-- 編集モーダル開くボタン -->
                        <button onclick="document.getElementById('edit-profile-modal').classList.remove('hidden')"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-blue hover:bg-blue-600 text-white text-xs font-bold rounded-xl shadow-sm transition-all hover:shadow">
                            <span class="material-symbols-outlined !text-base">edit</span>
                            プロフィール編集
                        </button>

                        <!-- ログアウトボタン -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 text-xs font-bold rounded-xl transition-all">
                                <span class="material-symbols-outlined !text-base">logout</span>
                                ログアウト
                            </button>
                        </form>
                    </div>
                </div>

                <!-- 英語学習アクティビティ（統計ハイライト） -->
                <div class="pt-2 grid grid-cols-3 gap-3 max-w-md">
                    <div class="bg-sky-50/60 border border-sky-100 rounded-2xl p-3 text-center">
                        <span class="block text-xs font-bold text-slate-400">Total XP</span>
                        <span class="text-lg font-extrabold text-brand-blue">{{ number_format($user->total_xp ?? 0) }}</span>
                    </div>
                    <div class="bg-amber-50/60 border border-amber-100 rounded-2xl p-3 text-center">
                        <span class="block text-xs font-bold text-slate-400">連続学習</span>
                        <span class="text-lg font-extrabold text-amber-500">{{ $user->study_streak ?? 0 }} 日</span>
                    </div>
                    <div class="bg-emerald-50/60 border border-emerald-100 rounded-2xl p-3 text-center">
                        <span class="block text-xs font-bold text-slate-400">総学習時間</span>
                        <span class="text-lg font-extrabold text-emerald-600">{{ round(($user->total_study_time ?? 0) / 60, 1) }}h</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. 学習＆シャワー設定（詳細ステータス） -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- 英語学習目標 -->
            <div class="bg-white rounded-3xl p-6 shadow-card border border-slate-100 space-y-4">
                <div class="flex items-center gap-2 text-brand-yellow font-extrabold text-lg border-b border-slate-100 pb-3">
                    <span class="material-symbols-outlined">school</span>
                    英語試験予定日
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl text-sm">
                        <span class="font-bold text-slate-600">TOEIC 試験日</span>
                        <span class="font-extrabold text-slate-800">
                            {{ $user->toeic_exam_date ? $user->toeic_exam_date->format('Y/m/d') : '未設定' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl text-sm">
                        <span class="font-bold text-slate-600">IELTS 試験日</span>
                        <span class="font-extrabold text-slate-800">
                            {{ $user->ielts_exam_date ? $user->ielts_exam_date->format('Y/m/d') : '未設定' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- シャワーの好み設定 -->
            <div class="bg-white rounded-3xl p-6 shadow-card border border-slate-100 space-y-4">
                <div class="flex items-center gap-2 text-brand-blue font-extrabold text-lg border-b border-slate-100 pb-3">
                    <span class="material-symbols-outlined">shower</span>
                    シャワーの好み設定
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl text-sm">
                        <span class="font-bold text-slate-600">お好みの水温</span>
                        <span class="font-extrabold text-slate-800">{{ $user->preferred_temperature_label ?? '未設定' }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl text-sm">
                        <span class="font-bold text-slate-600">お好みの水圧</span>
                        <span class="font-extrabold text-slate-800">{{ $user->preferred_pressure_label ?? '未設定' }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- 3. プロフィール編集用ポップアップ（モーダル） -->
<div id="edit-profile-modal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative space-y-6">
        
        <div class="flex justify-between items-center border-b border-slate-100 pb-4">
            <h3 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-blue">manage_accounts</span>
                プロフィール編集
            </h3>
            <button onclick="document.getElementById('edit-profile-modal').classList.add('hidden')" 
                    class="text-slate-400 hover:text-slate-600 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('patch')

            <!-- アカウント名 -->
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">お名前</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue" required>
                @error('name')
                    <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- メールアドレス -->
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue" required>
                @error('email')
                    <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" 
                        onclick="document.getElementById('edit-profile-modal').classList.add('hidden')" 
                        class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-all">
                    キャンセル
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 text-xs font-bold bg-brand-blue hover:bg-blue-600 text-white rounded-xl shadow-sm hover:shadow transition-all">
                    変更を保存
                </button>
            </div>
        </form>

    </div>
</div>

<!-- バリデーションエラー発生時に自動でモーダルを開くスクリプト -->
@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('edit-profile-modal').classList.remove('hidden');
        });
    </script>
@endif
@endsection