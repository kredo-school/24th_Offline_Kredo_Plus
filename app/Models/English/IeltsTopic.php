<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;

class IeltsTopic extends Model
{
    protected $table = 'ielts_topics';

    protected $fillable = [
        'slug',
        'name',
        'description',
        'icon',
        'sort_order',
    ];

    // ===== リレーション =====

    public function slides()
    {
        return $this->hasMany(IeltsSlide::class, 'topic_id');
    }

    public function materials()
    {
        return $this->hasMany(IeltsMaterial::class, 'topic_id');
    }

    // ===== スコープ =====

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // ===== クラスメソッド =====

    /**
     * スラッグからトピックを取得
     */
    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }
}
