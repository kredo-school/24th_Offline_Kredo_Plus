<?php

namespace Database\Seeders\English;

use App\Models\English\IeltsTopic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IeltsTopicSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $topics = [
            [
                'slug'        => 'education',
                'name'        => 'Education',
                'description' => '教育・学習・学校制度に関するトピック',
                'icon'        => '🎓',
                'sort_order'  => 1,
            ],
            [
                'slug'        => 'technology',
                'name'        => 'Technology',
                'description' => 'テクノロジー・IT・デジタル社会に関するトピック',
                'icon'        => '💻',
                'sort_order'  => 2,
            ],
            [
                'slug'        => 'environment',
                'name'        => 'Environment',
                'description' => '環境問題・気候変動・自然保護に関するトピック',
                'icon'        => '🌿',
                'sort_order'  => 3,
            ],
        ];

        // firstOrCreateではなくupdateOrCreateなので、既にあるslugでも重複エラーにならず何度でも実行できる
        foreach ($topics as $data) {
            IeltsTopic::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
