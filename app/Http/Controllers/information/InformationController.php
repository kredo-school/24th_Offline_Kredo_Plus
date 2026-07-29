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

        Post::create($validated);

        return redirect()
            ->route('restaurant-cafe.index')
            ->with('status', '投稿しました。');
    }
}
