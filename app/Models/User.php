<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\English\IeltsRecord;
use App\Models\English\QuizResult;
use App\Models\English\StudyLog;
use App\Models\English\ToeicResult;
use App\Models\English\TypingRecord;
use App\Models\English\UserSectionProgress;
use App\Models\English\UserWordFavorite;
use App\Models\English\UserWordProgress;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'total_xp',
        'study_streak',
        'last_study_date',
        'total_study_time',
        'gender',
        'gender_locked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_study_date' => 'date',
            'total_xp' => 'integer',
            'study_streak' => 'integer',
            'total_study_time' => 'integer',
            'gender_locked' => 'boolean',
        ];
    }

    // ===== 英語学習リレーション =====

    public function typingRecords()
    {
        return $this->hasMany(TypingRecord::class);
    }

    public function ieltsRecords()
    {
        return $this->hasMany(IeltsRecord::class);
    }

    public function toeicResults()
    {
        return $this->hasMany(ToeicResult::class);
    }

    public function quizResults()
    {
        return $this->hasMany(QuizResult::class);
    }

    public function studyLogs()
    {
        return $this->hasMany(StudyLog::class);
    }

    public function sectionProgress()
    {
        return $this->hasMany(UserSectionProgress::class);
    }

    public function wordFavorites()
    {
        return $this->hasMany(UserWordFavorite::class);
    }

    public function wordProgress()
    {
        return $this->hasMany(UserWordProgress::class);
    }

    /**
     * 累積XPから現在のレベルを動的に算出
     * level = floor(total_xp / 500) + 1
     */
    public function getLevelAttribute(): int
    {
        return (int) floor($this->total_xp / 500) + 1;
    }

    /**
     * 次のレベルに必要な累積XP
     */
    public function getNextLevelXpAttribute(): int
    {
        return $this->level * 500;
    }

    /**
     * 現在のレベル内でのXP（進捗バー用）
     * current_level_xp = total_xp % 500
     */
    public function getCurrentLevelXpAttribute(): int
    {
        return (int) ($this->total_xp % 500);
    }

    // シャワーリレーション
    public function hasGender(): bool
    {
        return !is_null($this->gender);
    }
}
