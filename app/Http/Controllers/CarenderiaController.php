<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarenderiaController extends Controller
{
    /**
     * Carinderia一覧ページ
     *
     * 今はダミーデータを配列で用意しているだけ。
     * 将来DB接続する時は、この部分を以下のように差し替えるだけでOK:
     *
     *   $posts = Post::where('category', 'Carinderia')
     *                ->with(['user', 'comments.user'])
     *                ->latest()
     *                ->get();
     */
    public function index(Request $request)
    {
        $posts = [
            [
                'tag' => 'Carinderia',
                'title' => 'Best Sisig near school',
                'desc' => 'Crispy and flavorful sisig perfect with rice. A favorite among students!',
                'price' => 150,
                'name' => 'Mateo L.',
                'time' => '2時間前',
                'likes' => 34,
                'img' => asset('images/carenderia/sisig.jpg'),
                'avatar' => 'https://i.pravatar.cc/64?img=12',
                'mapQuery' => 'Orange UCMA',
                'comments' => [
                    ['name' => 'Sophia C.', 'text' => 'これめっちゃ美味しそう!'],
                    ['name' => 'Juan R.', 'text' => '今度行ってみます👍'],
                ],
            ],
            [
                'tag' => 'Carinderia',
                'title' => 'Lutong Bahay Meals',
                'desc' => 'Home-cooked meals like how your mom makes it.',
                'price' => 120,
                'name' => 'Sophia C.',
                'time' => '5時間前',
                'likes' => 51,
                'img' => asset('images/carenderia/sisig2.jpg'),
                'avatar' => 'https://i.pravatar.cc/64?img=32',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
            [
                'tag' => 'Carinderia',
                'title' => 'Fried Chicken Sarap',
                'desc' => 'Juicy fried chicken with special spices.',
                'price' => 90,
                'name' => 'Juan R.',
                'time' => '1日前',
                'likes' => 12,
                'img' => asset('images/carenderia/sisig3.jpg'),
                'avatar' => 'https://i.pravatar.cc/64?img=51',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
            [
                'tag' => 'Carinderia',
                'title' => 'Pinakbet and More',
                'desc' => 'Healthy veggies with a local twist.',
                'price' => 110,
                'name' => 'Angela M.',
                'time' => '1日前',
                'likes' => 8,
                'img' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=25',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
            [
                'tag' => 'Carinderia',
                'title' => 'Grilled Bangus',
                'desc' => 'Fresh bangus grilled to perfection.',
                'price' => 130,
                'name' => 'Daniel L.',
                'time' => '2日前',
                'likes' => 22,
                'img' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=15',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
            [
                'tag' => 'Carinderia',
                'title' => 'Bulalo sa Umaga',
                'desc' => 'Warm bulalo that heals your soul.',
                'price' => 140,
                'name' => 'Kim C.',
                'time' => '2日前',
                'likes' => 45,
                'img' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=44',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
            [
                'tag' => 'Carinderia',
                'title' => 'Beef Mami Special',
                'desc' => 'Rich broth noodles perfect for rainy days.',
                'price' => 80,
                'name' => 'Ella R.',
                'time' => '3日前',
                'likes' => 6,
                'img' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=5',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
            [
                'tag' => 'Carinderia',
                'title' => 'Lechon Roll Slices',
                'desc' => 'Crispy skin, tender meat, served hot.',
                'price' => 160,
                'name' => 'Marco T.',
                'time' => '4日前',
                'likes' => 29,
                'img' => 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=8',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
            [
                'tag' => 'Carinderia',
                'title' => 'Longsilog Combo',
                'desc' => 'Sweet longganisa with egg and rice.',
                'price' => 100,
                'name' => 'Rina P.',
                'time' => '5日前',
                'likes' => 3,
                'img' => 'https://images.unsplash.com/photo-1600891964092-4316c288032e?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=20',
                'mapQuery' => 'Orange UCMA',
                'comments' => [],
            ],
        ];

        return view('information.carenderia.index', compact('posts'));
    }
}
