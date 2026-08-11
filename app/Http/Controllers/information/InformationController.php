<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformationController extends Controller
{
    /**
     * 投稿完了後・更新後・削除後に戻る「一覧ページ」のURLを、sectionから組み立てる。
     * 既存4つ(carinderia/restaurant-cafe/travel/other)は専用の名前付きルートへ、
     * それ以外(5個目以降、main_categoriesにadminが追加した分)は汎用ルート(information.dynamic)へ。
     */
    private function sectionIndexUrl(string $section): string
    {
        $fixedRoutes = [
            'carinderia'      => 'carinderia.index',
            'restaurant-cafe' => 'restaurant-cafe.index',
            'travel'          => 'travel.index',
            'other'           => 'other.index',
        ];

        return isset($fixedRoutes[$section])
            ? route($fixedRoutes[$section])
            : route('information.dynamic', $section);
    }

    // 投稿画面
    public function create()
    {
        $categories = Category::all();

        // メインカテゴリーの一覧(投稿フォームで「メイン→サブ」の2段階選択に使う)
        $mainCategories = MainCategory::allOrdered();

        return view('information.post', compact('categories', 'mainCategories'));
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
        $section = $post->category->section ?? 'restaurant-cafe';

        return redirect()
            ->to($this->sectionIndexUrl($section))
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

        // メインカテゴリーの一覧(編集フォームで「メイン→サブ」の2段階選択に使う)
        $mainCategories = MainCategory::allOrdered();

        // 投稿のカテゴリーからセクションを取得
        $section = $post->category->section ?? 'restaurant-cafe';

        return view('information.edit', compact('post', 'categories', 'mainCategories', 'section'));
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
            ->to($this->sectionIndexUrl($section))
            ->with('status', '投稿を更新しました。');
    }


    // ============================================================
    // 詳細ページ
    // ============================================================

    public function show(Post $post)
    {
        $post->load(['user', 'category']);
        $section = $post->category->section ?? 'restaurant-cafe';

        // Travelだけ投稿詳細が専用ページ(travel.post.show)なのでそちらへ
        if ($section === 'travel') {
            return redirect()->route('travel.post.show', $post);
        }

        // 5個目以降(main_categoriesにadminが追加した分)は専用の詳細ページが無いため、一覧ページへ戻す
        if (! in_array($section, ['carinderia', 'restaurant-cafe', 'other'], true)) {
            return redirect()->to($this->sectionIndexUrl($section));
        }

        return view("information.{$section}.show", compact('post'));
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

        $section = $post->category->section ?? 'restaurant-cafe';

        $post->delete();

        return redirect()
            ->to($this->sectionIndexUrl($section))
            ->with('status', '投稿を削除しました。');
    }
}
