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
            // 隠しカテゴリー(egg / St Ninoなど)の投稿は、通常のOther一覧には出さない
            'category',
            fn ($q) => $q->where('section', self::SECTION)->where('is_hidden', false)
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
                fn ($c) => [$c->name => ['bg' => $c->backgroundColor(), 'text' => $c->textColor()]]
            );

        // このページ自体(メインカテゴリー)のヒーロー画像・タイトル・説明文
        $section = MainCategory::findByKey(self::SECTION);

        // サブカテゴリー一覧(STORE欄をDBから動的に表示するため)
        $subCategories = Category::forSection(self::SECTION);

        // 上部の「メインカテゴリー一覧ボタン」用。既存4つ+今後追加された分すべて
        $allMainCategories = MainCategory::allOrdered();

        // URLの ?category=◯◯ でサブカテゴリーの選択状態を復元する(リロードしてもAllに戻らないようにするため)。
        // ルート自体は増やさずクエリパラメータだけなので他のページには影響しない。
        $initialCategory = request()->query('category');
        if ($initialCategory && !$subCategories->contains('name', $initialCategory)) {
            $initialCategory = null;
        }

        return view(
            'information.other.index',
            compact('posts', 'categoryColors', 'section', 'subCategories', 'allMainCategories', 'initialCategory')
        );
    }

    /**
     * お楽しみ機能: 隠しリンク専用ページ(egg / St Nino)。
     * 通常のOther一覧には出てこない隠しカテゴリー(is_hidden=true)の投稿だけを表示する。
     */
    public function secret(string $slug)
    {
        $category = Category::findHiddenBySlug(self::SECTION, $slug);

        abort_if(!$category, 404);

        $posts = Post::where('category_id', $category->id)
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

        // 通常ページと同じ、上部のメインカテゴリー一覧ボタン用
        $allMainCategories = MainCategory::allOrdered();

        // カード上のタグバッジ色(egg=黄色 / St Nino=ボルドー。通常ページのカテゴリー色ロジックとは別の特別配色)
        $badgeColor = $category->slug === 'egg' ? '#F0C419' : '#6E0F1A';
        $badgeBgColor = Category::lighten($badgeColor, 0.88);

        return view('information.other.secret', compact('posts', 'category', 'allMainCategories', 'badgeColor', 'badgeBgColor'));
    }
}
