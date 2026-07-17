<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;

class TypingRecord extends Model
{
    protected $table = 'typing_records';

    protected $fillable = [
        'user_id',
        'material_id',
        'wpm',
        'accuracy',
        'clear_time',
        'xp_gained',
    ];

    protected $casts = [
        'accuracy'   => 'float',
        'clear_time' => 'float',
    ];

    // ===== リレーション =====

    public function material()
    {
        return $this->belongsTo(TypingMaterial::class, 'material_id');
    }
}
