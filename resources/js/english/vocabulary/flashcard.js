import { post }      from '../utils/api.js';
import { showToast } from '../utils/toast.js';

/**
 * Alpine.data component for Flashcard (S13).
 *
 * Config:
 *   words           – [{id, word, pos, meaning, example, example_ja}]
 *   level           – slug string e.g. 'toeic-700'
 *   favoriteIds     – number[] of already-favorited word IDs
 *   toggleFavoriteUrl – POST /english/vocabulary/favorite
 *   progressUrl       – POST /english/vocabulary/progress
 */
export function flashcardApp(config) {
    return {
        words:            config.words ?? [],
        level:            config.level,
        favoritesOnly:    config.favoritesOnly ?? false,
        favoriteIds:      [...(config.favoriteIds ?? [])],
        toggleFavoriteUrl: config.toggleFavoriteUrl,
        progressUrl:       config.progressUrl,

        currentIndex: 0,
        isFlipped:    false,
        learned:      [],
        isDone:       false,
        // 完了時にサーバから返る獲得XP・ユーザーレベル（完了画面で表示）
        gainedXp:     0,
        userLevel:    null,
        totalXp:      null,
        xpInLevel:    null,
        barPercent:   0,
        // フラッシュカードを開いた時刻（＝このコンポーネント生成時）から計測し、
        // 10問完了時にcomplete()で経過秒数をduration_secondsとして送信する。
        startTime:    Date.now(),

        get current()  { return this.words[this.currentIndex]; },
        get progress() {
            return this.words.length > 0
                ? Math.round((this.currentIndex / this.words.length) * 100)
                : 0;
        },
        isFavorite(id) { return this.favoriteIds.includes(id); },

        flip() { this.isFlipped = !this.isFlipped; },

        async toggleFavorite() {
            const id = this.current.id;
            try {
                const data = await post(this.toggleFavoriteUrl, { word_id: id });
                if (data.is_favorite) {
                    if (!this.favoriteIds.includes(id)) this.favoriteIds.push(id);
                } else {
                    this.favoriteIds = this.favoriteIds.filter(f => f !== id);
                }
            } catch {
                showToast('お気に入りの更新に失敗しました', 'error');
            }
        },

        markLearned() {
            if (!this.learned.includes(this.current.id)) {
                this.learned.push(this.current.id);
            }
            this.next();
        },

        async next() {
            if (this.currentIndex < this.words.length - 1) {
                this.currentIndex++;
                this.isFlipped = false;
            } else {
                await this.complete();
            }
        },

        async complete() {
            const durationSeconds = this.startTime
                ? Math.round((Date.now() - this.startTime) / 1000)
                : 0;
            try {
                const data = await post(this.progressUrl, {
                    level:            this.level,
                    is_completed:     true,
                    favorites_only:   this.favoritesOnly,
                    learned_word_ids: this.learned,
                    seen_word_ids:    this.words.map(w => w.id),
                    duration_seconds: durationSeconds,
                });
                this.gainedXp    = data.gained_xp ?? 30;
                this.userLevel   = data.level ?? null;
                this.totalXp     = data.total_xp ?? null;
                this.xpInLevel   = data.xp_in_level ?? null;
                this.barPercent  = data.bar_percent ?? 0;
            } catch {
                // Non-critical: progress update failure doesn't block UI
                this.gainedXp = 30;
            }
            this.isDone = true;
        },

        restart() {
            this.currentIndex = 0;
            this.isFlipped    = false;
            this.learned      = [];
            this.isDone       = false;
            this.gainedXp     = 0;
            this.userLevel    = null;
            this.totalXp      = null;
            this.xpInLevel    = null;
            this.barPercent   = 0;
            this.startTime    = Date.now();
        },
    };
}
