<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // ---- Travel ----
        Category::updateOrCreate(['slug' => 'it-park'], ['section' => 'travel', 'name' => 'IT Park', 'sort_order' => 1, 'hero_image' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?q=80&w=1600&auto=format&fit=crop', 'description' => "夜も賑わう都会エリア。\nおしゃれなバー・レストラン・展望体験が集まる。"]);
        Category::updateOrCreate(['slug' => 'north-area'], ['section' => 'travel', 'name' => 'North Area', 'sort_order' => 2, 'hero_image' => 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?q=80&w=1600&auto=format&fit=crop', 'description' => "花畑・寺院・離島ビーチ。\n北部エリアの自然とスピリチュアルを巡る。"]);
        Category::updateOrCreate(['slug' => 'south-area'], ['section' => 'travel', 'name' => 'South Area', 'sort_order' => 3, 'hero_image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?q=80&w=1600&auto=format&fit=crop', 'description' => "ホエールシャーク・滝・山と海。\nアクティブ派に人気の南部エリア。"]);

        // ---- Other ----
        Category::updateOrCreate(['slug' => 'laundry'], [
            'section' => 'other',
            'name' => 'Laundry',
            'sort_order' => 1,
            'hero_image' => 'https://images.unsplash.com/photo-1545173168-9f1947eebb7f?q=80&w=1600&auto=format&fit=crop',
            'description' => "洗濯もあっという間。\n留学生活の家事をサポートするランドリーサービス。",
        ]);
        Category::updateOrCreate(['slug' => 'money-exchange'], [
            'section' => 'other',
            'name' => 'Money Exchange',
            'sort_order' => 2,
            'hero_image' => 'https://images.unsplash.com/photo-1580519542036-c47de6196ba5?q=80&w=1600&auto=format&fit=crop',
            'description' => "安心・便利な両替サービス。\n留学生活のお金まわりをサポート。",
        ]);
        Category::updateOrCreate(['slug' => 'sim-card'], [
            'section' => 'other',
            'name' => 'SIM Card',
            'sort_order' => 3,
            'hero_image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=1600&auto=format&fit=crop',
            'description' => "来て早々インターネット環境を確保。\nSIMカードの購入・設定情報。",
        ]);
        Category::updateOrCreate(['slug' => 'hospital'], [
            'section' => 'other',
            'name' => 'Hospital',
            'sort_order' => 4,
            'hero_image' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=1600&auto=format&fit=crop',
            'description' => "もしもの時も安心。\n留学生対応の病院・クリニック情報。",
        ]);
        Category::updateOrCreate(['slug' => 'others'], [
            'section' => 'other',
            'name' => 'Others',
            'sort_order' => 5,
            'hero_image' => 'https://images.unsplash.com/photo-1568992687947-868a62a9f521?q=80&w=1600&auto=format&fit=crop',
            'description' => "その他、留学生活に役立つ便利なサービス情報を\nまとめています。",
        ]);

        // ---- Restaurant & Cafe ----
        Category::updateOrCreate(['slug' => 'restaurant'], [
            'section' => 'restaurant-cafe',
            'name' => 'Restaurant',
            'sort_order' => 1,
            'hero_image' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=1600&auto=format&fit=crop',
            'description' => "落ち着いた空間でゆったり食事。\nディナーにもぴったりなレストラン。",
        ]);
        Category::updateOrCreate(['slug' => 'cafe'], [
            'section' => 'restaurant-cafe',
            'name' => 'Cafe',
            'sort_order' => 2,
            'hero_image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=1600&auto=format&fit=crop',
            'description' => "コーヒー片手にひと休み。\n勉強にも作業にも使える居心地の良いカフェ。",
        ]);

        // ---- Carinderia ----
        // サブカテゴリーを選ぶとヒーロー画像・説明文もそれぞれ切り替わる(未設定のうちはメインカテゴリーの
        // デフォルトにフォールバックする)。ここではとりあえずの初期画像を入れておき、アドミンが後で
        // 管理画面から差し替えられるようにしてある。
        Category::where('slug', 'carinderia')->delete();
        Category::updateOrCreate(['slug' => 'ucma'], [
            'section' => 'carinderia',
            'name' => 'UCMA',
            'sort_order' => 1,
            'hero_image' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?q=80&w=1600&auto=format&fit=crop',
            'description' => "地元で人気のカリンデリア。\n日替わりのおかずが並ぶ、あったかい家庭の味。",
        ]);
        Category::updateOrCreate(['slug' => 'w-geonzon'], [
            'section' => 'carinderia',
            'name' => 'W Geonzon',
            'sort_order' => 2,
            'hero_image' => 'https://images.unsplash.com/photo-1626200419199-391ae4be7a41?q=80&w=1600&auto=format&fit=crop',
            'description' => "ボリューム満点のワンプレート。\n学生にも嬉しい、コスパ抜群のカリンデリア。",
        ]);
    }
}
