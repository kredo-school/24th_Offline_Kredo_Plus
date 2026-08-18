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
 */
class MainCategoryPageController extends Controller
{
    /** メインカテゴリーの一覧ページ(URL: /information/{key}) */
    public function index(string $key)
    {
        // main_categoriesに登録が無いキーなら404
        $section = MainCategory::where('key', $key)->firstOrFail();

        $posts = Post::whereHas(
            'category',
            fn ($q) => $q->where('section', $key)
        )
            ->withCount(['likes', 'comments'])
            ->with([
                'category',
                'user:id,name',
                'earthLocation',
                'likes' => fn ($q) => $q->where('user_id', auth()->id()),
                'bookmarks' => fn ($q) => $q->where('user_id', auth()->id()),
                'comments' => fn ($q) => $q->with('user:id,name')->oldest(),
            ])
            ->latest()
            ->get();

        // カテゴリー名 => 表示色
        $categoryColors = Category::forSection($key)
            ->mapWithKeys(fn ($c) => [$c->name => $c->color()]);

        // サブカテゴリー一覧(STORE欄をDBから動的に表示するため)
        $subCategories = Category::forSection($key);

        // 上部の「メインカテゴリー一覧ボタン」用。既存4つ+今後追加された分すべて
        $allMainCategories = MainCategory::allOrdered();

        // URLの ?category=◯◯ でサブカテゴリーの選択状態を復元する(リロードしてもAllに戻らないようにするため)。
        // ルート自体は増やさずクエリパラメータだけなので他のページには影響しない。
        $initialCategory = request()->query('category');
        if ($initialCategory && !$subCategories->contains('name', $initialCategory)) {
            $initialCategory = null;
        }

        return view('information.dynamic.index', compact(
            'posts',
            'categoryColors',
            'section',
            'subCategories',
            'allMainCategories',
            'initialCategory'
        ));
    }
}
