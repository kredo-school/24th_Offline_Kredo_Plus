<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OtherController extends Controller
{
    /**
     * Other一覧ページ(Laundry / Money Exchange / SIM Card / Hospital / Others)
     *
     * 将来DB接続する時:
     *   $posts = Post::whereIn('category', ['Laundry','Money Exchange','SIM Card','Hospital','Others'])
     *                ->latest()->get();
     */
    public function index(Request $request)
    {
        $posts = [
            // ---- Laundry ----
            [
                'tag' => 'Laundry', 'title' => 'Quick Dry Laundry Shop',
                'desc' => 'Fast wash & dry service, ready in 3 hours. Includes folding.',
                'name' => 'Rica M.', 'time' => '1時間前', 'likes' => 18,
                'img' => 'https://images.unsplash.com/photo-1545173168-9f1947eebb7f?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=45', 'mapQuery' => 'Orange UCMA', 'comments' => [],
            ],
            [
                'tag' => 'Laundry', 'title' => 'Coin Laundry near Dorm',
                'desc' => 'Self-service coin laundry, open 24/7 for busy students.',
                'name' => 'Josh T.', 'time' => '4時間前', 'likes' => 9,
                'img' => 'https://images.unsplash.com/photo-1604335399105-a0c585fd81a1?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=33', 'mapQuery' => 'Orange UCMA', 'comments' => [],
            ],

            // ---- Money Exchange ----
            [
                'tag' => 'Money Exchange', 'title' => 'Best Rate USD to PHP',
                'desc' => 'Trusted money changer with daily updated rates, no hidden fees.',
                'name' => 'Carlo D.', 'time' => '30分前', 'likes' => 14,
                'img' => 'https://images.unsplash.com/photo-1580519542036-c47de6196ba5?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=14', 'mapQuery' => 'Orange UCMA', 'comments' => [],
            ],

            // ---- SIM Card ----
            [
                'tag' => 'SIM Card', 'title' => 'Prepaid SIM Starter Pack',
                'desc' => 'Includes free load and data bundle for new students.',
                'name' => 'Miguel A.', 'time' => '2時間前', 'likes' => 20,
                'img' => 'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=39', 'mapQuery' => 'Orange UCMA', 'comments' => [],
            ],

            // ---- Hospital ----
            [
                'tag' => 'Hospital', 'title' => 'Student Health Clinic',
                'desc' => 'Walk-in clinic with English-speaking staff near campus.',
                'name' => 'Dr. Reyes', 'time' => '5時間前', 'likes' => 11,
                'img' => 'https://images.unsplash.com/photo-1587351021759-3e566b6af7cc?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=60', 'mapQuery' => 'Orange UCMA', 'comments' => [],
            ],

            // ---- Others ----
            [
                'tag' => 'Others', 'title' => 'Budget Smartphones & Repairs',
                'desc' => 'Affordable phones, screen repairs, and accessories for students.',
                'name' => 'Kevin R.', 'time' => '2時間前', 'likes' => 16,
                'img' => 'https://images.unsplash.com/photo-1556656793-08538906a9f8?q=80&w=800&auto=format&fit=crop',
                'avatar' => 'https://i.pravatar.cc/64?img=17', 'mapQuery' => 'Orange UCMA', 'comments' => [],
            ],
        ];

        return view('information.other.index', compact('posts'));
    }
}
