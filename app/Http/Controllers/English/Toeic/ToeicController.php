<?php

namespace App\Http\Controllers\English\Toeic;

use App\Http\Controllers\Controller;
use App\Http\Requests\English\StoreToeicAnswerRequest;
use App\Models\English\ToeicAnswerLog;
use App\Models\English\ToeicQuestion;
use App\Models\English\ToeicQuestionOption;
use App\Models\English\ToeicResult;
use App\Models\English\ToeicSlide;
use App\Models\English\UserSectionProgress;
use App\Services\English\StudyLogService;
use App\Services\English\XpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ToeicController extends Controller
{
    public function __construct(
        private readonly XpService       $xpService,
        private readonly StudyLogService $studyLogService,
    ) {}

    /**
     * TOEIC メニュー (S02)
     * GET /english/toeic
     */
    public function index()
    {
        $userId = Auth::id();

        // 完了済みセクションを取得（スライド + 問題）
        $completedSlides    = UserSectionProgress::where('user_id', $userId)
            ->where('section_type', UserSectionProgress::TYPE_TOEIC_SLIDES)
            ->where('is_completed', true)
            ->pluck('section_key')
            ->toArray();

        $completedQuestions = UserSectionProgress::where('user_id', $userId)
            ->where('section_type', UserSectionProgress::TYPE_TOEIC_QUESTIONS)
            ->where('is_completed', true)
            ->pluck('section_key')
            ->toArray();

        $partMeta = config('english.toeic_part_meta');

        $parts = collect($partMeta)->map(function ($meta, $partNum) use ($completedSlides, $completedQuestions) {
            $key         = "part_{$partNum}";
            $slidesDone  = in_array($key, $completedSlides);
            $questionsDone = in_array($key, $completedQuestions);
            $progress    = $meta['available']
                ? (int) round((($slidesDone ? 1 : 0) + ($questionsDone ? 1 : 0)) / 2 * 100)
                : 0;

            return array_merge($meta, [
                'part'     => $partNum,
                'progress' => $progress,
            ]);
        })->values()->all();

        return view('english.toeic.index', compact('parts'));
    }

    /**
     * TOEIC スライド (S03)
     * GET /english/toeic/{part}/slides/{step?}
     */
    public function slides(int $part, int $step = 1)
    {
        $slides = ToeicSlide::where('part', $part)
            ->orderBy('step_number')
            ->get();

        abort_if($slides->isEmpty(), 404);

        $totalSteps  = $slides->count();
        $step        = max(1, min($step, $totalSteps));
        $slide       = $slides->firstWhere('step_number', $step) ?? $slides->first();

        $userId      = Auth::id();
        $sectionKey  = "part_{$part}";

        // 閲覧済みセクションを確認（スキップボタン表示判定）
        $sectionProgress = UserSectionProgress::where('user_id', $userId)
            ->where('section_type', UserSectionProgress::TYPE_TOEIC_SLIDES)
            ->where('section_key', $sectionKey)
            ->first();

        $canSkip = $sectionProgress && $sectionProgress->is_completed;

        // 最終閲覧ステップを更新
        UserSectionProgress::updateOrCreate(
            ['user_id' => $userId, 'section_type' => UserSectionProgress::TYPE_TOEIC_SLIDES, 'section_key' => $sectionKey],
            ['last_step' => $step]
        );

        return view('english.toeic.slides', compact('part', 'step', 'totalSteps', 'canSkip', 'slide'));
    }

    /**
     * スライド全閲覧完了を記録
     * POST /english/toeic/{part}/slides/complete
     */
    public function completeSlides(Request $request, int $part)
    {
        $sectionKey = "part_{$part}";

        UserSectionProgress::updateOrCreate(
            ['user_id' => Auth::id(), 'section_type' => UserSectionProgress::TYPE_TOEIC_SLIDES, 'section_key' => $sectionKey],
            ['is_completed' => true, 'completed_at' => now()]
        );

        return redirect()->route('english.toeic.practice', ['part' => $part]);
    }

    /**
     * TOEIC 問題（全問ロード方式）(S04)
     * GET /english/toeic/{part}/practice
     *
     * Part 5 は問題プールからランダムで10問を抽出して出題する（セッション中は順序固定）。
     * Part 6 / 7 は長文（passage）に紐づく問題を全問、sort_order順に出題する。
     */
    public function practice(int $part)
    {
        $sessionKey = "toeic_practice_{$part}";
        $questions  = collect();

        if (session()->has($sessionKey)) {
            $ids = session($sessionKey);
            $found = ToeicQuestion::whereIn('id', $ids)->with(['options', 'passage'])->get();

            if ($found->count() === count($ids)) {
                $questions = $found->sortBy(fn($q) => array_search($q->id, $ids))->values();
            } else {
                // 問題データ更新等でセッション内のIDが古くなっている場合は再抽選する
                session()->forget([$sessionKey, "toeic_answers_{$part}"]);
            }
        }

        if ($questions->isEmpty()) {
            $pool = ToeicQuestion::forPart($part)->with(['options', 'passage'])->get();

            abort_if($pool->isEmpty(), 404);

            $questions = $part === 5
                ? $pool->shuffle()->take(10)->values()
                : $pool;

            session([$sessionKey => $questions->pluck('id')->all()]);
        }

        // フロントに正解フラグを露出しないようにオプションから is_correct を除外
        $questionsJson = $questions->map(function ($q) {
            return [
                'id'            => $q->id,
                'question_text' => $q->question_text,
                'explanation'   => $q->explanation ?? '',
                'passage'       => $q->passage ? [
                    'id'        => $q->passage->id,
                    'title'     => $q->passage->title,
                    'documents' => $q->passage->documents,
                ] : null,
                'options'       => $q->options->map(fn($o) => [
                    'id'          => $o->id,
                    'label'       => $o->label,
                    'option_text' => $o->option_text,
                ])->values()->all(),
            ];
        })->values()->all();

        return view('english.toeic.practice', compact('part', 'questionsJson'));
    }

    /**
     * 1問回答（JSON）
     * POST /english/toeic/{part}/answer
     * ⚠️ XP はここでは加算しない（二重加算防止）
     */
    public function submitAnswer(StoreToeicAnswerRequest $request, int $part)
    {
        $questionId       = $request->integer('question_id');
        $selectedOptionId = $request->integer('selected_option_id');

        $correctOption = ToeicQuestionOption::where('question_id', $questionId)
            ->where('is_correct', true)
            ->first();

        abort_if(!$correctOption, 404);

        $isCorrect = ($selectedOptionId === $correctOption->id);

        // セッションに回答を積算
        $answerKey = "toeic_answers_{$part}";
        $answers   = session($answerKey, []);
        $answers[$questionId] = [
            'selected_option_id' => $selectedOptionId,
            'is_correct'         => $isCorrect,
        ];
        session([$answerKey => $answers]);

        return response()->json([
            'is_correct'       => $isCorrect,
            'correct_option_id' => $correctOption->id,
            'explanation'      => ToeicQuestion::find($questionId)?->explanation ?? '',
        ]);
    }

    /**
     * 全問完了 → 保存 + XP付与
     * POST /english/toeic/{part}/complete
     */
    public function complete(Request $request, int $part)
    {
        $user       = Auth::user();
        $answerKey  = "toeic_answers_{$part}";
        $answers    = session($answerKey, []);

        if (empty($answers)) {
            return redirect()->route('english.toeic.practice', ['part' => $part]);
        }

        $correctCount    = collect($answers)->where('is_correct', true)->count();
        $totalQuestions  = count($answers);
        $xp              = $this->xpService->calcToeicXp($correctCount, $totalQuestions);

        DB::transaction(function () use ($user, $part, $answers, $correctCount, $totalQuestions, $xp) {
            // ToeicResult 保存
            $result = ToeicResult::create([
                'user_id'         => $user->id,
                'part'            => $part,
                'total_questions' => $totalQuestions,
                'correct_count'   => $correctCount,
                'xp_gained'       => $xp,
                'completed_at'    => now(),
            ]);

            // ToeicAnswerLog を一括 INSERT
            $logs = [];
            foreach ($answers as $questionId => $answer) {
                $logs[] = [
                    'result_id'          => $result->id,
                    'question_id'        => $questionId,
                    'selected_option_id' => $answer['selected_option_id'],
                    'is_correct'         => $answer['is_correct'],
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
            }
            ToeicAnswerLog::insert($logs);

            // XP 加算（complete() でのみ呼ぶ）
            $this->xpService->addXp($user, $xp);

            // StudyLog 記録（total_study_time + streak も内部で更新）
            $this->studyLogService->log($user, 'toeic', $result->id, $xp, 0);

            // セクション進捗を完了に更新
            UserSectionProgress::updateOrCreate(
                ['user_id' => $user->id, 'section_type' => UserSectionProgress::TYPE_TOEIC_QUESTIONS, 'section_key' => "part_{$part}"],
                ['is_completed' => true, 'completed_at' => now()]
            );

            // セッションに結果IDを保存してからクリア
            session([
                'toeic_result_id'           => $result->id,
                "toeic_answers_{$part}"     => null,
                "toeic_practice_{$part}"    => null,
            ]);
        });

        return redirect()->route('english.toeic.result', ['part' => $part]);
    }

    /**
     * TOEIC 結果 (S05)
     * GET /english/toeic/{part}/result
     */
    public function result(int $part)
    {
        $resultId = session('toeic_result_id');

        if (!$resultId) {
            return redirect()->route('english.toeic.practice', ['part' => $part]);
        }

        $result = ToeicResult::with([
            'answerLogs.question.options',
            'answerLogs.selectedOption',
        ])->findOrFail($resultId);

        session()->forget('toeic_result_id');

        $wrongAnswers = $result->answerLogs
            ->where('is_correct', false)
            ->map(fn($log) => [
                'question'        => $log->question?->question_text ?? '',
                'your_answer'     => $log->selectedOption?->option_text ?? '',
                'correct_answer'  => $log->question?->options->firstWhere('is_correct', true)?->option_text ?? '',
                'explanation'     => $log->question?->explanation ?? '',
            ])->values()->all();

        $levelInfo = $this->xpService->getLevelInfo(Auth::user());

        return view('english.toeic.result', compact('part', 'result', 'wrongAnswers', 'levelInfo'));
    }
}
