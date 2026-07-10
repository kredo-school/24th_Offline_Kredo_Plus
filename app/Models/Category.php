<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['section', 'name', 'slug', 'hero_image', 'description', 'sort_order'];

    /**
     * Kredoロゴと同じ4色 + 5つ目以降用の予備色。
     * カテゴリーが追加された順番(sort_order / id)でこの配列をループして割り当てる。
     */
    public static function colorPalette(): array
    {
        return [
            '#2f5fdb', // Kredo Blue
            '#e05237', // Kredo Red
            '#f5b52e', // Kredo Yellow
            '#5eab35', // Kredo Green
            '#674cad', // 予備1(パープル) — 5つ目以降はここから追加していく
            '#39c7d6', // 予備2(ティール) — 6つ目
            '#f041ad', // 予備3(モーブ)    — 7つ目
        ];
    }

    /**
     * このカテゴリーに割り当てる色(見た目のバッジ・下線・アクティブ表示すべてに使用)
     * 同じ section 内での並び順(sort_order昇順)を基準にループする。
     */
    public function color(): string
    {
        $palette = self::colorPalette();

        // 同じsection内で、自分より前に追加されたカテゴリーの数 = 自分の並び順
        $index = self::where('section', $this->section)
            ->where(function ($q) {
                $q->where('sort_order', '<', $this->sort_order)
                  ->orWhere(function ($q2) {
                      $q2->where('sort_order', $this->sort_order)
                         ->where('id', '<', $this->id);
                  });
            })
            ->count();

        return $palette[$index % count($palette)];
    }

    /** 指定したsection(travel, other, restaurant-cafeなど)のカテゴリー一覧を並び順で取得 */
    public static function forSection(string $section)
    {
        return self::where('section', $section)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}
