<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Post;

/**
 * 5個目以降、アドミンが新しく追加したメインカテゴリー用の汎用ページ。
 *
 * 既存のCarinderia/RestaurantCafe/Travel/Other用Controllerはこのファイルとは
 * 完全に別物で、一切変更していない。ここは「main_categoriesテーブルに登録された、
 * 上記4つ以外のキー」が来た時にだけ使われる。
 *
 * Travel(TravelController)のindex/showと同じ構成: メインカテゴリー全体を見る
 * ページ(index)と、サブカテゴリー1つに絞り込んだページ(show)の2つを用意し、
 * サブカテゴリーごとに登録したヒーロー画像・説明文がちゃんと出るようにしている。
 */
class MainCategoryPageController extends Controller
{
    /** メインカテゴリーの一覧ページ(URL: /information/{key}) */
    public function index(string $key)
    {
        return $this->render($key, null);
    }

    /** サブカテゴリー単位の絞り込みページ(URL: /information/{key}/{slug}) */
    public function show(string $key, string $slug)
    {
        // main_categoriesに登録が無いキーなら404(サブカテゴリー側のfirstOrFailより先に判定しておく)
        MainCategory::where('key', $key)->firstOrFail();

        $currentArea = Category::where('section', $key)
            ->where('slug', $slug)
            ->where('is_hidden', false)
            ->firstOrFail();

        return $this->render($key, $currentArea);
    }

    /**
     * index/showで共通の表示処理。
     * $currentAreaがnullなら「All(絞り込みなし)」、指定があればそのサブカテゴリーだけに絞り込む。
     */
    private function render(string $key, ?Category $currentArea)
    {
        // main_categoriesに登録が無いキーなら404
        $section = MainCategory::where('key', $key)->firstOrFail();

        $posts = ($currentArea
                ? Post::where('category_id', $currentArea->id)
                : Post::whereHas('category', fn ($q) => $q->where('section', $key))
            )
            ->withCount(['likes', 'comments'])
            ->with([
                'category',
                'user:id,name,avatar',
                'earthLocation',
                'likes' => fn ($q) => $q->where('user_id', auth()->id()),
                'bookmarks' => fn ($q) => $q->where('user_id', auth()->id()),
                'comments' => fn ($q) => $q->with('user:id,name')->oldest(),
            ])
            ->latest()
            ->get();

        // カテゴリー名 => 表示色(投稿カードのタグ色用)
        $categoryColors = Category::forSection($key)
            ->mapWithKeys(fn ($c) => [$c->name => $c->color()]);

        // サブカテゴリー一覧(STORE欄をDBから動的に表示するため)
        $subCategories = Category::forSection($key);

        // 上部の「メインカテゴリー一覧ボタン」用。既存4つ+今後追加された分すべて
        $allMainCategories = MainCategory::allOrdered();

        return view('information.dynamic.index', compact(
            'posts',
            'categoryColors',
            'section',
            'subCategories',
            'allMainCategories',
            'currentArea'
        ));
    }
}
