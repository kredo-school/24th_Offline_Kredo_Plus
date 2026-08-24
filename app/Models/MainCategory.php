<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    protected $fillable = ['key', 'name', 'hero_image', 'description', 'sort_order', 'color'];

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
     * 管理画面(留学情報管理)で手動でカラーを設定していればそれを最優先で使う。
     * 未設定(null)の場合のみ、これまで通りの自動割り当て
     * (既存4つ=Kredoカラー固定、5個目以降=予備パレットを順番に割り当て)にフォールバックする。
     */
    public function color(): string
    {
        if (!empty($this->color)) {
            return $this->color;
        }

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
