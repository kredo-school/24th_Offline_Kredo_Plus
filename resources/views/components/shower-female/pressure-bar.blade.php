@props([
    'pressure' => 7, // 0〜10
])

@php
    $levels = [
        3.3 => '弱い',
        6.6 => '普通',
        10  => '強い',
    ];

    $closest = array_key_first($levels);

    foreach ($levels as $value => $text) {
        if (abs($pressure - $value) < abs($pressure - $closest)) {
            $closest = $value;
        }
    }

    $label = $levels[$closest];
    $percent = ($pressure / 10) * 100;
@endphp

<div class="flex justify-between items-center mb-2">
    <span class="font-bold text-blue-950">
        水圧
    </span>
</div>

<div class="relative w-full h-4 rounded-full overflow-hidden">

    <!-- グレー背景 -->
    <div class="absolute inset-0 bg-gray-200"></div>

    <!-- グラデーション -->
    <div
        class="absolute inset-0 rounded-full"
        style="
            background: linear-gradient(
                to right,
                #76a4ed 0%,
                #3B82F6 33%,
                #1d5cc2 66%,
                #043076 100%
            );
            clip-path: inset(0 {{ 100 - $percent }}% 0 0 round 9999px);
        ">
    </div>

</div>



@php
$labels = [
    '弱い' => 'text-blue-500',
    '普通' => 'text-blue-700',
    '強い'   => 'text-blue-900',
];
@endphp

<div class="flex justify-between text-sm">
    @foreach($labels as $text => $color)
        <span class="{{ $label === $text ? "$color font-bold" : 'text-gray-300' }}">
            {{ $text }}
        </span>
    @endforeach
</div>