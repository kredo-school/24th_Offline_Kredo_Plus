<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\MainCategory;

class RestaurantCafeController extends Controller
{
    /**
     * Restaurant & Cafe 一覧ページ
     * 投稿の編集・更新・削除・詳細は InformationController で一括管理する。
     */
    public function index()
    {
        // ---- Restaurant & Cafe セクションの投稿だけに絞り込む ----
        $posts = Post::whereHas('category', fn ($q) => $q->where('section', 'restaurant-cafe'))
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

        // Restaurant & Cafe のカテゴリー表示色
        $categoryColors = Category::forSection('restaurant-cafe')
            ->mapWithKeys(fn ($c) => [$c->name => $c->color()]);

        // このページ自体(メインカテゴリー)のヒーロー画像・タイトル・説明文
        $section = MainCategory::findByKey('restaurant-cafe');

        // サブカテゴリー一覧(STORE欄をDBから動的に表示するため)
        $subCategories = Category::forSection('restaurant-cafe');

        // 上部の「メインカテゴリー一覧ボタン」用。既存4つ+今後追加された分すべて
        $allMainCategories = MainCategory::allOrdered();

        // URLの ?category=◯◯ でサブカテゴリーの選択状態を復元する(リロードしてもAllに戻らないようにするため)。
        // ルート自体は増やさずクエリパラメータだけなので他のページには影響しない。
        $initialCategory = request()->query('category');
        if ($initialCategory && !$subCategories->contains('name', $initialCategory)) {
            $initialCategory = null;
        }

        return view(
            'information.restaurant-cafe.index',
            compact('posts', 'categoryColors', 'section', 'subCategories', 'allMainCategories', 'initialCategory')
        );
    }
}
