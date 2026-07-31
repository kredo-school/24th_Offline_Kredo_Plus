<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestaurantCafeController extends Controller
{
    /**
     * Restaurant & Cafe 一覧ページ
     *
     * 将来DB接続する時:
     *   $posts = Post::whereIn('category', ['Restaurant', 'Cafe'])
     *                ->with(['user', 'comments.user'])
     *                ->latest()
     *                ->get();
     *0710追記
     * * ⚠️ 現状は仮データ(配列)のままです。
     * edit / update / destroy は posts テーブル(Eloquent)を前提にしているため、
     * index も Post::with(['user','category'])->... のようにDB化しないと
     * 一覧 → 編集画面への導線(post.id によるルートモデルバインディング)が繋がりません。
     * 一覧側の担当者と認識合わせをお願いします。
     */

    public function index()
    {
        // ---- Restaurant ----
        // ⚠️ カテゴリー(restaurant-cafe)による絞り込みは未実装(投稿担当に確認中のため保留)
        $posts = Post::withCount(['likes', 'comments'])
            ->with([
                'category',
                'user:id,name',
                'likes' => fn ($q) => $q->where('user_id', auth()->id()),
                'bookmarks' => fn ($q) => $q->where('user_id', auth()->id()),
                'comments' => fn ($q) => $q->with('user:id,name')->oldest(),
            ])
            ->latest()
            ->get();

        // カテゴリー名 => 表示色(Category::color()と同じロジック)。JSのバッジ色に使う。
        $categoryColors = Category::forSection('restaurant-cafe')->mapWithKeys(fn ($c) => [$c->name => $c->color()]);

        return view('information.restaurant-cafe.index', compact('posts', 'categoryColors'));
    }

    /**
     * 投稿詳細ページ(独立ページ)
     */
    public function show(Post $post)
    {
        $post->load(['user', 'category']);

        return view('information.restaurant-cafe.show', compact('post'));
    }

    /**
     * 編集フォーム表示 * ルートモデルバインディングで posts.id から Post を自動取得
     */
    public function edit(Post $post)
    {
        // 投稿者本人以外はアクセス不可
        abort_if($post->user_id !== auth()->id(), 403, 'この投稿を編集する権限がありません。');

        // restaurant-cafe セクションのカテゴリー一覧(色付き選択チップ用)
        $categories = Category::forSection('restaurant-cafe');
        $section = 'restaurant-cafe';

        return view('information.edit', compact('post', 'categories', 'section'));
    }

    /**
     * 更新処理
     */
    public function update(Request $request, Post $post)
    {
        abort_if($post->user_id !== auth()->id(), 403, 'この投稿を編集する権限がありません。');

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price'       => ['nullable', 'numeric', 'min:0'],
            'image'       => ['nullable', 'image', 'max:5120'], // 5MBまで
        ]);

        // 新しい画像がアップロードされた場合だけ差し替え
        if ($request->hasFile('image')) {
            // 古い画像を削除(あれば)
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validated);

        return redirect()
            ->route('restaurant-cafe.index')
            ->with('status', '投稿を更新しました。');
    }

    /**
     * 削除処理
     */
    public function destroy(Post $post)
    {
        abort_if($post->user_id !== auth()->id(), 403, 'この投稿を削除する権限がありません。');

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()
            ->route('restaurant-cafe.index')
            ->with('status', '投稿を削除しました。');
    }
}
