<?php

namespace Database\Seeders;

use App\Models\MainCategory;
use Illuminate\Database\Seeder;

class MainCategorySeeder extends Seeder
{
    /**
     * 今まで各Bladeファイルに直書きされていたヒーロー画像・タイトル・説明文を、
     * そのままの内容でmain_categoriesテーブルに登録する。
     *
     * ここで登録される内容は、変更前の見た目と完全に同じになるようにしてある。
     * (アドミンが編集画面から変更するまでは、今までと見た目は変わらない)
     *
     * firstOrCreateなので、何度実行しても重複登録されない。
     */
    public function run(): void
    {
        MainCategory::firstOrCreate(['key' => 'carinderia'], [
            'name' => 'Carinderia',
            'hero_image' => 'images/carinderia/sisig.jpg',
            'description' => 'Simple, delicious, and close to home. Bringing you the flavors of your neighborhood carinderia.',
            'sort_order' => 1,
        ]);

        MainCategory::firstOrCreate(['key' => 'restaurant-cafe'], [
            'name' => 'Restaurant & Cafe',
            'hero_image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1600&auto=format&fit=crop',
            'description' => 'Sit-down dinners and cozy coffee corners, all in one place.',
            'sort_order' => 2,
        ]);

        MainCategory::firstOrCreate(['key' => 'travel'], [
            'name' => 'Travel & Tourism',
            'hero_image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?q=80&w=1600&auto=format&fit=crop',
            'description' => 'セブの海・自然・カルチャーを、まるごと満喫しよう。',
            'sort_order' => 3,
        ]);

        MainCategory::firstOrCreate(['key' => 'other'], [
            'name' => 'Other',
            'hero_image' => 'https://images.unsplash.com/photo-1604335399105-a0c585fd81a1?q=80&w=800&auto=format&fit=crop',
            'description' => 'Everyday essentials for student life: laundry, money exchange, SIM cards, and more.',
            'sort_order' => 4,
        ]);
    }
}
