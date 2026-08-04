@props([
    'pressure' => 7, // 0〜10
])

@php
    $levels = [
        2.5 => '無し',
        5.0 => '弱い',
        7.5 => '普通',
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
                #99b9ed 0%,
                #608fd9 33%,
                #366bc0 66%,
                #194489 100%
            );
            clip-path: inset(0 {{ 100 - $percent }}% 0 0 round 9999px);
        ">
    </div>

</div>



@php
$labels = [
    '無し' => 'text-blue-300',
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