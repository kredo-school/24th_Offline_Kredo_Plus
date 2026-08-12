<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\English\IeltsRecord;
use App\Models\English\QuizResult;
use App\Models\English\StudyLog;
use App\Models\English\ToeicAnswerLog;
use App\Models\English\ToeicResult;
use App\Models\English\TypingRecord;
use App\Models\English\UserSectionProgress;
use App\Models\English\UserWordFavorite;
use App\Models\English\UserWordProgress;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Services\Shower\ShowerScale;
use App\Models\Shower\ShowerReport;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ADMIN_ROLE_ID = 1;
    public const USER_ROLE_ID = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'total_xp',
        'study_streak',
        'last_study_date',
        'total_study_time',
        'gender',
        'gender_locked',
        'toeic_exam_date',
        'ielts_exam_date',
        'preferred_temperature',
        'preferred_pressure',
        'shower_priority_factor',
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
            'role_id' => 'integer',
            'last_study_date' => 'date',
            'total_xp' => 'integer',
            'study_streak' => 'integer',
            'total_study_time' => 'integer',
            'gender_locked' => 'boolean',
            'toeic_exam_date' => 'date',
            'ielts_exam_date' => 'date',
            'preferred_temperature' => 'decimal:1',
            'preferred_pressure' => 'decimal:1',
        ];
    }

    // ===== 英語学習リレーション =====

    public function toeicResults()
    {
        return $this->hasMany(ToeicResult::class);
    }

    public function toeicAnswerLogs()
    {
        return $this->hasManyThrough(ToeicAnswerLog::class, ToeicResult::class, 'user_id', 'result_id');
    }

    public function ieltsRecords()
    {
        return $this->hasMany(IeltsRecord::class);
    }

    public function typingRecords()
    {
        return $this->hasMany(TypingRecord::class);
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

    // シャワーリレーション
    // 性別登録
    public function hasGender(): bool
    {
        return !is_null($this->gender);
    }

    public function isAdmin(): bool
    {
        return $this->role_id === self::ADMIN_ROLE_ID;
    }

    // シャワーの好み登録

    public function getPreferredTemperatureLabelAttribute(): string
    {
        return ShowerScale::closestLabel($this->preferred_temperature, ShowerScale::PREFERENCE_TEMPERATURE_LEVELS);
    }

    public function getPreferredPressureLabelAttribute(): string
    {
        return ShowerScale::closestLabel($this->preferred_pressure, ShowerScale::PREFERENCE_PRESSURE_LEVELS);
    }

    // シャワー状態の管理
    public function showerReports()
{
    return $this->hasMany(ShowerReport::class);
}
}
