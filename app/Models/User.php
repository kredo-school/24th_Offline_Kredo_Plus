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
     * JSON/配列化した際に、avatar_url（アクセサ）も一緒に含める。
     * 投稿一覧などで user を丸ごとJSに渡す箇所で、素の avatar カラムではなく
     * 表示用URLをそのまま使えるようにするため。
     *
     * @var list<string>
     */
    protected $appends = [
        'avatar_url',
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

    // プロフィール写真の公開URL
    // Storage::url() は APP_URL のホストで絶対URLを組み立てるため、
    // APP_URL と実際のアクセス先ホストが異なる開発環境では画像が読み込めなくなる。
    // そのため常にルート相対パスを返し、今アクセスしているホストから解決させる。
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? '/storage/' . $this->avatar : null;
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

    // 目安箱
    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }

    // マイカレンダー（課題・イベントのメモ）
    public function calendarNotes()
    {
        return $this->hasMany(CalendarNote::class);
    }

    // 留学情報: 自分が投稿した投稿
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // 留学情報: 自分がいいねした投稿（Likeレコード）
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // 留学情報: 自分が保存した投稿（Bookmarkレコード）
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}
