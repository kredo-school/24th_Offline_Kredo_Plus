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
            '#2f5bfd', // Kredo Blue
            '#e05237', // Kredo Red
            '#f5b52e', // Kredo Yellow
            '#5eab35', // Kredo Green
            '#674cad', // 予備1(パープル)     — 5つ目
            '#39c7d6', // 予備2(ティール)     — 6つ目
            '#f041ad', // 予備3(モーブ)       — 7つ目
            '#e8467e', // 予備4(ローズレッド) — 8つ目。9つ目以降はここから再び1番目にループ
        ];
    }

    /**
     * このカテゴリー(サブカテゴリー)に割り当てる色(バッジ・タグ・アクティブ表示すべてに使用)
     * 以前はサブカテゴリーごとに別のパレット色を割り当てていたが、
     * 「メインカテゴリーと同じ色にした方がわかりやすい」との判断で、
     * 親であるメインカテゴリー(sectionが一致するMainCategory)の色をそのまま使う方式に変更。
     * サイドバーの左ガイドだけは例外で、Category::lighten()を使って薄くしたグラデーションを別途使用している。
     */
    public function color(): string
    {
        return MainCategory::findByKey($this->section)?->color() ?? self::colorPalette()[0];
    }

    /** 指定したsection(travel, other, restaurant-cafeなど)のカテゴリー一覧を並び順で取得 */
    public static function forSection(string $section)
    {
        return self::where('section', $section)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * サイドバーの色ガイド用: 指定した16進カラーを白に近づけて薄くする。
     * $percent は 0(元の色のまま) 〜 1(真っ白) の割合。
     */
    public static function lighten(string $hex, float $percent): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = (int) round($r + (255 - $r) * $percent);
        $g = (int) round($g + (255 - $g) * $percent);
        $b = (int) round($b + (255 - $b) * $percent);

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    /**
     * サイドバーの色ガイド用: 上から何番目のサブカテゴリーかに応じて薄くする割合を返す。
     * 一番上のAllはメインカテゴリーの色そのまま、そこから下に行くほど薄くなるグラデーション。
     * 5個より増えても一番薄い段階のまま(真っ白に近づきすぎないようにするため)。
     */
    public static function sidebarTint(int $index): float
    {
        $steps = [0.25, 0.50, 0.70, 0.82, 0.90];

        return $steps[$index] ?? end($steps);
    }
}
