<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
    ];

    /**
     * 送信先ユーザーとのリレーション（個別送信時）
     */
    public function user()
    {
        return $table = $this->belongsTo(User::class);
    }
}