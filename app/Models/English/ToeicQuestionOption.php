<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;

class ToeicQuestionOption extends Model
{
    protected $table = 'toeic_question_options';

    protected $fillable = [
        'question_id',
        'label',
        'option_text',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    // ===== リレーション =====

    public function question()
    {
        return $this->belongsTo(ToeicQuestion::class, 'question_id');
    }

    // ===== スコープ =====

    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }
}
