<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * 今まで手作業でファイルを分けていたカテゴリーを、
     * 初期データとしてDBに登録する。
     *
     * 以降、新しいカテゴリーはアドミン画面から追加すればOK。
     * ファイル(Controller/Blade)の追加は不要。
     */
    public function run(): void
    {
        // ---- Travel(IT Park / North Area / South Area) ----
        Category::create([
            'section' => 'travel', 'name' => 'IT Park', 'slug' => 'it-park', 'sort_order' => 1,
            'hero_image' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?q=80&w=1600&auto=format&fit=crop',
            'description' => '夜も賑わう都会エリア。おしゃれなバー・レストラン・展望体験が集まる。',
        ]);
        Category::create([
            'section' => 'travel', 'name' => 'North Area', 'slug' => 'north-area', 'sort_order' => 2,
            'hero_image' => 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?q=80&w=1600&auto=format&fit=crop',
            'description' => '花畑・寺院・離島ビーチ。北部エリアの自然とスピリチュアルを巡る。',
        ]);
        Category::create([
            'section' => 'travel', 'name' => 'South Area', 'slug' => 'south-area', 'sort_order' => 3,
            'hero_image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?q=80&w=1600&auto=format&fit=crop',
            'description' => 'ホエールシャーク・滝・山と海。アクティブ派に人気の南部エリア。',
        ]);

        // ---- Other(Laundry / Money Exchange / SIM Card / Hospital / Others) ----
        Category::create(['section' => 'other', 'name' => 'Laundry',        'slug' => 'laundry',        'sort_order' => 1]);
        Category::create(['section' => 'other', 'name' => 'Money Exchange', 'slug' => 'money-exchange', 'sort_order' => 2]);
        Category::create(['section' => 'other', 'name' => 'SIM Card',       'slug' => 'sim-card',       'sort_order' => 3]);
        Category::create(['section' => 'other', 'name' => 'Hospital',       'slug' => 'hospital',       'sort_order' => 4]);
        Category::create(['section' => 'other', 'name' => 'Others',         'slug' => 'others',         'sort_order' => 5]);

        // ---- Restaurant & Cafe ----
        Category::create(['section' => 'restaurant-cafe', 'name' => 'Restaurant', 'slug' => 'restaurant', 'sort_order' => 1]);
        Category::create(['section' => 'restaurant-cafe', 'name' => 'Cafe',       'slug' => 'cafe',       'sort_order' => 2]);
    }
}
