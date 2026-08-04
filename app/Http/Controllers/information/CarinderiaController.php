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

    /**
     * 投稿詳細ページ(独立ページ)
     */
    public function show(Post $post)
    {
        $post->load(['user', 'category']);

        return view('information.carinderia.show', compact('post'));
    }

    /**
     * 編集フォーム表示
     */
    public function edit(Post $post)
    {
        abort_if($post->user_id !== auth()->id(), 403, 'この投稿を編集する権限がありません。');

        $categories = Category::forSection(self::SECTION);
        $section = self::SECTION;

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
            'image'       => ['nullable', 'image', 'max:5120'],
        ]);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validated);

        return redirect()
            ->route('carinderia.index')
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
            ->route('carinderia.index')
            ->with('status', '投稿を削除しました。');
    }
}
