<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\EarthLocation;
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


    // ============================================================
    // 場所追加前の投稿内容を一時保存
    // ============================================================

    public function saveDraft(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        // すでに保存されている下書きを取得
        $draft = session('information_draft', []);

        // 画像が選択されていた場合、一時保存
        if ($request->hasFile('image')) {

            // 古い一時画像があれば削除
            if (!empty($draft['image'])) {
                Storage::disk('public')->delete($draft['image']);
            }

            $draft['image'] = $request->file('image')
                ->store('drafts', 'public');
        }

        // テキスト系の入力内容を保存
        $draft['main_category'] = $request->input('main_category');
        $draft['category_id'] = $request->input('category_id');
        $draft['title'] = $request->input('title');
        $draft['description'] = $request->input('description');
        $draft['price'] = $request->input('price');

        session([
            'information_draft' => $draft,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }


    // ============================================================
    // 投稿画面
    // ============================================================

    public function create(Request $request)
    {
        $categories = Category::all();

        // メインカテゴリーの一覧
        $mainCategories = MainCategory::allOrdered();

        $earthLocation = null;

        if ($request->filled('earth_location_id')) {
            $earthLocation = EarthLocation::find($request->earth_location_id);
        }

         // 場所追加から戻ってきた場合だけ、一時保存した投稿データを取得
         $draft = [];

         if ($request->filled('earth_location_id')) {
               $draft = session('information_draft', []);
         }
         return view('information.post', compact(
            'categories',
            'mainCategories',
            'earthLocation',
            'draft'
        ));
    }


    // ============================================================
    // 投稿保存
    // ============================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'main_category' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['nullable', 'numeric'],
            'image'       => ['nullable', 'image', 'max:5120'],
            'earth_location_id' => ['nullable', 'exists:earth_locations,id'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('posts', 'public');
        }

        $validated['user_id'] = auth()->id();

        $post = Post::create($validated);

        // 位置情報が選択されていたら、投稿と紐づける
        if ($request->filled('earth_location_id')) {
            EarthLocation::where('id', $request->earth_location_id)
                ->update([
                    'post_id' =>

                     $post->id,
                ]);
        }

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

        // メインカテゴリーの一覧
        $mainCategories = MainCategory::allOrdered();

        // 投稿に紐づいている位置情報を取得
        $earthLocation = EarthLocation::where('post_id', $post->id)->first();

        // 投稿のカテゴリーからセクションを取得
        $section = $post->category->section ?? 'restaurant-cafe';

        return view(
            'information.edit',
            compact(
                'post',
                'categories',
                'mainCategories',
                'section',
                'earthLocation'
            )
        );
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
            'この投稿を編集する権限がありません。'
        );

        // 保存されている画像を削除
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $section = $post->category->section ?? 'restaurant-cafe';

        // 投稿に紐づいている位置情報も削除
        EarthLocation::where('post_id', $post->id)->delete();

        $post->delete();

        return redirect()
            ->to($this->sectionIndexUrl($section))
            ->with('status', '投稿を削除しました。');
    }
}
