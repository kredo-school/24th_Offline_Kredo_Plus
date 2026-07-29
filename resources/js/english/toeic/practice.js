import { post, submitForm } from '../utils/api.js';
import { showToast }        from '../utils/toast.js';

/**
 * Alpine.data component for TOEIC practice (S04).
 *
 * Config (passed from Blade via x-data):
 *   questions   – array of { id, question_text, explanation, options: [{id, label, option_text}] }
 *   submitUrl   – POST /english/toeic/{part}/answer  → JSON {is_correct, correct_option_id, explanation}
 *   completeUrl – POST /english/toeic/{part}/complete → redirect to result page
 *   resultUrl   – GET  /english/toeic/{part}/result
 */
export function toeicPractice(config) {
    return {
        questions:       config.questions ?? [],
        submitUrl:       config.submitUrl,
        completeUrl:     config.completeUrl,
        resultUrl:       config.resultUrl,

        currentIndex:    0,
        selectedId:      null,   // currently selected option ID (numeric)
        isAnswered:      false,
        isCorrect:       false,
        correctOptionId: null,
        explanation:     '',
        score:           0,
        isComplete:      false,
        isLoading:       false,

        get current()         { return this.questions[this.currentIndex]; },
        get progressPercent() {
            return this.questions.length > 0
                ? Math.round((this.currentIndex / this.questions.length) * 100)
                : 0;
        },

        init() {
            // ChromeはgetVoices()が非同期に読み込まれ、初回は空配列を返すことがあるため、
            // 読み込み完了後に一度だけ再生し直して指定ボイスを反映させる
            if ('speechSynthesis' in window && window.speechSynthesis.getVoices().length === 0) {
                window.speechSynthesis.onvoiceschanged = () => this.autoPlayListening();
            }
            this.autoPlayListening();
            this.$watch('currentIndex', () => this.autoPlayListening());
        },

        selectOption(optionId) {
            if (!this.isAnswered) this.selectedId = optionId;
        },

        getPreferredVoice() {
            return window.speechSynthesis.getVoices().find(voice => voice.name === 'Google US English');
        },

        speak(text) {
            if (!('speechSynthesis' in window)) return;
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-US';
            // ブラウザ既定のrate=1は速すぎるため、TOEIC本番相当（約140〜160 WPM）に近づける
            utterance.rate = 0.85;
            utterance.voice = this.getPreferredVoice();
            window.speechSynthesis.speak(utterance);
        },

        stopAudio() {
            if ('speechSynthesis' in window) window.speechSynthesis.cancel();
        },

        // Part1（写真描写問題）は本番の音声形式に合わせ、選択肢ごとに
        // 文字（A〜D）を読み上げてから一呼吸おいて文章を読み上げる
        repeatAudio() {
            if (!('speechSynthesis' in window) || !this.current?.options) return;
            this.stopAudio();
            // Chromeはcancel()の直後にspeak()すると正しく停止・再生できないことがあるため、
            // 少し間を空けてから読み上げを開始する
            setTimeout(() => {
                this.current.options.forEach(option => {
                    this.speak(`${option.label}...`);
                    this.speak(option.option_text);
                });
            }, 50);
        },

        autoPlayListening() {
            if (this.current?.image_url) this.repeatAudio();
        },

        optionClass(optionId) {
            if (this.isAnswered && optionId === this.correctOptionId) {
                return 'border-green-500 bg-green-50 text-green-800';
            }
            if (this.isAnswered && this.selectedId === optionId && optionId !== this.correctOptionId) {
                return 'border-error bg-error-container/50 text-error';
            }
            if (this.selectedId === optionId && !this.isAnswered) {
                return 'border-primary bg-primary/10';
            }
            return 'border-outline-variant bg-surface-container-lowest';
        },

        async submitAnswer() {
            if (!this.selectedId || this.isAnswered || this.isLoading) return;
            this.isLoading = true;
            try {
                const data = await post(this.submitUrl, {
                    question_id:        this.current.id,
                    selected_option_id: this.selectedId,
                });
                this.isAnswered      = true;
                this.isCorrect       = data.is_correct;
                this.correctOptionId = data.correct_option_id;
                this.explanation     = data.explanation ?? '';
                if (data.is_correct) this.score++;
            } catch (err) {
                showToast(err.status === 422 ? 'バリデーションエラーが発生しました' : '回答の送信に失敗しました', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async nextQuestion() {
            if (!this.isAnswered) return;
            this.stopAudio();
            if (this.currentIndex < this.questions.length - 1) {
                this.currentIndex++;
                this.selectedId      = null;
                this.isAnswered      = false;
                this.isCorrect       = false;
                this.correctOptionId = null;
                this.explanation     = '';
            } else {
                this.isComplete = true;
                // Use form submit so the server-side redirect is followed naturally
                submitForm(this.completeUrl);
            }
        },
    };
}
