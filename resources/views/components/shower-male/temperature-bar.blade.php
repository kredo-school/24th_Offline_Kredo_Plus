@props([
    'temperature' => 5, // 0〜10
])

@php
    $levels = [
        2.5 => '冷たい',
        5.0 => 'ぬるい',
        7.5 => '温かい',
        10  => '熱い',
    ];

    $closest = array_key_first($levels);

    foreach ($levels as $value => $text) {
        if (abs($temperature - $value) < abs($temperature - $closest)) {
            $closest = $value;
        }
    }

    $label = $levels[$closest];
    $percent = ($temperature / 10) * 100;
@endphp

<div class="flex justify-between items-center mb-2">
    <span class="font-bold text-blue-950">
        温度
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
                #60a5fa 0%,
                #34d399 33%,
                #fbbf24 66%,
                #ef4444 100%
            );
            clip-path: inset(0 {{ 100 - $percent }}% 0 0 round 9999px);
        ">
    </div>

</div>



@php
$labels = [
    '冷たい' => 'text-[#60a5fa]',
    'ぬるい' => 'text-[#34d399]',
    '温かい' => 'text-[#fbbf24]',
    '熱い'   => 'text-[#ef4444]',
];
@endphp

<div class="flex justify-between text-sm">
    @foreach($labels as $text => $color)
        <span class="{{ $label === $text ? "$color font-bold" : 'text-gray-300' }}">
            {{ $text }}
        </span>
    @endforeach
</div>