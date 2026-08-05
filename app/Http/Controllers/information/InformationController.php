<?php
namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\EarthLocation;

class InformationController extends Controller
{
    // 投稿画面
    public function create(Request $request)
    {
        $categories = Category::all();

        $earthLocation = null;

        if ($request->filled('earth_location_id')) {
            $earthLocation = EarthLocation::find($request->earth_location_id);
        }

        return view('information.post', compact(
            'categories',
            'earthLocation'
        ));
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

        if ($request->filled('earth_location_id')) {

            $earthLocation = EarthLocation::find($request->earth_location_id);

            if ($earthLocation) {

                $earthLocation->post_id = $post->id;

                $earthLocation->save();

            }

}

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
}
