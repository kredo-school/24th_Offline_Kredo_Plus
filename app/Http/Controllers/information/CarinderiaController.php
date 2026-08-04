<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;

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

        return view('information.carinderia.index', compact('posts', 'categoryColors'));
    }
}
