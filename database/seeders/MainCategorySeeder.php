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
            'hero_image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=1600&auto=format&fit=crop',
            'description' => 'シンプルで美味しい、家庭的な味。近所のカリンデリアの美味しさをお届けします。',
            'sort_order' => 1,
        ]);
        // ↑テスト投稿で管理画面から画像が上書きされていたため、他ページと揃うオシャレな初期画像に強制的に戻す
        // (firstOrCreateだと既存行は更新されないので、hero_imageだけ明示的に上書き。アドミンが後で管理画面から変更すればそちらが優先される)
        MainCategory::where('key', 'carinderia')->update([
            'hero_image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=1600&auto=format&fit=crop',
        ]);

        MainCategory::firstOrCreate(['key' => 'restaurant-cafe'], [
            'name' => 'Restaurant & Cafe',
            'hero_image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1600&auto=format&fit=crop',
            'description' => 'ゆったり座れるディナーも、居心地の良いカフェも、ここに全部揃ってます。',
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
            'description' => '留学生活に欠かせない日常サービス:ランドリー・両替・SIMカードなど。',
            'sort_order' => 4,
        ]);
    }
}
