<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\MainCategory;

class TravelController extends Controller
{
    /** categoriesテーブルのsectionカラムと一致させる。
     */
    private const SECTION = 'travel';

    /**  全エリア一覧（絞り込みなし）
     * サイドバーに表示するエリア一覧と Travelの投稿一覧を取得する。
     * 投稿の編集・更新・削除・詳細は InformationControllerで一括管理する。
     */
    public function index()
    {
        $areas = Category::forSection(self::SECTION);

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

        return view('information.travel.show', [
            'areas' => $areas,
            'currentArea' => null,
            // Travelページ自体(メインカテゴリー)のヒーロー。エリア未選択時はこちらを表示
            'section' => MainCategory::findByKey(self::SECTION),
            'posts' => $posts,
            // 上部の「メインカテゴリー一覧ボタン」用。既存4つ+今後追加された分すべて
            'allMainCategories' => MainCategory::allOrdered(),
        ]);
    }

    /** カテゴリー（エリア）ごとの絞り込み表示。
     */
    public function show(string $slug)
    {
        $currentArea = Category::where('section', self::SECTION)
            ->where('slug', $slug)
            ->firstOrFail();

        $areas = Category::forSection(self::SECTION);

        $posts = Post::where('category_id', $currentArea->id)
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

        return view('information.travel.show', [
            'areas' => $areas,
            'currentArea' => $currentArea,
            // エリア選択時は$currentAreaのヒーローが優先されるが、変数自体は渡しておく
            'section' => MainCategory::findByKey(self::SECTION),
            'posts' => $posts,
            // 上部の「メインカテゴリー一覧ボタン」用。既存4つ+今後追加された分すべて
            'allMainCategories' => MainCategory::allOrdered(),
        ]);
    }

    /** Travel投稿の詳細ページ。
      Travelのエリア絞り込みページとは別のページ。
     */
    public function showPost(Post $post)
    {
        $post->load(['user', 'category']);

        return view('information.travel.post-show', compact('post'));
    }
}
