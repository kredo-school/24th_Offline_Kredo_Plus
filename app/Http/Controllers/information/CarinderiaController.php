<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\MainCategory;

class CarinderiaController extends Controller
{
    /**
     * カテゴリーのsection名(categoriesテーブルのsectionカラムと一致させる)。
     * ルート名・Controller名・ビューフォルダ・DBすべて正式な綴り(carinderia)で統一。
     */
    private const SECTION = 'carinderia';

    /** Carinderia 一覧ページ
     * 投稿の編集・更新・削除・詳細は InformationControllerで一括管理する。
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
                // ログイン中のユーザーのいいねだけ取得
                'likes' => fn ($q) => $q->where('user_id', auth()->id()),
                // ログイン中のユーザーのブックマークだけ取得
                'bookmarks' => fn ($q) => $q->where('user_id', auth()->id()),
                // コメントとコメント投稿者を取得
                'comments' => fn ($q) => $q->with('user:id,name')->oldest(),
            ])
            ->latest()
            ->get();

        // カテゴリー名 => 表示色
        $categoryColors = Category::forSection(self::SECTION)
            ->mapWithKeys(fn ($c) => [$c->name => $c->color()]);

        // このページ自体(メインカテゴリー)のヒーロー画像・タイトル・説明文
        $section = MainCategory::findByKey(self::SECTION);

        // サブカテゴリー一覧(STORE欄をDBから動的に表示するため)
        $subCategories = Category::forSection(self::SECTION);

        // 上部の「メインカテゴリー一覧ボタン」用。既存4つ+今後追加された分すべて
        $allMainCategories = MainCategory::allOrdered();

        // URLの ?category=◯◯ でサブカテゴリーの選択状態を復元する(リロードしてもAllに戻らないようにするため)。
        // ルート自体は増やさずクエリパラメータだけなので他のページには影響しない。
        // 実在するサブカテゴリー名と完全一致しない値は無視してAll扱いにする。
        $initialCategory = request()->query('category');
        if ($initialCategory && !$subCategories->contains('name', $initialCategory)) {
            $initialCategory = null;
        }

        return view('information.carinderia.index', compact('posts', 'categoryColors', 'section', 'subCategories', 'allMainCategories', 'initialCategory'));
    }
}
