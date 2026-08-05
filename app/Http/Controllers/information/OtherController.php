<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;

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

        return view(
            'information.other.index',
            compact('posts', 'categoryColors')
        );
    }
}
