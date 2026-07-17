<?php

namespace App\Http\Controllers;

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
    public function index(Request $request)
    {
        $posts = [
            // ---- Restaurant ----
            [
                'tag' => 'Restaurant',
                'title' => 'Sunset Grill House',
                'desc' => 'Steaks, seafood, and a sunset view perfect for celebrations.',
                'price' => 450,
                'name' => 'Mateo L.',
                'time' => '2時間前',
                'likes' => 41,
                'img' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=12',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
            [
                'tag' => 'Restaurant',
                'title' => 'Ramen Ichiban',
                'desc' => 'Rich tonkotsu broth and handmade noodles, Japanese comfort food.',
                'price' => 280,
                'name' => 'Sophia C.',
                'time' => '4時間前',
                'likes' => 63,
                'img' => 'https://images.unsplash.com/photo-1591814468924-caf88d1232e1?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=32',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
            [
                'tag' => 'Restaurant',
                'title' => 'Pizzeria Napoli',
                'desc' => 'Wood-fired Neapolitan pizza with imported Italian ingredients.',
                'price' => 350,
                'name' => 'Juan R.',
                'time' => '6時間前',
                'likes' => 37,
                'img' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=51',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
            [
                'tag' => 'Restaurant',
                'title' => 'Korean BBQ House',
                'desc' => 'All-you-can-eat grilled meats with banchan side dishes.',
                'price' => 599,
                'name' => 'Angela M.',
                'time' => '1日前',
                'likes' => 55,
                'img' => 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=25',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
            [
                'tag' => 'Restaurant',
                'title' => 'Bistro Cebu',
                'desc' => 'Modern Filipino fusion in a cozy, candle-lit setting.',
                'price' => 480,
                'name' => 'Daniel L.',
                'time' => '1日前',
                'likes' => 19,
                'img' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=15',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],

            // ---- Cafe ----
            [
                'tag' => 'Cafe',
                'title' => 'Brew & Books Corner',
                'desc' => 'Quiet study nooks with unlimited refills and fast wifi.',
                'price' => 150,
                'name' => 'Kim C.',
                'time' => '1時間前',
                'likes' => 58,
                'img' => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=44',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
            [
                'tag' => 'Cafe',
                'title' => 'Matcha House Cebu',
                'desc' => 'Specialty matcha lattes and Japanese-style desserts.',
                'price' => 180,
                'name' => 'Ella R.',
                'time' => '3時間前',
                'likes' => 44,
                'img' => 'https://images.unsplash.com/photo-1515823064-d6e0c04616a7?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=5',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
            [
                'tag' => 'Cafe',
                'title' => 'Third Wave Coffee Lab',
                'desc' => 'Single-origin pour-overs from local roasters, coffee nerd approved.',
                'price' => 160,
                'name' => 'Marco T.',
                'time' => '5時間前',
                'likes' => 39,
                'img' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=8',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
            [
                'tag' => 'Cafe',
                'title' => 'Rooftop View Cafe',
                'desc' => 'City skyline views with your afternoon iced coffee.',
                'price' => 190,
                'name' => 'Rina P.',
                'time' => '1日前',
                'likes' => 61,
                'img' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=20',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
            [
                'tag' => 'Cafe',
                'title' => 'Cat Cafe Purrfect',
                'desc' => 'Sip your latte while cuddling resident cafe cats.',
                'price' => 200,
                'name' => 'Josh T.',
                'time' => '2日前',
                'likes' => 72,
                'img' => 'https://images.unsplash.com/photo-1526336024174-e58f5cdd8e13?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=33',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
        ];

        return view('information.restaurant-cafe.index', compact('posts'));
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
     * 編集フォーム表示
     * ルートモデルバインディングで posts.id から Post を自動取得
     */
    public function edit(Post $post)
    {
        // 投稿者本人以外はアクセス不可
        abort_if($post->user_id !== auth()->id(), 403, 'この投稿を編集する権限がありません。');

        // restaurant-cafe セクションのカテゴリー一覧(色付き選択チップ用)
        $categories = Category::forSection('restaurant-cafe');

        return view('information.restaurant-cafe.edit', compact('post', 'categories'));
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
