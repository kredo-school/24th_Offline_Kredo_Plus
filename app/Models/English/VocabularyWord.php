<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;

class VocabularyWord extends Model
{
    protected $table = 'vocabulary_words';

    protected $fillable = [
        'word',
        'part_of_speech',
        'meaning_ja',
        'meaning_en',
        'example_sentence',
        'example_sentence_ja',
        'exam_type',
        'level',
        'sort_order',
    ];

    // ===== スコープ =====

    /**
     * exam_type + level でフィルタ（最も使用頻度が高いクエリ）
     *
     * 使用例: VocabularyWord::byLevel('TOEIC', '700')->get()
     *
     * ⚠️ 並び順はあえて固定しない。呼び出し側で inRandomOrder() 等を指定する
     *    （スコープ内で orderBy('sort_order') すると後続の inRandomOrder() が
     *     第2ソートキー扱いになり、実質ランダムにならないため）。
     */
    public function scopeByLevel($query, string $examType, string $level)
    {
        return $query
            ->where('exam_type', $examType)
            ->where('level', $level);
    }

    /**
     * 出題順（教材としての推奨順）で並べる。
     */
    public function scopeInStudyOrder($query)
    {
        return $query->orderBy('sort_order');
    }
}
