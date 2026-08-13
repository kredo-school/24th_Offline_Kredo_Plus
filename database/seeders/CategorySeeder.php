<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // ---- Travel ----
        Category::firstOrCreate(['slug' => 'it-park'], ['section' => 'travel', 'name' => 'IT Park', 'sort_order' => 1, 'hero_image' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?q=80&w=1600&auto=format&fit=crop', 'description' => '夜も賑わう都会エリア。おしゃれなバー・レストラン・展望体験が集まる。']);
        Category::firstOrCreate(['slug' => 'north-area'], ['section' => 'travel', 'name' => 'North Area', 'sort_order' => 2, 'hero_image' => 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?q=80&w=1600&auto=format&fit=crop', 'description' => '花畑・寺院・離島ビーチ。北部エリアの自然とスピリチュアルを巡る。']);
        Category::firstOrCreate(['slug' => 'south-area'], ['section' => 'travel', 'name' => 'South Area', 'sort_order' => 3, 'hero_image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?q=80&w=1600&auto=format&fit=crop', 'description' => 'ホエールシャーク・滝・山と海。アクティブ派に人気の南部エリア。']);

        // ---- Other ----
        Category::firstOrCreate(['slug' => 'laundry'], ['section' => 'other', 'name' => 'Laundry', 'sort_order' => 1]);
        Category::firstOrCreate(['slug' => 'money-exchange'], ['section' => 'other', 'name' => 'Money Exchange', 'sort_order' => 2]);
        Category::firstOrCreate(['slug' => 'sim-card'], ['section' => 'other', 'name' => 'SIM Card', 'sort_order' => 3]);
        Category::firstOrCreate(['slug' => 'hospital'], ['section' => 'other', 'name' => 'Hospital', 'sort_order' => 4]);
        Category::firstOrCreate(['slug' => 'others'], ['section' => 'other', 'name' => 'Others', 'sort_order' => 5]);

        // ---- Restaurant & Cafe ----
        Category::firstOrCreate(['slug' => 'restaurant'], ['section' => 'restaurant-cafe', 'name' => 'Restaurant', 'sort_order' => 1]);
        Category::firstOrCreate(['slug' => 'cafe'], ['section' => 'restaurant-cafe', 'name' => 'Cafe', 'sort_order' => 2]);

        // ---- Carinderia ----
        Category::where('slug', 'carinderia')->delete();
        Category::firstOrCreate(['slug' => 'ucma'], ['section' => 'carinderia', 'name' => 'UCMA', 'sort_order' => 1]);
        Category::firstOrCreate(['slug' => 'w-geonzon'], ['section' => 'carinderia', 'name' => 'W Geonzon', 'sort_order' => 2]);
    }
}
