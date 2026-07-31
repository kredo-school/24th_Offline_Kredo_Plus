<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OtherController extends Controller
{
    /**
     * カテゴリーのsection名(categoriesテーブルのsectionカラムと一致させる)
     */
    private const SECTION = 'other';

    /**
     * Other一覧ページ(Laundry / Money Exchange / SIM Card / Hospital / Others)
     * RestaurantCafeControllerと同じ形にDB化。
     */
    public function index()
    {
        $posts = Post::whereHas('category', fn ($q) => $q->where('section', self::SECTION))
            ->withCount(['likes', 'comments'])
            ->with([
                'category',
                'user:id,name',
                // 今ログインしている本人のいいね/保存だけ読み込む(post->liked_by_me等の判定に使う。N+1防止)
                'likes' => fn ($q) => $q->where('user_id', auth()->id()),
                'bookmarks' => fn ($q) => $q->where('user_id', auth()->id()),
                'comments' => fn ($q) => $q->with('user:id,name')->oldest(),
            ])
            ->latest()
            ->get();

        // カテゴリー名 => 表示色(Category::color()と同じロジック)。JSのバッジ色に使う。
        $categoryColors = Category::forSection(self::SECTION)->mapWithKeys(fn ($c) => [$c->name => $c->color()]);

        return view('information.other.index', compact('posts', 'categoryColors'));
    }

    /**
     * 投稿詳細ページ(独立ページ)
     */
    public function show(Post $post)
    {
        $post->load(['user', 'category']);

        return view('information.other.show', compact('post'));
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
            ->route('other.index')
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
            ->route('other.index')
            ->with('status', '投稿を削除しました。');
    }
}
