<?php

namespace App\Services\Shower;

class ShowerScale
{

    public const CONDITION_TEMPERATURE_LEVELS = [
        '冷たい' => 2.5,
        'ぬるい' => 5.0,
        '温かい' => 7.5,
        '熱い'   => 10.0,
    ];

    public const CONDITION_PRESSURE_LEVELS = [
        '無し' => 0.0,
        '弱い' => 3.3,
        '普通' => 6.6,
        '強い' => 10.0,
    ];

    public const PREFERENCE_TEMPERATURE_LEVELS = [
        '冷たい' => 2.5,
        'ぬるい' => 5.0,
        '温かい' => 7.5,
        '熱い'   => 10.0,
    ];

    public const PREFERENCE_PRESSURE_LEVELS = [
        '弱い' => 3.3,
        '普通' => 6.6,
        '強い' => 10.0,
    ];
    
    public static function closestLabel(float $value, array $levels): string
    {
        $closestLabel = array_key_first($levels);
        $closestValue = $levels[$closestLabel];

        foreach ($levels as $label => $v) {
            if (abs($value - $v) < abs($value - $closestValue)) {
                $closestLabel = $label;
                $closestValue = $v;
            }
        }

        return $closestLabel;
    }

    /**
     * 指定したラベルが対象とする数値の範囲を返す(隣接ラベルとの中間値が境界)。
     *
     * @return array{0: float, 1: float} [最小値, 最大値]
     */
    public static function rangeForLabel(string $label, array $levels): array
    {
        $labels = array_keys($levels);
        $values = array_values($levels);
        $index = array_search($label, $labels, true);

        if ($index === false) {
            return [0, 10];
        }

        $min = $index === 0 ? 0 : ($values[$index - 1] + $values[$index]) / 2;
        $max = $index === count($values) - 1 ? $values[$index] : ($values[$index] + $values[$index + 1]) / 2;

        return [$min, $max];
    }

    /**
     * 0〜10の範囲をラベル数で均等分割し、値がどのゾーンに属するかを判定する。
     * (投稿フォームのスライダーと同じロジック)
     */
    public static function zoneLabel(float $value, array $levels): string
    {
        $labels = array_keys($levels);
        $count = count($labels);
        $value = max(0, min(10, $value));

        $index = (int) floor(($value / 10) * $count);
        $index = min($index, $count - 1);

        return $labels[$index];
    }

    /**
     * 指定したラベルが担当する数値範囲を、均等分割ゾーンとして返す。
     *
     * @return array{0: float, 1: float}
     */
    public static function zoneRange(string $label, array $levels): array
    {
        $labels = array_keys($levels);
        $count = count($labels);
        $index = array_search($label, $labels, true);

        if ($index === false) {
            return [0, 10];
        }

        $zoneWidth = 10 / $count;
        $min = $index * $zoneWidth;
        $max = $index === $count - 1 ? 10 : ($index + 1) * $zoneWidth;

        return [$min, $max];
    }

}