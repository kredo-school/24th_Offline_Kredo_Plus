<?php
// Dashboard and English
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
// Shower
use App\Http\Controllers\ShowerController;
use App\Http\Controllers\GenderController;
use App\Http\Middleware\EnsureGenderIsSet;
use App\Http\Controllers\UserShowerPreferenceController;
use App\Http\Controllers\Shower\ShowerReportController;
use App\Http\Controllers\Shower\ShowerPriorityController;
use App\Http\Controllers\Shower\ShowerCapacityReportController;
use App\Http\Controllers\Shower\ShowerMalfunctionReportController;
use App\Http\Controllers\Shower\ShowerScatterDataController;
use App\Http\Controllers\Shower\ShowerTrendDataController;
use App\Http\Controllers\Shower\ShowerCommentController;
// Information
use App\Http\Controllers\Information\InformationController;
use App\Http\Controllers\Information\CarinderiaController;
use App\Http\Controllers\Information\TravelController;
use App\Http\Controllers\Information\OtherController;
use App\Http\Controllers\Information\RestaurantCafeController;
use App\Http\Controllers\Information\PostInteractionController;
use App\Http\Controllers\Information\MainCategoryPageController;
// Notification 通知
use App\Http\Controllers\NotificationController;
//Earth
use App\Http\Controllers\EarthController;
use App\Http\Controllers\EarthLocationController;
//Admin
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminMessageController;

// 目安箱
use App\Http\Controllers\SuggestionBoxController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\Admin\AdminSuggestionController;
// シャワー管理
use App\Http\Controllers\Admin\AdminShowerMalfunctionController;
use App\Models\Shower\ShowerMalfunctionReport;
use App\Models\Shower\ShowerCapacityReport;
use App\Models\Shower\ShowerReport;
// English
use App\Models\English\StudyLog;
use App\Models\Post;
use App\Models\User;


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
            Route::get('/{exam}',  [LearningContentController::class, 'overviewShow'])->name('show')->whereIn('exam', config('english.exam_types'));
        });

        // ── 学習ストラテジー ──────────────────────────────────────────────
        Route::prefix('strategy')->name('strategy.')->group(function () {
            Route::get('/',               [LearningContentController::class, 'strategyIndex'])->name('index');
            Route::get('/{exam}/{level}', [LearningContentController::class, 'strategyShow'])->name('show')->whereIn('exam', config('english.exam_types'));
        });
        // ── 学習管理・ランキング ──────────────────────────────────────────
        Route::get('/progress',             [EnglishProgressController::class, 'index'])->name('progress');
        Route::post('/progress/exam-date',  [EnglishProgressController::class, 'updateExamDate'])->name('progress.exam-date');
        Route::get('/ranking',  [EnglishRankingController::class, 'index'])->name('ranking');
    });

    // Shower
    // 性別登録
    Route::post('/gender', [GenderController::class, 'store'])->name('gender.store');

    // シャワーページに入る。性別未登録であれば弾かれる。
    Route::get('/shower', [ShowerController::class, 'entry'])
        ->middleware(EnsureGenderIsSet::class)
        ->name('shower.entry');

    // 男子寮ページ
    Route::get('/shower/male', [ShowerController::class, 'male'])
        ->middleware('gender:male')
        ->name('shower.male');

    // 女子寮ページ
    Route::get('/shower/female', [ShowerController::class, 'female'])
        ->middleware('gender:female')
        ->name('shower.female');

    // シャワーの好み
    Route::post('/shower/preference', [UserShowerPreferenceController::class, 'update'])->name('shower.preference.update');
    // 温度・水圧の重視項目
    Route::post('/shower/priority', [ShowerPriorityController::class, 'update'])->name('shower.priority.update');

    // シャワー状態管理
    // 投稿情報をゲット
    Route::post('/shower/report', [ShowerReportController::class, 'store'])->name('shower.report.store');
    // 満室報告
    Route::post('/shower/capacity', [ShowerCapacityReportController::class, 'store'])->name('shower.capacity.store');
    // 故障報告
    Route::post('/shower/malfunction', [ShowerMalfunctionReportController::class, 'store'])->name('shower.malfunction.store');

    // 統計表示
    // 散布図（二次元チャート）
    Route::get('/shower/scatter-data', ShowerScatterDataController::class)->name('shower.scatter-data');
    // パフォーマンストレンド（折れ線グラフ）
    Route::get('/shower/trend-data', ShowerTrendDataController::class)->name('shower.trend-data');
    // コメント
    Route::get('/shower/comments', ShowerCommentController::class)->name('shower.comments');

    // ============================================================
    // Information
    // 投稿の編集・更新・削除・詳細のロジックはInformationControllerに集約(セクションごとの重複を削減)。
    // ============================================================

    Route::get('/information/carinderia', [CarinderiaController::class, 'index'])->name('carinderia.index');
    Route::get('/information/restaurant-cafe', [RestaurantCafeController::class, 'index'])->name('restaurant-cafe.index');
    Route::get('/information/travel', [TravelController::class, 'index'])->name('travel.index');
    Route::get('/information/travel/{slug}', [TravelController::class, 'show'])->name('travel.show');
    Route::get('/information/other', [OtherController::class, 'index'])->name('other.index');

    Route::get('/information/{key}', [MainCategoryPageController::class, 'index'])
        ->name('information.dynamic')
        ->where('key', '(?!post$|posts$|carinderia$|restaurant-cafe$|travel$|other$)[a-z0-9\-]+');

    // Carinderia (restaurant-cafeと同じパターン)
    Route::prefix('information/carinderia')->name('carinderia.')->group(function () {
        Route::get('/{post}/edit', [CarinderiaController::class, 'edit'])->name('edit');
        Route::put('/{post}', [CarinderiaController::class, 'update'])->name('update');
        Route::delete('/{post}', [CarinderiaController::class, 'destroy'])->name('destroy');
        Route::get('/{post}', [CarinderiaController::class, 'show'])->name('show');
    });

    Route::prefix('information')->name('information.')->group(function () {
        Route::get('/{post}/edit', [InformationController::class, 'edit'])->name('edit')->whereNumber('post');
        Route::put('/{post}', [InformationController::class, 'update'])->name('update')->whereNumber('post');
        Route::delete('/{post}', [InformationController::class, 'destroy'])->name('destroy')->whereNumber('post');
        Route::get('/{post}', [InformationController::class, 'show'])->name('show')->whereNumber('post');
    });

    Route::prefix('information/travel')->name('travel.')->group(function () {
        Route::get('/post/{post}', [TravelController::class, 'showPost'])->name('post.show');
    });

    // いいね・コメント・お気に入り
    Route::prefix('information/posts')->name('posts.')->group(function () {
        Route::post('/{post}/like', [PostInteractionController::class, 'toggleLike'])->name('like');
        Route::post('/{post}/bookmark', [PostInteractionController::class, 'toggleBookmark'])->name('bookmark');
        Route::post('/{post}/comments', [PostInteractionController::class, 'storeComment'])->name('comments.store');
        Route::delete('/comments/{comment}', [PostInteractionController::class, 'destroyComment'])->name('comments.destroy');
    });

    //入力した後も情報を保持する
    Route::post('/information/draft',[InformationController::class, 'saveDraft'])->name('information.draft.save');

    // suggestion box  目安箱
    Route::get('/suggestion-box', [SuggestionBoxController::class, 'suggestion'])->name('suggestion');
    // 送信・管理
    Route::get('/suggestion', [SuggestionController::class, 'create'])->name('suggestion');
    Route::post('/suggestion', [SuggestionController::class, 'store'])->name('suggestion.store');
});  // auth, verified group

//Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// information
Route::prefix('information')->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/post', [InformationController::class, 'create'])->name('information.create');
        Route::post('/', [InformationController::class, 'store'])->name('information.store');
    });
    Route::get('/carinderia', [CarinderiaController::class, 'index'])->name('carinderia.index');
    Route::get('/restaurant-cafe', [RestaurantCafeController::class, 'index'])->name('restaurant-cafe.index');
    Route::get('/travel', [TravelController::class, 'index'])->name('travel.index');
    Route::get('/travel/{slug}', [TravelController::class, 'show'])->name('travel.show');
    Route::get('/other', [OtherController::class, 'index'])->name('other.index');
});

require __DIR__ . '/auth.php';

// Notification 通知
Route::get('/notifications', [NotificationController::class, 'data'])->name('notifications.data');
Route::post('/notifications/mark-seen', [NotificationController::class, 'markSeen'])->name('notifications.mark-seen');

// Earth
Route::get('/earth', [EarthController::class, 'index'])->name('earth');
Route::get('/earth/location/create', [EarthLocationController::class, 'create'])->name('earth.location.create');
Route::post('/earth/location', [EarthLocationController::class, 'store'])->name('earth.location.store');
Route::get('/earth/location/{earthLocation}/edit', [EarthLocationController::class, 'edit'])->name('earth.location.edit');
Route::put('/earth/location/{earthLocation}', [EarthLocationController::class, 'update'])->name('earth.location.update');

// Admin (管理者画面)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Controller 経由でデータを渡すように修正
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/notices', [AdminDashboardController::class, 'storeNotice'])->name('notices.store');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');

    // メッセージ保存用ルート
    Route::post('/messages', [AdminMessageController::class, 'store'])->name('messages.store');

    // メインカテゴリー保存用ルート
    Route::post('/main-categories', [AdminDashboardController::class, 'storeMainCategory'])->name('main-categories.store');

    // カテゴリー（サブカテゴリー）保存用ルート
    Route::post('/categories', [AdminDashboardController::class, 'storeCategory'])->name('categories.store');

    // シャワー故障報告の受け取り・修理報告
    Route::get('/shower/malfunctions', [AdminShowerMalfunctionController::class, 'index'])->name('shower.malfunctions.index');
    Route::post('/shower/malfunctions/{gender}/{showerNumber}/fix', [AdminShowerMalfunctionController::class, 'fix'])->name('shower.malfunctions.fix');

    // 目安箱受け取り
    Route::get('/suggestions/data', [AdminSuggestionController::class, 'data'])->name('suggestions.data');
    Route::patch('/suggestions/{suggestion}', [AdminSuggestionController::class, 'update'])->name('suggestions.update');
});