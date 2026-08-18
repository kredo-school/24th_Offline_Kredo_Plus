@extends('layouts.app')

@section('title', 'Ranking')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-orange-600 transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <span class="text-blue-950/90 font-semibold">ランキング</span>
    </x-english.breadcrumb>

    <div class="mb-8 text-center">
        <h1 class="text-headline-lg font-bold text-blue-950/90 mb-2">ランキング</h1>
        <p class="text-body-md text-blue-950/90">獲得したXP（週間・月間・Total）でランキングを競おう</p>
    </div>

    <div class="max-w-3xl mx-auto">

        {{-- タブ（GETリクエスト） --}}
        <div class="flex gap-2 mb-6 justify-center">
            @foreach([['id'=>'weekly','label'=>'週間'],['id'=>'monthly','label'=>'月間'],['id'=>'total','label'=>'Total']] as $tab)
            <a href="{{ route('english.ranking', ['period' => $tab['id']]) }}"
               class="{{ $period === $tab['id'] ? 'bg-[#b95827] text-white' : 'bg-surface-container-lowest shadow-sm text-blue-950/90 hover:bg-slate-50' }} px-6 py-2.5 rounded-[0.75rem] font-label-md text-label-md transition-all no-underline">
                {{ $tab['label'] }}
            </a>
            @endforeach
        </div>

        {{-- ランキングテーブル --}}
        <div class="bg-surface-container-lowest rounded-[0.5rem] shadow-sm overflow-hidden mb-4">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gradient-to-br from-orange-100 to-amber-50 border-b border-orange-200">
                        <th class="py-4 px-4 text-label-md text-blue-950/90 text-center w-16">順位</th>
                        <th class="py-4 px-4 text-label-md text-blue-950/90">ユーザー</th>
                        <th class="py-4 px-4 text-label-md text-blue-950/90 text-center">Level</th>
                        <th class="py-4 px-4 text-label-md text-blue-950/90 text-center">総学習日数</th>
                        <th class="py-4 px-4 text-label-md text-blue-950/90 text-right">
                            {{ $period === 'total' ? 'Total XP' : ($period === 'weekly' ? '週間 XP' : '月間 XP') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/50">
                    @forelse($rankings as $i => $entry)
                    @php
                        $rank = ($rankings->currentPage() - 1) * $rankings->perPage() + $i + 1;
                        $level = (int) floor($entry->total_xp / 500) + 1;
                        $xpDisplay = $period === 'total' ? $entry->total_xp : ($entry->period_xp ?? 0);
                        $isMe = $entry->id === $user->id;
                    @endphp
                    <tr class="{{ $rank <= 3 ? 'bg-gradient-to-br from-orange-100 to-amber-50 font-semibold' : '' }} {{ $isMe ? 'bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-inset ring-orange-300' : '' }} hover:bg-surface-container-low/50 transition-colors">
                        <td class="py-4 px-4 text-center font-bold">
                            @if($rank === 1) <span class="text-xl">🥇</span>
                            @elseif($rank === 2) <span class="text-xl">🥈</span>
                            @elseif($rank === 3) <span class="text-xl">🥉</span>
                            @else <span class="text-blue-950/90 text-sm">{{ $rank }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-blue-950/90">
                            {{ $entry->name }}
                            @if($isMe)<span class="ml-1 text-caption text-orange-600 font-bold">（あなた）</span>@endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="bg-gradient-to-br from-orange-100 to-amber-50 ring-1 ring-orange-200 text-orange-600 text-caption font-bold px-2 py-0.5 rounded-[0.75rem]">Lv.{{ $level }}</span>
                        </td>
                        <td class="py-4 px-4 text-center text-blue-950/90">{{ $entry->total_study_days }}日</td>
                        <td class="py-4 px-4 text-right font-bold text-orange-600">{{ number_format($xpDisplay) }} <span class="text-caption font-normal text-blue-950/90">XP</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-blue-950/90 text-body-md">
                            この期間のランキングデータがありません
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ページネーション --}}
        @if($rankings->hasPages())
        <div class="mb-4">
            {{ $rankings->appends(['period' => $period])->links() }}
        </div>
        @endif

        {{-- 自分の順位（下部固定表示） --}}
        <div class="bg-gradient-to-br from-blue-50 to-sky-100 border-2 border-blue-600 rounded-[0.5rem] p-4">
            <p class="text-caption text-blue-600 font-bold mb-2">あなたの順位</p>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="text-headline-md font-black text-blue-600">{{ $myRank }}位</span>
                    <div>
                        <p class="font-bold text-blue-950/90">{{ $user->name }}</p>
                        <span class="bg-gradient-to-br from-blue-50 to-sky-100 ring-1 ring-blue-200 text-blue-600 text-caption font-bold px-2 py-0.5 rounded-[0.75rem]">Lv.{{ $levelInfo['level'] }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-black text-blue-600 text-headline-md">{{ number_format($levelInfo['current_xp']) }}</p>
                    <p class="text-caption text-blue-950/90">Total XP</p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
