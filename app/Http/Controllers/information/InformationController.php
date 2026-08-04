<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformationController extends Controller
{
    // 投稿画面
    public function create()
    {
        $categories = Category::all();

        return view('information.post', compact('categories'));
    }

    // 投稿保存
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['nullable', 'numeric'],
            'image'       => ['nullable', 'image', 'max:5120'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $validated['user_id'] = auth()->id();

        $post = Post::create($validated);

        // 投稿したカテゴリーのsectionに応じて、正しい一覧ページへ戻す。
        $sectionToRoute = [
            'carinderia'      => 'carinderia.index',
            'restaurant-cafe' => 'restaurant-cafe.index',
            'travel'          => 'travel.index',
            'other'           => 'other.index',
        ];
        $routeName = $sectionToRoute[$post->category->section ?? ''] ?? 'restaurant-cafe.index';

        return redirect()
            ->route($routeName)
            ->with('status', '投稿しました。');
    }

    // ============================================================
    // 編集画面
    // ============================================================

    public function edit(Post $post)
    {
        // 投稿者本人以外は編集できない
        abort_if(
            $post->user_id !== auth()->id(),
            403,
            'この投稿を編集する権限がありません。'
        );

        $categories = Category::all();

        // 投稿のカテゴリーからセクションを取得
        $section = $post->category->section ?? 'restaurant-cafe';

        return view('information.edit', compact('post', 'categories', 'section'));
    }


    // ============================================================
    // 更新処理
    // ============================================================

    public function update(Request $request, Post $post)
    {
        // 投稿者本人以外は更新できない
        abort_if(
            $post->user_id !== auth()->id(),
            403,
            'この投稿を編集する権限がありません。'
        );

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price'       => ['nullable', 'numeric', 'min:0'],
            'image'       => ['nullable', 'image', 'max:5120'],
        ]);

        // 新しい画像がアップロードされた場合
        if ($request->hasFile('image')) {

            // 古い画像を削除
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $validated['image'] = $request->file('image')
                ->store('posts', 'public');
        }

        $post->update($validated);
        $section = $post->category->section ?? 'restaurant-cafe';

        return redirect()
            ->route($section . '.index')
            ->with('status', '投稿を更新しました。');
    }


    // ============================================================
    // 詳細ページ
    // ============================================================

    public function show(Post $post)
    {
        $post->load(['user', 'category']);
        return view('information.restaurant-cafe.show', compact('post'));
    }


    // ============================================================
    // 削除処理
    // ============================================================

    public function destroy(Post $post)
    {
        // 投稿者本人以外は削除できない
        abort_if(
            $post->user_id !== auth()->id(),
            403,
            'この投稿を削除する権限がありません。'
        );

        // 保存されている画像を削除
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()
            ->route('restaurant-cafe.index')
            ->with('status', '投稿を削除しました。');
    }
}
