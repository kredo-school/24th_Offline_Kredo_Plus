<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * 留学情報管理(管理画面): メインカテゴリー・サブカテゴリーの新規追加/編集をまとめて扱う。
 * 「ポスト管理」タブを差し替えたもの。myu担当。
 */
class AdminCategoryController extends Controller
{
    /**
     * メインカテゴリー 新規追加
     */
    public function storeMainCategory(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('addMain', [
            'key' => ['required', 'string', 'max:50', 'regex:/^[a-z0-9\-]+$/', 'unique:main_categories,key'],
            'name' => ['required', 'string', 'max:255'],
            'hero_image' => ['nullable', 'image', 'max:5120'],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $nextSortOrder = (int) MainCategory::max('sort_order') + 1;

        MainCategory::create([
            'key' => $validated['key'],
            'name' => $validated['name'],
            'hero_image' => $this->storeHeroImage($request, 'main_categories'),
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?: null,
            'sort_order' => $nextSortOrder,
        ]);

        return back()->with('categoryAdminNotice', [
            'type' => 'main_created',
            'message' => "メインカテゴリー「{$validated['name']}」を追加しました。",
        ]);
    }

    /**
     * メインカテゴリー 編集
     * (keyは各所で識別子として使われているため編集不可。それ以外を更新する)
     */
    public function updateMainCategory(Request $request, MainCategory $mainCategory): RedirectResponse
    {
        $validated = $request->validateWithBag('editMain', [
            'name' => ['required', 'string', 'max:255'],
            'hero_image' => ['nullable', 'image', 'max:5120'],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $mainCategory->update([
            'name' => $validated['name'],
            'hero_image' => $this->storeHeroImage($request, 'main_categories') ?? $mainCategory->hero_image,
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?: null,
        ]);

        return back()->with('categoryAdminNotice', [
            'type' => 'main_updated',
            'message' => "メインカテゴリー「{$mainCategory->name}」を更新しました。",
        ]);
    }

    /**
     * メインカテゴリー 削除
     * 配下にサブカテゴリーが残っている場合は、通常は削除させない。
     * 画面で「中身ごと完全に削除する」にチェックが入っている(force=1)場合のみ、
     * 配下のサブカテゴリー(とそこに紐づく投稿。posts.category_idはcascadeOnDelete)ごと削除する。
     */
    public function destroyMainCategory(Request $request, MainCategory $mainCategory): RedirectResponse
    {
        $subCategories = Category::where('section', $mainCategory->key)->get();
        $subCount = $subCategories->count();
        $force = $request->boolean('force');

        if ($subCount > 0 && !$force) {
            $postCount = Post::whereIn('category_id', $subCategories->pluck('id'))->count();

            return back()->with('categoryAdminNotice', [
                'type' => 'error',
                'message' => "「{$mainCategory->name}」には{$subCount}件のサブカテゴリー(投稿{$postCount}件を含む)が残っています。中身ごと削除する場合は「中身ごと完全に削除する」にチェックを入れてから削除してください。",
            ]);
        }

        $name = $mainCategory->name;

        if ($subCount > 0) {
            Category::where('section', $mainCategory->key)->delete();
        }

        $mainCategory->delete();

        return back()->with('categoryAdminNotice', [
            'type' => 'main_deleted',
            'message' => $subCount > 0
                ? "メインカテゴリー「{$name}」とサブカテゴリー{$subCount}件をまとめて削除しました。"
                : "メインカテゴリー「{$name}」を削除しました。",
        ]);
    }

    /**
     * サブカテゴリー 新規追加
     * slugはnameから自動生成し、重複する場合は末尾に連番を付ける。
     */
    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('addSub', [
            'section' => ['required', 'string', 'exists:main_categories,key'],
            'name' => ['required', 'string', 'max:255'],
            'hero_image' => ['nullable', 'image', 'max:5120'],
            'description' => ['nullable', 'string'],
        ]);

        $slug = $this->uniqueSlug($validated['name']);

        $nextSortOrder = (int) Category::where('section', $validated['section'])->max('sort_order') + 1;

        Category::create([
            'section' => $validated['section'],
            'name' => $validated['name'],
            'slug' => $slug,
            'hero_image' => $this->storeHeroImage($request, 'categories'),
            'description' => $validated['description'] ?? null,
            'sort_order' => $nextSortOrder,
            'is_hidden' => false,
        ]);

        return back()->with('categoryAdminNotice', [
            'type' => 'sub_created',
            'message' => "サブカテゴリー「{$validated['name']}」を追加しました。",
        ]);
    }

    /**
     * サブカテゴリー 編集
     * 所属メインカテゴリー(section)の変更にも対応する。
     */
    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validateWithBag('editSub', [
            'section' => ['required', 'string', 'exists:main_categories,key'],
            'name' => ['required', 'string', 'max:255'],
            'hero_image' => ['nullable', 'image', 'max:5120'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update([
            'section' => $validated['section'],
            'name' => $validated['name'],
            'hero_image' => $this->storeHeroImage($request, 'categories') ?? $category->hero_image,
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('categoryAdminNotice', [
            'type' => 'sub_updated',
            'message' => "サブカテゴリー「{$category->name}」を更新しました。",
        ]);
    }

    /**
     * サブカテゴリー 削除
     * 投稿(posts)が残っている場合は、通常は削除させない。
     * 画面で「中身ごと完全に削除する」にチェックが入っている(force=1)場合のみ、
     * 投稿ごと削除する(posts.category_idはcascadeOnDeleteなのでDB側で自動的に消える)。
     */
    public function destroyCategory(Request $request, Category $category): RedirectResponse
    {
        $postCount = Post::where('category_id', $category->id)->count();
        $force = $request->boolean('force');

        if ($postCount > 0 && !$force) {
            return back()->with('categoryAdminNotice', [
                'type' => 'error',
                'message' => "「{$category->name}」には{$postCount}件の投稿が残っています。中身ごと削除する場合は「中身ごと完全に削除する」にチェックを入れてから削除してください。",
            ]);
        }

        $name = $category->name;
        $category->delete();

        return back()->with('categoryAdminNotice', [
            'type' => 'sub_deleted',
            'message' => $postCount > 0
                ? "サブカテゴリー「{$name}」と投稿{$postCount}件をまとめて削除しました。"
                : "サブカテゴリー「{$name}」を削除しました。",
        ]);
    }

    /**
     * アップロードされたヒーロー画像をstorageディスクに保存し、公開URLを返す。
     * ファイルが送信されていなければnullを返す(編集時は既存の画像を維持する)。
     */
    private function storeHeroImage(Request $request, string $folder): ?string
    {
        if (!$request->hasFile('hero_image')) {
            return null;
        }

        $path = $request->file('hero_image')->store($folder, 'public');

        return Storage::url($path);
    }

    /**
     * 名前からslugを生成し、既存と重複する場合は -2, -3 ... と連番を付けて一意にする。
     */
    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'category';
        $slug = $base;
        $i = 2;

        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
