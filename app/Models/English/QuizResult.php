<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    protected $table = 'quiz_results';

    protected $fillable = [
        'user_id',
        'quiz_type',
        'exam_type',
        'level',
        'total_questions',
        'correct_count',
        'xp_gained',
    ];
}
