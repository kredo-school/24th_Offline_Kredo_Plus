@extends('layouts.app')

@section('title', 'IELTS - トピック選択')

@section('content')
@php
$topicMeta = config('english.ielts_topic_meta');
@endphp

<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-orange-600 transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.ielts.index') }}" class="hover:text-orange-600 transition-colors no-underline">IELTS</a>
        <span class="mx-1">/</span>
        <span class="text-blue-950/90 font-semibold">Part {{ $part }} トピック選択</span>
    </x-english.breadcrumb>

    <div class="mb-8">
        <h1 class="text-headline-lg font-bold text-blue-950/90 mb-2">トピックを選択</h1>
        <p class="text-body-md text-blue-950/90">IELTS Speaking Part {{ $part }} のトピックを選んでください</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl">
        @foreach($topics as $topic)
        @php $meta = $topicMeta[$topic->slug] ?? []; @endphp
        <a href="/english/ielts/speaking/{{ $part }}/{{ $topic->slug }}/score"
           class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-6 flex flex-col gap-4 no-underline group">
            <div class="text-5xl">{{ $meta['emoji'] ?? '📚' }}</div>
            <div>
                <h3 class="text-headline-md font-bold text-blue-950/90 mb-2">{{ $topic->name }}</h3>
                <p class="text-body-md text-blue-950/90">{{ $topic->description ?? ($meta['desc'] ?? '') }}</p>
            </div>
            <div class="mt-auto flex items-center gap-1 text-orange-600 text-label-md font-semibold group-hover:gap-2 transition-all">
                <span>選択する</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </div>
        </a>
        @endforeach
    </div>

</div>
@endsection
