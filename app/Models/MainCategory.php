<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    protected $fillable = ['key', 'name', 'hero_image', 'description', 'sort_order', 'color', 'text_color'];

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
     * 手動指定が無い場合のフォールバック色(文字色の基準)。
     * 既存4つ=Kredoカラー固定、5個目以降=予備パレットを順番に割り当て。
     */
    private function fallbackColor(): string
    {
        $fixedColors = [
            'carinderia'      => '#22a117',
            'restaurant-cafe' => '#c2932e',
            'travel'          => '#b30f18',
            'other'           => '#0f65b3',
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

    /**
     * 手動指定が無い場合のフォールバック背景色。
     * 既存4つ=専用で選んだ薄い色を固定、5個目以降=文字色を自動で薄くして生成。
     */
    private function fallbackBackgroundColor(): string
    {
        $fixedBackgrounds = [
            'carinderia'      => '#d0f4cd',
            'restaurant-cafe' => '#fce9c0',
            'travel'          => '#fcc5c8',
            'other'           => '#c5e2fc',
        ];

        if (isset($fixedBackgrounds[$this->key])) {
            return $fixedBackgrounds[$this->key];
        }

        return Category::lighten($this->textColor(), 0.70);
    }

    /**
     * ボタン・バッジの「文字色」(濃い方の色)。
     * 管理画面で手動指定していればそれを最優先、無ければ自動割り当て(fallbackColor)にフォールバック。
     */
    public function textColor(): string
    {
        return $this->text_color ?: $this->fallbackColor();
    }

    /**
     * ボタン・バッジの「背景色」(薄い方の色)。
     * 管理画面で手動指定していればそれを最優先、無ければ文字色を白寄りに薄くして自動生成する。
     */
    public function backgroundColor(): string
    {
        return $this->color ?: $this->fallbackBackgroundColor();
    }

    /**
     * @deprecated 新しいコードではtextColor()/backgroundColor()を使うこと。
     * 既存の呼び出し箇所が残っていても壊れないよう、textColor()のエイリアスとして残す。
     */
    public function color(): string
    {
        return $this->textColor();
    }
}
