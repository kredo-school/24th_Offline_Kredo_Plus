<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;

class TypingSlide extends Model
{
    protected $table = 'typing_slides';

    protected $fillable = [
        'material_id',
        'step_number',
        'slide_type',
        'title',
        'content',
        'sort_order',
    ];

    // ===== リレーション =====

    public function material()
    {
        return $this->belongsTo(TypingMaterial::class, 'material_id');
    }
}
