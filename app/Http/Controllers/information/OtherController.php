<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\MainCategory;

class OtherController extends Controller
{
    /**カテゴリーのsection名
     * categoriesテーブルのsectionカラムと一致させる
     */
    private const SECTION = 'other';

    /** Other一覧ページ
     * Laundry / Money Exchange / SIM Card / Hospital / Others
     */
    public function index()
    {
        $posts = Post::whereHas(
            'category',
            fn ($q) => $q->where('section', self::SECTION)
        )
            ->withCount(['likes', 'comments'])
            ->with([
                'category',
                'user:id,name',
                
                // 投稿に紐づいた位置情報
                'earthLocation',

                // ログイン中ユーザー自身のいいね
                'likes' => fn ($q) => $q->where('user_id', auth()->id()),

                // ログイン中ユーザー自身のお気に入り
                'bookmarks' => fn ($q) => $q->where('user_id', auth()->id()),

                // コメントとコメント投稿者
                'comments' => fn ($q) => $q
                    ->with('user:id,name')
                    ->oldest(),
            ])
            ->latest()
            ->get();

        // カテゴリー名ごとの表示色
        $categoryColors = Category::forSection(self::SECTION)
            ->mapWithKeys(
                fn ($c) => [$c->name => $c->color()]
            );

        // このページ自体(メインカテゴリー)のヒーロー画像・タイトル・説明文
        $section = MainCategory::findByKey(self::SECTION);

        // サブカテゴリー一覧(STORE欄をDBから動的に表示するため)
        $subCategories = Category::forSection(self::SECTION);

        // 上部の「メインカテゴリー一覧ボタン」用。既存4つ+今後追加された分すべて
        $allMainCategories = MainCategory::allOrdered();

        return view(
            'information.other.index',
            compact('posts', 'categoryColors', 'section', 'subCategories', 'allMainCategories')
        );
    }
}
