<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;

class IeltsRecord extends Model
{
    protected $table = 'ielts_records';

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
        return $this->belongsTo(IeltsMaterial::class, 'material_id');
    }
}
