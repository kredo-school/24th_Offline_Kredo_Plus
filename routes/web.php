<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\English\Content\LearningContentController;
use App\Http\Controllers\English\HubController;
use App\Http\Controllers\English\Ielts\IeltsController;
use App\Http\Controllers\English\ProgressController as EnglishProgressController;
use App\Http\Controllers\English\Quiz\QuizController;
use App\Http\Controllers\English\RankingController as EnglishRankingController;
use App\Http\Controllers\English\Toeic\ToeicController;
use App\Http\Controllers\English\Typing\TypingController;
use App\Http\Controllers\English\Vocabulary\VocabularyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarenderiaController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\RestaurantCafeController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // English
    Route::prefix('english')->name('english.')->group(function () {

        // ── Hub ──────────────────────────────────────────────────────────
        Route::get('/', [HubController::class, 'index'])->name('hub');

        // ── TOEIC ────────────────────────────────────────────────────────
        Route::prefix('toeic')->name('toeic.')->group(function () {
            Route::get('/',                                  [ToeicController::class, 'index'])->name('index');
            Route::get('/{part}/slides',                     [ToeicController::class, 'slides'])->name('slides')->whereIn('part', config('english.toeic_parts'));
            Route::get('/{part}/slides/{step}',              [ToeicController::class, 'slides'])->name('slides.step')->whereIn('part', config('english.toeic_parts'))->whereNumber('step');
            Route::post('/{part}/slides/complete',           [ToeicController::class, 'completeSlides'])->name('slides.complete')->whereIn('part', config('english.toeic_parts'));
            Route::get('/{part}/practice',                   [ToeicController::class, 'practice'])->name('practice')->whereIn('part', config('english.toeic_parts'));
            Route::post('/{part}/answer',                    [ToeicController::class, 'submitAnswer'])->name('answer')->whereIn('part', config('english.toeic_parts'));
            Route::post('/{part}/complete',                  [ToeicController::class, 'complete'])->name('complete')->whereIn('part', config('english.toeic_parts'));
            Route::get('/{part}/result',                     [ToeicController::class, 'result'])->name('result')->whereIn('part', config('english.toeic_parts'));
        });

        // ── IELTS ─────────────────────────────────────────────────────────
        Route::prefix('ielts')->name('ielts.')->group(function () {
            Route::get('/',                                                                 [IeltsController::class, 'index'])->name('index');
            Route::get('/speaking/{part}/topic',                                            [IeltsController::class, 'topic'])->name('topic')->whereIn('part', config('english.ielts_parts'));
            Route::get('/speaking/{part}/{topic}/score',                                    [IeltsController::class, 'score'])->name('score')->whereIn('part', config('english.ielts_parts'))->whereIn('topic', config('english.ielts_topics'));
            Route::get('/speaking/{part}/{topic}/{score}/slides',                           [IeltsController::class, 'slides'])->name('slides')->whereIn('part', config('english.ielts_parts'))->whereIn('topic', config('english.ielts_topics'))->whereIn('score', config('english.ielts_scores'));
            Route::get('/speaking/{part}/{topic}/{score}/slides/{step}',                    [IeltsController::class, 'slides'])->name('slides.step')->whereIn('part', config('english.ielts_parts'))->whereIn('topic', config('english.ielts_topics'))->whereIn('score', config('english.ielts_scores'))->whereNumber('step');
            Route::post('/speaking/{part}/{topic}/{score}/slides/complete',                 [IeltsController::class, 'completeSlides'])->name('slides.complete')->whereIn('part', config('english.ielts_parts'))->whereIn('topic', config('english.ielts_topics'))->whereIn('score', config('english.ielts_scores'));
            Route::get('/speaking/{part}/{topic}/{score}/typing',                           [IeltsController::class, 'typing'])->name('typing')->whereIn('part', config('english.ielts_parts'))->whereIn('topic', config('english.ielts_topics'))->whereIn('score', config('english.ielts_scores'));
            Route::post('/speaking/{part}/{topic}/{score}/result',                          [IeltsController::class, 'storeResult'])->name('result.store')->whereIn('part', config('english.ielts_parts'))->whereIn('topic', config('english.ielts_topics'))->whereIn('score', config('english.ielts_scores'));
            Route::get('/speaking/{part}/{topic}/{score}/result',                           [IeltsController::class, 'result'])->name('result')->whereIn('part', config('english.ielts_parts'))->whereIn('topic', config('english.ielts_topics'))->whereIn('score', config('english.ielts_scores'));
        });

        // ── 英単語 ────────────────────────────────────────────────────────
        Route::prefix('vocabulary')->name('vocabulary.')->group(function () {
            Route::get('/',                              [VocabularyController::class, 'index'])->name('index');
            Route::get('/favorites',                     [VocabularyController::class, 'favorites'])->name('favorites');
            Route::post('/favorite',                     [VocabularyController::class, 'toggleFavorite'])->name('favorite');
            Route::post('/progress',                     [VocabularyController::class, 'updateProgress'])->name('progress');
            Route::get('/{level}/flashcard',             [VocabularyController::class, 'flashcard'])->name('flashcard')->whereIn('level', config('english.vocabulary_level_slugs'));
            Route::get('/{level}/spelling',              [VocabularyController::class, 'spelling'])->name('spelling')->whereIn('level', config('english.vocabulary_level_slugs'));
            Route::post('/{level}/spelling/check',       [VocabularyController::class, 'checkSpelling'])->name('spelling.check')->whereIn('level', config('english.vocabulary_level_slugs'));
            Route::post('/{level}/spelling/complete',    [VocabularyController::class, 'completeSpelling'])->name('spelling.complete')->whereIn('level', config('english.vocabulary_level_slugs'));
            Route::get('/{level}/quiz',                  [VocabularyController::class, 'quiz'])->name('quiz')->whereIn('level', config('english.vocabulary_level_slugs'));
            Route::post('/{level}/quiz/submit',          [VocabularyController::class, 'submitQuiz'])->name('quiz.submit')->whereIn('level', config('english.vocabulary_level_slugs'));
        });

        // ── タイピング（単独） ─────────────────────────────────────────────
        Route::prefix('typing')->name('typing.')->group(function () {
            Route::get('/',                        [TypingController::class, 'index'])->name('index');
            Route::get('/category/{category}/practice', [TypingController::class, 'randomPractice'])->name('category.practice');
            Route::get('/{id}/slides',             [TypingController::class, 'slides'])->name('slides')->whereNumber('id');
            Route::get('/{id}/slides/{step}',      [TypingController::class, 'slides'])->name('slides.step')->whereNumber('id')->whereNumber('step');
            Route::post('/{id}/slides/complete',   [TypingController::class, 'completeSlides'])->name('slides.complete')->whereNumber('id');
            Route::get('/{id}/practice',           [TypingController::class, 'practice'])->name('practice')->whereNumber('id');
            Route::post('/{id}/result',            [TypingController::class, 'storeResult'])->name('result.store')->whereNumber('id');
            Route::get('/{id}/result',             [TypingController::class, 'result'])->name('result')->whereNumber('id');
        });

        // ── クイズ ────────────────────────────────────────────────────────
        Route::prefix('quiz')->name('quiz.')->group(function () {
            Route::get('/',                        [QuizController::class, 'index'])->name('index');
            Route::get('/spelling',                [QuizController::class, 'spelling'])->name('spelling');
            Route::post('/spelling/submit',        [QuizController::class, 'submitSpelling'])->name('spelling.submit');
            Route::get('/vocabulary',              [QuizController::class, 'vocabulary'])->name('vocabulary');
            Route::post('/vocabulary/submit',      [QuizController::class, 'submitVocabulary'])->name('vocabulary.submit');
        });

        // ── 試験概要 ──────────────────────────────────────────────────────
        Route::prefix('overview')->name('overview.')->group(function () {
            Route::get('/',        [LearningContentController::class, 'overviewIndex'])->name('index');
            Route::get('/{exam}',  [LearningContentController::class, 'overviewShow'])->name('show')->whereIn('exam', config('english.exam_types'));
        });

        // ── 学習ストラテジー ──────────────────────────────────────────────
        Route::prefix('strategy')->name('strategy.')->group(function () {
            Route::get('/',               [LearningContentController::class, 'strategyIndex'])->name('index');
            Route::get('/{exam}/{level}', [LearningContentController::class, 'strategyShow'])->name('show')->whereIn('exam', config('english.exam_types'));
        });
        // ── 学習管理・ランキング ──────────────────────────────────────────
        Route::get('/progress', [EnglishProgressController::class, 'index'])->name('progress');
        Route::get('/ranking',  [EnglishRankingController::class, 'index'])->name('ranking');
    Route::group(['prefix' => 'english', 'as' => 'english.'], function () {
        Route::get('/index', [EnglishController::class, 'index'])->name('index');
    });
    // Shower

    // Information (編集・削除はログイン必須のためこちらに配置)
    Route::prefix('information/restaurant-cafe')->name('restaurant-cafe.')->group(function () {
        Route::get('/{post}/edit', [RestaurantCafeController::class, 'edit'])->name('edit');
        Route::put('/{post}', [RestaurantCafeController::class, 'update'])->name('update');
        Route::delete('/{post}', [RestaurantCafeController::class, 'destroy'])->name('destroy');
        Route::get('/{post}', [RestaurantCafeController::class, 'show'])->name('show');
    });
});

//下記コードデフォルトのままです。
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// information
Route::prefix('information')->group(function () {
    Route::get('/carenderia', [CarenderiaController::class, 'index'])->name('carenderia.index');
    Route::get('/restaurant-cafe', [RestaurantCafeController::class, 'index'])->name('restaurant-cafe.index');
    Route::get('/travel', [TravelController::class, 'index'])->name('travel.index');
    Route::get('/travel/{slug}', [TravelController::class, 'show'])->name('travel.show');
    Route::get('/other', [OtherController::class, 'index'])->name('other.index');
});

require __DIR__ . '/auth.php';
