<?php
namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

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
        // ⚠️ categoriesテーブルの値は 'carinderia'(業種として正しい綴り)だが、
        // ルート名/Controller名は 'carenderia'(既存コードの綴り)のため対応表が必要。
        $sectionToRoute = [
            'carinderia'      => 'carenderia.index',
            'restaurant-cafe' => 'restaurant-cafe.index',
            'travel'          => 'travel.index',
            'other'           => 'other.index',
        ];
        $routeName = $sectionToRoute[$post->category->section ?? ''] ?? 'restaurant-cafe.index';

        return redirect()
            ->route($routeName)
            ->with('status', '投稿しました。');
    }
}
