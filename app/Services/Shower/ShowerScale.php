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
}