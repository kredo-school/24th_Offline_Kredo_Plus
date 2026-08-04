<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EarthLocation;

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

    /**
     * JSON化した時に自動で含める算出属性。
     * フロントのJS(index.blade.php)がpost.image_url / post.liked_by_me / post.bookmarked_by_me に
     * 直接アクセスできるようにするため。
     */
    protected $appends = ['image_url', 'liked_by_me', 'bookmarked_by_me'];

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

    /** この投稿へのいいね */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /** この投稿へのコメント */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /** この投稿のお気に入り(保存) */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
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

    /**
     * 今ログインしているユーザーがこの投稿にいいね済みかどうか。
     * $post->liked_by_me でアクセス可能。
     * Controller側で likes リレーションを「自分のいいねだけ」に絞って
     * eager load しておけば、N+1を避けてこの判定ができる。
     */
    public function getLikedByMeAttribute(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        if ($this->relationLoaded('likes')) {
            return $this->likes->contains('user_id', auth()->id());
        }

        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    /**
     * 今ログインしているユーザーがこの投稿を保存(お気に入り)済みかどうか。
     * $post->bookmarked_by_me でアクセス可能。
     */
    public function getBookmarkedByMeAttribute(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        if ($this->relationLoaded('bookmarks')) {
            return $this->bookmarks->contains('user_id', auth()->id());
        }

        return $this->bookmarks()->where('user_id', auth()->id())->exists();
    }

    //地球アイコンをクリックしたときに位置情報を入力する
        public function earthLocation()
    {
        return $this->hasOne(EarthLocation::class);
}
}
