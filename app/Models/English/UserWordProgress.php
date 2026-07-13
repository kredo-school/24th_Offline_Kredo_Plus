<?php

namespace App\Models\English;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserWordProgress extends Model
{
    protected $table = 'user_word_progress';

    // status の定数
    const STATUS_NOT_LEARNED = 0;
    const STATUS_LEARNING    = 1;
    const STATUS_LEARNED     = 2;

    protected $fillable = [
        'user_id',
        'word_id',
        'status',
        'correct_count',
        'incorrect_count',
        'last_reviewed_at',
    ];

    protected $casts = [
        'last_reviewed_at' => 'datetime',
        'status'           => 'integer',
    ];

    // ===== リレーション =====

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function word()
    {
        return $this->belongsTo(VocabularyWord::class, 'word_id');
    }

    // ===== スコープ =====

    public function scopeLearned($query)
    {
        return $query->where('status', self::STATUS_LEARNED);
    }
}
