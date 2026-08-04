<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;

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
                'user:id,name',
                'likes' => fn ($q) => $q->where('user_id', auth()->id()),
                'bookmarks' => fn ($q) => $q->where('user_id', auth()->id()),
                'comments' => fn ($q) => $q->with('user:id,name')->oldest(),
            ])
            ->latest()
            ->get();

        // Restaurant & Cafe のカテゴリー表示色
        $categoryColors = Category::forSection('restaurant-cafe')
            ->mapWithKeys(fn ($c) => [$c->name => $c->color()]);

        return view(
            'information.restaurant-cafe.index',
            compact('posts', 'categoryColors')
        );
    }
}
