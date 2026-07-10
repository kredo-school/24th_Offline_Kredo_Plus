<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class TravelController extends Controller
{
    /**
     * 全スポットのダミーデータ(将来はPostモデルに置き換え)
     *
     *   $posts = Post::where('category_id', $category->id)->latest()->get();
     */
    private function allSpots(): array
    {
        return [
            // ---- IT Park ----
            [
                'tag' => 'IT Park', 'title' => 'IT Park Night Market',
                'desc' => 'Trendy bars, restaurants, and live music right in the city center.',
                'name' => 'Mika S.', 'time' => '2時間前', 'likes' => 22,
                'img' => 'https://images.unsplash.com/photo-1519892300165-cb5542fb47c7?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=25', 'mapQuery' => 'Orange UCMA', 'comments' => [],
            ],
            [
                'tag' => 'IT Park', 'title' => 'Crown Regency Sky Experience',
                'desc' => 'Adrenaline-pumping edge coaster and sky walk above the city.',
                'name' => 'Leo T.', 'time' => '5時間前', 'likes' => 30,
                'img' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=11', 'mapQuery' => 'Orange UCMA', 'comments' => [],
            ],

            // ---- North Area ----
            [
                'tag' => 'North Area', 'title' => 'Sirao Flower Garden',
                'desc' => "Colorful flower fields in the highlands, Cebu's little Amsterdam.",
                'name' => 'Grace P.', 'time' => '1日前', 'likes' => 40,
                'img' => 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=32', 'mapQuery' => 'Orange UCMA', 'comments' => [],
            ],
            [
                'tag' => 'North Area', 'title' => 'Temple of Leah',
                'desc' => 'A grand Roman-inspired temple overlooking the city, built as a symbol of love.',
                'name' => 'Carlo M.', 'time' => '2日前', 'likes' => 27,
                'img' => 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=14', 'mapQuery' => 'Orange UCMA', 'comments' => [],
            ],
            [
                'tag' => 'North Area', 'title' => 'Bantayan Island Beach',
                'desc' => 'Powdery white sand and calm turquoise waters, perfect for a day trip.',
                'name' => 'Ella R.', 'time' => '3日前', 'likes' => 48,
                'img' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=5', 'mapQuery' => 'Orange UCMA', 'comments' => [],
            ],

            // ---- South Area ----
            [
                'tag' => 'South Area', 'title' => 'Whale Shark Watching, Oslob',
                'desc' => 'Swim alongside gentle giants in the clear waters of Oslob.',
                'name' => 'Dave K.', 'time' => '3時間前', 'likes' => 65,
                'img' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=51', 'mapQuery' => 'Orange UCMA', 'comments' => [],
            ],
            [
                'tag' => 'South Area', 'title' => 'Kawasan Falls Canyoneering',
                'desc' => 'Cliff jumps and turquoise waterfalls through a full-day canyoneering trail.',
                'name' => 'Trisha K.', 'time' => '6時間前', 'likes' => 52,
                'img' => 'https://images.unsplash.com/photo-1546587348-d12660c30c50?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=37', 'mapQuery' => 'Orange UCMA', 'comments' => [],
            ],
            [
                'tag' => 'South Area', 'title' => 'Osmeña Peak Sunrise Trek',
                'desc' => 'The "Little Batanes" of Cebu — rolling hills perfect for sunrise hikes.',
                'name' => 'Miguel A.', 'time' => '1日前', 'likes' => 35,
                'img' => 'https://images.unsplash.com/photo-1500534623283-312aade485b7?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=29', 'mapQuery' => 'Orange UCMA', 'comments' => [],
            ],
            [
                'tag' => 'South Area', 'title' => 'Moalboal Sardine Run',
                'desc' => 'Snorkel or dive through millions of sardines just meters from shore.',
                'name' => 'Nina S.', 'time' => '2日前', 'likes' => 44,
                'img' => 'https://images.unsplash.com/photo-1544552866-d3ed42536cfd?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=48', 'mapQuery' => 'Orange UCMA', 'comments' => [],
            ],
        ];
    }

    /**
     * 全エリア一覧(絞り込みなし)
     * サイドバーに出すカテゴリー一覧は categories テーブルから動的に取得。
     */
    public function index(Request $request)
    {
        $areas = Category::forSection('travel');
        $posts = $this->allSpots();

        return view('information.travel.show', [
            'areas' => $areas,
            'currentArea' => null, // 全エリア表示中は「選択中カテゴリーなし」
            'posts' => $posts,
        ]);
    }

    /**
     * カテゴリー(エリア)ごとの絞り込み表示。
     *
     * ここが今回のキモ:
     * itPark() / northArea() / southArea() のような専用メソッドはもう存在しない。
     * どんなslugが来ても、DBに該当カテゴリーがあれば自動的に表示される。
     * アドミンが新しいエリアを追加しても、このメソッド・このビューのままで対応できる。
     */
    public function show(Request $request, string $slug)
    {
        $currentArea = Category::where('section', 'travel')
            ->where('slug', $slug)
            ->firstOrFail(); // 存在しないslugなら自動的に404

        $areas = Category::forSection('travel');

        $posts = array_values(array_filter(
            $this->allSpots(),
            fn ($p) => $p['tag'] === $currentArea->name
        ));

        return view('information.travel.show', [
            'areas' => $areas,
            'currentArea' => $currentArea,
            'posts' => $posts,
        ]);
    }
}
