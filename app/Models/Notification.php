<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public const TYPES = [
        'capacity_full' => '満室報告',
        'capacity_vacant' => '満室解除',
        'malfunction_broken' => '故障報告',
        'malfunction_fixed' => '修理完了',
    ];

    protected $fillable = [
        'gender',
        'type',
        'shower_number',
        'message',
    ];

    public function scopeForGender($query, string $gender)
    {
        return $query->where('gender', $gender);
    }
}
