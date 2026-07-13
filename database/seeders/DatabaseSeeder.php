<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // ===== 英語学習機能 =====

        // ----- タイピング教材 -----
        $this->call([
            TypingCategorySeeder::class,   // typing_categories
            TypingMaterialSeeder::class,   // typing_materials
        ]);

        // ----- IELTS -----
        $this->call([
            IeltsTopicSeeder::class,       // ielts_topics
            IeltsSlideSeeder::class,       // ielts_slides（IeltsTopic 作成後）
            IeltsMaterialSeeder::class,    // ielts_materials（IeltsTopic 作成後）
        ]);

        // ----- TOEIC -----
        $this->call([
            ToeicSlideSeeder::class,       // toeic_slides
            ToeicQuestionSeeder::class,    // toeic_questions + toeic_question_options
        ]);

        // ----- 英単語 -----
        $this->call(VocabularyWordSeeder::class);

        // ----- 試験概要・学習ストラテジー -----
        $this->call(LearningContentSeeder::class);
    }
}
