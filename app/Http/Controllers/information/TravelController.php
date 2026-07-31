<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TravelController extends Controller
{
    /**
     * カテゴリーのsection名(categoriesテーブルのsectionカラムと一致させる)
     */
    private const SECTION = 'travel';

    /**
     * 全エリア一覧(絞り込みなし)
     * サイドバーに出すカテゴリー一覧は categories テーブルから動的に取得。
     */
    public function index(Request $request)
    {
        $areas = Category::forSection(self::SECTION);

        $posts = Post::whereHas('category', fn ($q) => $q->where('section', self::SECTION))
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

        return view('information.travel.show', [
            'areas' => $areas,
            'currentArea' => null, // 全エリア表示中は「選択中カテゴリーなし」
            'posts' => $posts,
        ]);
    }

    /**
     * カテゴリー(エリア)ごとの絞り込み表示。
     */
    public function show(Request $request, string $slug)
    {
        $currentArea = Category::where('section', self::SECTION)
            ->where('slug', $slug)
            ->firstOrFail(); // 存在しないslugなら自動的に404

        $areas = Category::forSection(self::SECTION);

        $posts = Post::where('category_id', $currentArea->id)
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

        return view('information.travel.show', [
            'areas' => $areas,
            'currentArea' => $currentArea,
            'posts' => $posts,
        ]);
    }

    /**
     * 投稿詳細ページ(独立ページ)
     * ルート名: travel.post.show (travel.show は上記のエリア絞り込みで使用済みのため別名)
     */
    public function showPost(Post $post)
    {
        $post->load(['user', 'category']);

        return view('information.travel.post-show', compact('post'));
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
            ->route('travel.index')
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
            ->route('travel.index')
            ->with('status', '投稿を削除しました。');
    }
}
