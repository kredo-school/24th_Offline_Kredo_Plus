<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'problem' => 'お困りごと',
        'request' => 'ご要望',
        'other' => 'その他',
    ];

    public const STATUSES = [
        'pending' => '未対応',
        'in_progress' => '対応中',
        'resolved' => '対応済み',
    ];

    protected $fillable = [
        'user_id',
        'category',
        'comment',
        'status',
        'admin_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}