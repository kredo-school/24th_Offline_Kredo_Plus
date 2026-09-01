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
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ADMIN_ROLE_ID = 1;
    public const USER_ROLE_ID = 2;

    /**
     * JSON/配列化した際に、フロントエンドで必要なアクセサも含める。
     *
     * @var list<string>
     */
    protected $appends = [
        'avatar_url',
        'role',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'role_id',
        'dorm',                 // ★所属寮（必要に応じて追加）
        'course',               // ★コース（必要に応じて追加）
        'is_active',            // ★ステータス（必要に応じて追加）
        'last_active_at',        // ★最終アクセス（必要に応じて追加）
        'total_xp',
        'study_streak',
        'last_study_date',
        'total_study_time',
        'gender',
        'gender_locked',
        'toeic_exam_date',
        'ielts_exam_date',
        'graduation_date',
        'preferred_temperature',
        'preferred_pressure',
        'shower_priority_factor',
        'notifications_last_seen_at',
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
            'is_active' => 'boolean',
            'last_active_at' => 'datetime',
            'last_study_date' => 'date',
            'total_xp' => 'integer',
            'study_streak' => 'integer',
            'total_study_time' => 'integer',
            'gender_locked' => 'boolean',
            'toeic_exam_date' => 'date',
            'ielts_exam_date' => 'date',
            'graduation_date' => 'date',
            'preferred_temperature' => 'decimal:1',
            'preferred_pressure' => 'decimal:1',
        ];
    }

    // ===== 英語学習リレーション =====

    public function toeicResults(): HasMany
    {
        return $this->hasMany(ToeicResult::class);
    }

    public function toeicAnswerLogs()
    {
        return $this->hasManyThrough(ToeicAnswerLog::class, ToeicResult::class, 'user_id', 'result_id');
    }

    public function ieltsRecords(): HasMany
    {
        return $this->hasMany(IeltsRecord::class);
    }

    public function typingRecords(): HasMany
    {
        return $this->hasMany(TypingRecord::class);
    }

    public function quizResults(): HasMany
    {
        return $this->hasMany(QuizResult::class);
    }

    public function studyLogs(): HasMany
    {
        return $this->hasMany(StudyLog::class);
    }

    public function sectionProgress(): HasMany
    {
        return $this->hasMany(UserSectionProgress::class);
    }

    public function wordFavorites(): HasMany
    {
        return $this->hasMany(UserWordFavorite::class);
    }

    public function wordProgress(): HasMany
    {
        return $this->hasMany(UserWordProgress::class);
    }

    // ===== その他各種リレーション =====

    public function showerReports(): HasMany
    {
        return $this->hasMany(ShowerReport::class);
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(Suggestion::class);
    }

    public function calendarNotes(): HasMany
    {
        return $this->hasMany(CalendarNote::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    // ===== ヘルパー・アクセサ =====

    public function hasGender(): bool
    {
        return !is_null($this->gender);
    }

    public function isAdmin(): bool
    {
        return $this->role_id === self::ADMIN_ROLE_ID;
    }

    /**
     * フロントエンド判定用の role 文字列アクセサ ('admin' または 'student')
     */
    public function getRoleAttribute(): string
    {
        return $this->isAdmin() ? 'admin' : 'student';
    }

    /**
     * プロフィール写真の公開URLアクセサ
     */
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? '/storage/' . $this->avatar : null;
    }

    public function getPreferredTemperatureLabelAttribute(): string
    {
        return ShowerScale::closestLabel($this->preferred_temperature, ShowerScale::PREFERENCE_TEMPERATURE_LEVELS);
    }

    public function getPreferredPressureLabelAttribute(): string
    {
        return ShowerScale::closestLabel($this->preferred_pressure, ShowerScale::PREFERENCE_PRESSURE_LEVELS);
    }
}