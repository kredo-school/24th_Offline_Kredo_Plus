@extends('layouts.app')

@section('title', 'English Learning Hub')

@section('content')
<div class="bg-[#fef9f8]">
    <div class="max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-8 md:py-10">

        {{-- Breadcrumb --}}
        <nav class="text-sm text-on-surface-variant mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-primary transition-colors">ホーム</a>
            <span class="mx-1.5">/</span>
            <span class="font-bold text-on-surface">英語学習</span>
        </nav>

        {{-- ヒーロー: タイトル + 現在のレベルカード --}}
        <section class="relative overflow-hidden rounded-[24px] mb-8 h-[300px] bg-cover bg-center"
                  style="background-image: url('{{ asset('images/english/hub-hero.webp') }}');">
            <div class="absolute inset-0 bg-gradient-to-r from-white from-10% via-white/70 via-45% to-transparent to-80%"></div>

            <div class="relative h-full flex items-center px-10">
                <div class="max-w-sm">
                    <h1 class="text-4xl font-black text-on-surface mb-1">英語学習</h1>
                    <p class="text-lg font-bold text-primary mb-3">English Learning</p>
                    <p class="text-sm text-on-surface-variant leading-relaxed">
                        TOEIC・IELTS対策を中心に、英語力を伸ばすための学習コンテンツをご利用いただけます。
                    </p>
                </div>
            </div>

            {{-- 現在のレベルカード（右側にフロート表示） --}}
            <div class="hidden md:block absolute top-6 right-6 bottom-6 w-[300px] bg-white rounded-[20px] shadow-xl p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 shrink-0 bg-primary-container rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">military_tech</span>
                    </div>
                    <div>
                        <p class="text-xs text-on-surface-variant leading-none mb-1">現在のレベル</p>
                        <p class="text-2xl font-black text-on-surface leading-none">Lv.{{ $levelInfo['level'] }}</p>
                    </div>
                </div>

                <x-english.xp-bar
                    :level="$levelInfo['level']"
                    :currentXp="$levelInfo['xp_in_level']"
                    :nextXp="$levelInfo['xp_needed']" />

                <div class="grid grid-cols-3 gap-2 mt-5 pt-4 border-t border-outline-variant">
                    <div class="text-center">
                        <p class="text-lg font-black text-on-surface">{{ number_format($levelInfo['current_xp']) }}</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">Total XP</p>
                    </div>
                    <div class="text-center border-x border-outline-variant">
                        <p class="text-lg font-black text-on-surface">{{ $totalStudyDays }}</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">総学習日数</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-black text-on-surface">{{ $overallProgress }}%</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">全体進捗率</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- 試験概要・学習ストラテジー バナー --}}
        <a href="#"
           class="flex items-center gap-5 bg-[#faece9] rounded-[20px] p-5 mb-8 no-underline text-inherit hover:brightness-95 transition-all">
            <div class="w-12 h-12 shrink-0 bg-primary-container rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">menu_book</span>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-bold text-on-surface mb-0.5">試験概要と学習ストラテジー</h3>
                <p class="text-sm text-on-surface-variant">TOEIC・IELTSの試験概要や出題形式を確認し、効果的な学習の進め方を学びましょう。</p>
            </div>
            <span class="hidden sm:flex items-center gap-1 text-primary text-sm font-bold shrink-0">
                詳しく見る
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </span>
        </a>

        {{-- 機能カードグリッド --}}
        <section class="mb-8">
            <h2 class="font-bold text-lg text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">auto_stories</span>
                学習メニュー
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <x-english.feature-card
                    icon="menu_book"
                    title="TOEIC学習"
                    description="TOEICの文法・読解を中心に学習"
                    href="#"
                    :progress="$featureProgress['toeic']"
                />
                <x-english.feature-card
                    icon="record_voice_over"
                    title="IELTS Speaking"
                    description="IELTSスピーキングを実践的に練習"
                    href="#"
                    :progress="$featureProgress['ielts']"
                />
                <x-english.feature-card
                    icon="translate"
                    title="英単語"
                    description="フラッシュカードで語彙を強化"
                    href="#"
                    :progress="$featureProgress['vocabulary']"
                />
                <x-english.feature-card
                    icon="keyboard"
                    title="タイピング練習"
                    description="英語タイピングのスピードと精度を向上"
                    href="#"
                    :progress="$featureProgress['typing']"
                />
                <x-english.feature-card
                    icon="quiz"
                    title="クイズ"
                    description="スペル・語彙クイズで実力をテスト"
                    href="#"
                    actionLabel="問題に挑戦する"
                />
                <x-english.feature-card
                    icon="assignment"
                    title="試験概要"
                    description="TOEIC・IELTSの試験形式を確認"
                    href="#"
                    actionLabel="詳しく見る"
                />
            </div>
        </section>

        {{-- 下部ナビゲーション --}}
        <section class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="#" class="flex items-center gap-4 bg-white rounded-[20px] shadow-sm hover:shadow-md transition-all p-5 no-underline text-inherit group">
                <div class="w-11 h-11 shrink-0 bg-primary-container rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">trending_up</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-on-surface">学習管理</h3>
                    <p class="text-sm text-on-surface-variant">進捗・履歴・学習日数を確認</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
            </a>
            <a href="#" class="flex items-center gap-4 bg-white rounded-[20px] shadow-sm hover:shadow-md transition-all p-5 no-underline text-inherit group">
                <div class="w-11 h-11 shrink-0 bg-primary-container rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">leaderboard</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-on-surface">ランキング</h3>
                    <p class="text-sm text-on-surface-variant">週間・月間・総合ランキング</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
            </a>
        </section>

    </div>
</div>
@endsection
