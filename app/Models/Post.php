<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * Mass assignment を許可するカラム
     * (posts テーブルの migration に対応)
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'image',
        'price',
        'map_query',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /** この投稿の投稿者 */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** この投稿のカテゴリー(Restaurant / Cafe など) */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * フロント表示用の画像URLを組み立てるアクセサ
     * $post->image_url でアクセス可能
     * (storage/app/public/posts に保存されている想定)
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        // 画像未設定時のプレースホルダー
        return 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=800&auto=format&fit=crop';
    }
}
