<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PostImageSeeder extends Seeder
{
    /**
     * 画像ごとのタイトル・説明文をここで指定する。
     * キーは「フォルダ名/ファイル名」。titleを空文字のままにすると自動生成タイトル(「カテゴリー名の投稿1」など)にフォールバックする。
     * descriptionはnullのままでもOK(説明文なしになるだけ)。
     *
     * ここを直接編集すれば、画像ごとに好きなタイトル・説明文を付けられる。
     */
    private array $postDetails = [
        // ---- cafe ----
        'cafe/LINE_ALBUM_PLUS_260902_11.jpg' => ['title' => 'cafe', 'description' => null],
        'cafe/LINE_ALBUM_PLUS_260902_6.jpg' => ['title' => 'cafe', 'description' => null],
        'cafe/LINE_ALBUM_PLUS_260902_8.jpg' => ['title' => 'cafe', 'description' => null],
        'cafe/LINE_ALBUM_cafe_260902_1.jpg' => ['title' => 'cafe', 'description' => null],
        'cafe/LINE_ALBUM_cafe_260902_10.jpg' => ['title' => 'cafe', 'description' => null],
        'cafe/LINE_ALBUM_cafe_260902_11.jpg' => ['title' => 'cafe', 'description' => null],
        'cafe/LINE_ALBUM_cafe_260902_12.jpg' => ['title' => 'cafe', 'description' => null],
        'cafe/LINE_ALBUM_cafe_260902_2.jpg' => ['title' => 'cafe', 'description' => null],
        'cafe/LINE_ALBUM_cafe_260902_3.jpg' => ['title' => 'cafe', 'description' => null],
        'cafe/LINE_ALBUM_cafe_260902_4.jpg' => ['title' => 'cafe', 'description' => null],
        'cafe/LINE_ALBUM_cafe_260902_5.jpg' => ['title' => 'cafe', 'description' => null],
        'cafe/LINE_ALBUM_cafe_260902_6.jpg' => ['title' => 'cafe', 'description' => null],
        'cafe/LINE_ALBUM_cafe_260902_7.jpg' => ['title' => 'cafe', 'description' => null],
        'cafe/LINE_ALBUM_cafe_260902_8.jpg' => ['title' => 'cafe', 'description' => null],
        'cafe/LINE_ALBUM_cafe_260902_9.jpg' => ['title' => 'cafe', 'description' => null],
        // ---- hospital ----
        // ---- it-park ----
        'it-park/2474B8C0-4788-4813-BEA4-660B61FC6F64.jpg' => ['title' => 'it-park', 'description' => null],
        'it-park/386F9180-43D3-4F9D-8A19-184DFE5946BC.png' => ['title' => 'it-park', 'description' => null],
        'it-park/427F8089-ECA3-485A-BA18-BAB4612602F9.jpg' => ['title' => 'it-park', 'description' => null],
        'it-park/LINE_ALBUM_ITpark_260902_1.jpg' => ['title' => 'it-park', 'description' => null],
        'it-park/LINE_ALBUM_ITpark_260902_2.jpg' => ['title' => 'it-park', 'description' => null],
        'it-park/LINE_ALBUM_ITpark_260902_3.jpg' => ['title' => 'it-park', 'description' => null],
        'it-park/LINE_ALBUM_ITpark_260902_4.jpg' => ['title' => 'it-park', 'description' => null],
        'it-park/LINE_ALBUM_ITpark_260902_5.jpg' => ['title' => 'it-park', 'description' => null],
        'it-park/LINE_ALBUM_ITpark_260902_6.jpg' => ['title' => 'it-park', 'description' => null],
        'it-park/LINE_ALBUM_ITpark_260902_7.jpg' => ['title' => 'it-park', 'description' => null],
        'it-park/LINE_ALBUM_ITpark_260902_8.jpg' => ['title' => 'it-park', 'description' => null],
        'it-park/LINE_ALBUM_R80902_260902_15.jpg' => ['title' => 'it-park', 'description' => null],
        'it-park/LINE_ALBUM_R80902_260902_3.jpg' => ['title' => 'it-park', 'description' => null],
        'it-park/LINE_ALBUM_R80902_260902_9.jpg' => ['title' => 'it-park', 'description' => null],
        // ---- laundry ----
        // ---- money-exchange ----
        'money-exchange/104F8B55-892A-4E43-A27B-B7953F2D33DB.jpg' => ['title' => 'money-exchange', 'description' => null],
        'money-exchange/LINE_ALBUM_Other_260902_1.jpg' => ['title' => 'money-exchange', 'description' => null],
        'money-exchange/LINE_ALBUM_Other_260902_2.jpg' => ['title' => 'money-exchange', 'description' => null],
        'money-exchange/LINE_ALBUM_Other_260902_3.jpg' => ['title' => 'money-exchange', 'description' => null],
        // ---- north-area ----
        'north-area/LINE_ALBUM_R80902_260902_10.jpg' => ['title' => 'north-area', 'description' => null],
        'north-area/LINE_ALBUM_R80902_260902_16.jpg' => ['title' => 'north-area', 'description' => null],
        'north-area/LINE_ALBUM_R80902_260902_2.jpg' => ['title' => 'north-area', 'description' => null],
        'north-area/LINE_ALBUM_R80902_260902_6.jpg' => ['title' => 'north-area', 'description' => null],
        'north-area/LINE_ALBUM_R80902_260902_7.jpg' => ['title' => 'north-area', 'description' => null],
        'north-area/LINE_ALBUM_R80902_260902_8.jpg' => ['title' => 'north-area', 'description' => null],
        // ---- others ----
        'others/LINE_ALBUM_Other_260902_10.jpg' => ['title' => 'others', 'description' => null],
        'others/LINE_ALBUM_Other_260902_4.jpg' => ['title' => 'others', 'description' => null],
        'others/LINE_ALBUM_Other_260902_5.jpg' => ['title' => 'others', 'description' => null],
        'others/LINE_ALBUM_Other_260902_6.jpg' => ['title' => 'others', 'description' => null],
        'others/LINE_ALBUM_Other_260902_7.jpg' => ['title' => 'others', 'description' => null],
        'others/LINE_ALBUM_Other_260902_8.jpg' => ['title' => 'others', 'description' => null],
        'others/LINE_ALBUM_Other_260902_9.jpg' => ['title' => 'others', 'description' => null],
        // ---- restaurant ----
        'restaurant/LINE_ALBUM_PLUS_260902_10.jpg' => ['title' => 'restaurant', 'description' => null],
        'restaurant/LINE_ALBUM_PLUS_260902_12.jpg' => ['title' => 'restaurant', 'description' => null],
        'restaurant/LINE_ALBUM_PLUS_260902_14.jpg' => ['title' => 'restaurant', 'description' => null],
        'restaurant/LINE_ALBUM_PLUS_260902_19.jpg' => ['title' => 'restaurant', 'description' => null],
        'restaurant/LINE_ALBUM_PLUS_260902_2.jpg' => ['title' => 'restaurant', 'description' => null],
        'restaurant/LINE_ALBUM_PLUS_260902_20.jpg' => ['title' => 'restaurant', 'description' => null],
        'restaurant/LINE_ALBUM_PLUS_260902_23.jpg' => ['title' => 'restaurant', 'description' => null],
        'restaurant/LINE_ALBUM_PLUS_260902_24.jpg' => ['title' => 'restaurant', 'description' => null],
        'restaurant/LINE_ALBUM_PLUS_260902_25.jpg' => ['title' => 'restaurant', 'description' => null],
        'restaurant/LINE_ALBUM_PLUS_260902_26.jpg' => ['title' => 'restaurant', 'description' => null],
        'restaurant/LINE_ALBUM_PLUS_260902_28.jpg' => ['title' => 'restaurant', 'description' => null],
        'restaurant/LINE_ALBUM_PLUS_260902_3.jpg' => ['title' => 'restaurant', 'description' => null],
        'restaurant/LINE_ALBUM_PLUS_260902_4.jpg' => ['title' => 'restaurant', 'description' => null],
        'restaurant/LINE_ALBUM_PLUS_260902_5.jpg' => ['title' => 'restaurant', 'description' => null],
        'restaurant/LINE_ALBUM_PLUS_260902_7.jpg' => ['title' => 'restaurant', 'description' => null],
        // ---- sim-card ----
        'sim-card/LINE_ALBUM_Other_260902_11.jpg' => ['title' => 'sim-card', 'description' => null],
        // ---- south-area ----
        'south-area/3884F699-1775-49DD-8C6C-ADCB09E16A5E.jpg' => ['title' => 'south-area', 'description' => null],
        'south-area/8500150D-95F8-46B2-BECE-C06991D555FF.jpg' => ['title' => 'south-area', 'description' => null],
        'south-area/LINE_ALBUM_R80902_260902_1.jpg' => ['title' => 'south-area', 'description' => null],
        'south-area/LINE_ALBUM_R80902_260902_11.jpg' => ['title' => 'south-area', 'description' => null],
        'south-area/LINE_ALBUM_R80902_260902_12.jpg' => ['title' => 'south-area', 'description' => null],
        'south-area/LINE_ALBUM_R80902_260902_13.jpg' => ['title' => 'south-area', 'description' => null],
        'south-area/LINE_ALBUM_R80902_260902_14.jpg' => ['title' => 'south-area', 'description' => null],
        'south-area/LINE_ALBUM_R80902_260902_17.jpg' => ['title' => 'south-area', 'description' => null],
        'south-area/LINE_ALBUM_R80902_260902_4.jpg' => ['title' => 'south-area', 'description' => null],
        'south-area/LINE_ALBUM_R80902_260902_5.jpg' => ['title' => 'south-area', 'description' => null],
        // ---- ucma ----
        'ucma/LINE_ALBUM_PLUS_260902_16.jpg' => ['title' => 'ucma', 'description' => null],
        'ucma/LINE_ALBUM_PLUS_260902_17.jpg' => ['title' => 'ucma', 'description' => null],
        'ucma/LINE_ALBUM_PLUS_260902_18.jpg' => ['title' => 'ucma', 'description' => null],
        'ucma/LINE_ALBUM_PLUS_260902_21.jpg' => ['title' => 'ucma', 'description' => null],
        'ucma/LINE_ALBUM_PLUS_260902_22.jpg' => ['title' => 'ucma', 'description' => null],
        'ucma/LINE_ALBUM_PLUS_260902_27.jpg' => ['title' => 'ucma', 'description' => null],
        'ucma/LINE_ALBUM_PLUS_260902_9.jpg' => ['title' => 'ucma', 'description' => null],
        // ---- w-geonzon ----
        'w-geonzon/LINE_ALBUM_PLUS_260902_13.jpg' => ['title' => 'w-geonzon', 'description' => null],
        'w-geonzon/LINE_ALBUM_PLUS_260902_15.jpg' => ['title' => 'w-geonzon', 'description' => null],
    ];

    /**
     * public/images/posts/{カテゴリーのslug}/ に置かれた画像を読み込んで、
     * 投稿(Post)として自動登録する。
     *
     * 画像ファイルはGit管理下(public/images/posts/以下)に置く想定なので、
     * これを実行すればチームの誰でも同じ投稿・同じ写真を再現できる。
     *
     * 何度実行しても同じ画像を重複登録しないよう、image列の値で存在チェックしてから作成する。
     * (後から画像を追加して再実行しても、新しく増えた分だけ追加登録される)
     */
    public function run(): void
    {
        $baseDir = public_path('images/posts');

        if (!File::isDirectory($baseDir)) {
            return;
        }

        // 投稿者は管理者アカウント固定(AdminSeederで作られるadmin@gmail.com)
        $author = User::where('email', 'admin@gmail.com')->first() ?? User::first();

        if (!$author) {
            $this->command?->warn('PostImageSeeder: 投稿者にできるユーザーが見つからないためスキップしました。');
            return;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $created = 0;

        foreach (File::directories($baseDir) as $categoryDir) {
            $slug = basename($categoryDir);

            // フォルダ名 = categoriesテーブルのslugという前提。一致するカテゴリーが無ければスキップ
            $category = Category::where('slug', $slug)->first();

            if (!$category) {
                continue;
            }

            $files = collect(File::files($categoryDir))
                ->filter(fn ($f) => in_array(strtolower($f->getExtension()), $allowedExtensions, true))
                ->sortBy(fn ($f) => $f->getFilename())
                ->values();

            foreach ($files as $index => $file) {
                $relativePath = 'images/posts/' . $slug . '/' . $file->getFilename();

                // 同じ画像パスの投稿が既にあれば重複登録しない
                if (Post::where('image', $relativePath)->exists()) {
                    continue;
                }

                // $postDetailsに個別のタイトル・説明文があればそれを使い、無ければ自動生成にフォールバック
                $detailKey = $slug . '/' . $file->getFilename();
                $details = $this->postDetails[$detailKey] ?? [];
                $title = $details['title'] ?? '';
                $description = $details['description'] ?? null;

                Post::create([
                    'user_id' => $author->id,
                    'category_id' => $category->id,
                    'title' => $title !== '' ? $title : ($category->name . 'の投稿' . ($index + 1)),
                    'description' => $description,
                    'image' => $relativePath,
                    'price' => null,
                    'map_query' => null,
                ]);

                $created++;
            }
        }

        $this->command?->info("PostImageSeeder: {$created}件の投稿を新規作成しました。");
    }
}
