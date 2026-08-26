<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MainCategory extends Model
{
    protected $fillable = ['key', 'name', 'hero_image', 'description', 'sort_order'];

    /**
     * サブカテゴリー（Category）とのリレーション
     * categories.main_category_key と main_categories.key で紐付け
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'main_category_key', 'key');
    }

    /** key(例: 'carinderia')を指定して、そのメインカテゴリーの行を1件取得する */
    public static function findByKey(string $key): ?self
    {
        return self::where('key', $key)->first();
    }

    /** 表示順(sort_order → id)ですべてのメインカテゴリーを取得する（ホーム画面のボタン一覧などで使用予定） */
    public static function allOrdered()
    {
        return self::orderBy('sort_order')->orderBy('id')->get();
    }

    /**
     * 既存4つと同じKredoカラー(Carinderia=青, Restaurant&Cafe=赤, Travel=黄, Other=緑)を
     * そのまま使い、5個目以降は予備色を順番に割り当てる。
     */
    public function color(): string
    {
        $fixedColors = [
            'carinderia'      => '#2f5fdb',
            'restaurant-cafe' => '#e05237',
            'travel'          => '#f5b52e',
            'other'           => '#5eab35',
        ];

        if (isset($fixedColors[$this->key])) {
            return $fixedColors[$this->key];
        }

        $palette = Category::colorPalette();
        $index = self::where(function ($q) {
            $q->where('sort_order', '<', $this->sort_order)
              ->orWhere(function ($q2) {
                  $q2->where('sort_order', $this->sort_order)
                     ->where('id', '<', $this->id);
              });
        })->count();

        return $palette[$index % count($palette)];
    }
}