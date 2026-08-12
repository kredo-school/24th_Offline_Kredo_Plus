{{-- シャワー情報投稿 --}}
<div x-data="{ open: false }">

    <button
        @click="open = true"
        class="w-auto rounded-full text-center bg-sky-200 text-sky-700 hover:bg-sky-300 transition-colors font-bold p-3 shadow-md hover:shadow-lg"
    >
        シャワー情報を投稿する
    </button>

    <div
        x-show="open"
        x-transition
        class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center"
    >
        <div
            @click.outside="open = false"
            class="relative overflow-hidden bg-white rounded-[24px] p-6 w-106"
        >

            <div class="absolute top-0 inset-x-0 h-1.5 rounded-t-[24px] bg-gradient-to-r from-sky-400 to-brand-blue"></div>

            <h2 class="text-xl font-bold mb-4 text-slate-800 text-center">
                シャワー情報投稿
            </h2>

            <div class="flex justify-center gap-2">
                <form action="{{ route('shower.report.store') }}" method="POST">
                    @csrf
                    
                    <p class="text-slate-500 text-sm font-semibold">シャワー番号</p>
                    <div class="flex items-center mt-2 mb-4 gap-1">
                        @for ($i = 1; $i <= 7; $i++)
                            <div>
                                <input id="shower-{{$i}}" type="radio" name="shower_number" value="{{$i}}" class="peer hidden">
                                <label for="shower-{{$i}}" class="flex rounded-full w-10 h-10 border-2 border-blue-300 text-md font-semibold text-blue-400 items-center justify-center cursor-pointer transition-transform duration-200 hover:scale-110 active:scale-95 peer-checked:bg-blue-300 peer-checked:text-white">{{$i}}</label>
                            </div>
                        @endfor
                    </div>

                    {{-- 水温 --}}
                    <p class="text-slate-500 text-sm font-semibold">水温</p>
                    <div class="rating-slider mt-1 mb-4" data-role="temperature" data-value="50">
                        <input type="hidden" name="temperature" value="50" data-role-input>
                        <div class="rating-track relative h-8 w-full flex items-center cursor-pointer select-none">
                            <div class="absolute inset-x-0 h-2 rounded-full overflow-hidden bg-slate-200">
                                <div class="absolute inset-y-0 left-0 rounded-full overflow-hidden" data-role-fill-wrap style="width:0%">
                                    <div class="absolute inset-y-0 left-0 opacity-90" data-role-fill-gradient style="width:0px"></div>
                                </div>
                            </div>
                            <div class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 w-6 h-6 bg-white border-2 border-blue-300 rounded-full shadow-md transition-shadow" data-role-thumb></div>
                        </div>
                        <div class="flex justify-between w-full mt-1">
                            <span class="text-xs text-slate-400 transition-colors" data-role-label-zone="q1">冷たい</span>
                            <span class="text-xs text-slate-400 transition-colors" data-role-label-zone="q2">ぬるい</span>
                            <span class="text-xs text-slate-400 transition-colors" data-role-label-zone="q3">温かい</span>
                            <span class="text-xs text-slate-400 transition-colors" data-role-label-zone="q4">熱い</span>
                        </div>
                    </div>

                    {{-- 水圧 --}}
                    <p class="text-slate-500 text-sm font-semibold">水圧</p>
                    <div class="rating-slider mt-1 mb-4" data-role="pressure" data-value="50">
                        <input type="hidden" name="pressure" value="50" data-role-input>
                        <div class="rating-track relative h-8 w-full flex items-center cursor-pointer select-none">
                            <div class="absolute inset-x-0 h-2 rounded-full overflow-hidden bg-slate-200">
                                <div class="absolute inset-y-0 left-0 rounded-full overflow-hidden" data-role-fill-wrap style="width:0%">
                                    <div class="absolute inset-y-0 left-0 opacity-90" data-role-fill-gradient style="width:0px"></div>
                                </div>
                            </div>
                            <div class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 w-6 h-6 bg-white border-2 border-blue-300 rounded-full shadow-md transition-shadow" data-role-thumb></div>
                        </div>
                        {{-- 3つ → 4つのラベルに変更 --}}
                        <div class="flex justify-between w-full mt-1">
                            <span class="text-xs text-slate-400 transition-colors" data-role-label-zone="q1">無し</span>
                            <span class="text-xs text-slate-400 transition-colors" data-role-label-zone="q2">弱い</span>
                            <span class="text-xs text-slate-400 transition-colors" data-role-label-zone="q3">普通</span>
                            <span class="text-xs text-slate-400 transition-colors" data-role-label-zone="q4">強い</span>
                        </div>
                    </div>

                    <p class="text-slate-500 text-sm font-semibold">コメント</p>
                    <textarea name="comment" rows="3" class="mt-2 rounded-md border-2 border-slate-300 w-full"></textarea>

                    <div class="flex justify-center gap-2 mt-4">
                        <button
                        @click="open = false"
                        class="rounded-full px-5 py-2.5 text-sm font-semibold text-slate-400 transition-all hover:bg-slate-100 hover:text-slate-600"
                        >
                        キャンセル
                        </button>

                        <button type="submit"
                        class="rounded-full bg-sky-400 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-sky-500 hover:shadow-md"
                        >
                        投稿する
                        </button>
                    </div>
                </form>

                <script>
                (function () {
                function lerp(a, b, t) { return a + (b - a) * t; }
                function lerpColor(hexA, hexB, t) {
                    const a = [1,3,5].map(i => parseInt(hexA.slice(i, i+2), 16));
                    const b = [1,3,5].map(i => parseInt(hexB.slice(i, i+2), 16));
                    return `rgb(${a.map((v,i) => Math.round(lerp(v, b[i], t))).join(",")})`;
                }

                function colorAtStops(stops, pct) {
                    const p = Math.max(0, Math.min(100, pct));
                    if (p <= stops[0].pos) return stops[0].color;
                    if (p >= stops[stops.length - 1].pos) return stops[stops.length - 1].color;
                    for (let i = 0; i < stops.length - 1; i++) {
                    const a = stops[i], b = stops[i + 1];
                    if (p >= a.pos && p <= b.pos) {
                        const t = (p - a.pos) / (b.pos - a.pos);
                        return lerpColor(a.color, b.color, t);
                    }
                    }
                }

                function buildGradientCSS(stops) {
                    const parts = stops.map((s) => `${s.color} ${s.pos}%`);
                    return `linear-gradient(to right, ${parts.join(", ")})`;
                }

                const stopsByRole = {
                    temperature: {
                    stops: [
                        { pos: 12.5, color: "#60a5fa" }, // 冷たい
                        { pos: 37.5, color: "#34d399" }, // ぬるい(緑)
                        { pos: 62.5, color: "#fbbf24" }, // 温かい
                        { pos: 87.5, color: "#ef4444" }, // 熱い
                    ]
                    },
                    // ★変更: 3分割 → 4分割に。各ゾーンの中心(12.5/37.5/62.5/87.5%)に色を配置
                    pressure: {
                    stops: [
                        { pos: 12.5, color: "#eff6ff" }, // 無し(白っぽい水色)
                        { pos: 37.5, color: "#93c5fd" }, // 弱い
                        { pos: 62.5, color: "#3b82f6" }, // 普通
                        { pos: 87.5, color: "#1e3a8a" }, // 強い(濃紺)
                    ]
                    }
                };

                // ★変更: 4分割の判定関数を1つに統一(水温・水圧どちらもこれを使う)
                function zoneAt4(pct) {
                    if (pct < 25) return "q1";
                    if (pct < 50) return "q2";
                    if (pct < 75) return "q3";
                    return "q4";
                }

                function setupSlider(card) {
                    const cfg = stopsByRole[card.dataset.role];
                    const track = card.querySelector(".rating-track");
                    const thumb = card.querySelector("[data-role-thumb]");
                    const input = card.querySelector("[data-role-input]");
                    const fillWrap = card.querySelector("[data-role-fill-wrap]");
                    const fillGradient = card.querySelector("[data-role-fill-gradient]");

                    if (fillGradient) {
                    fillGradient.style.backgroundImage = buildGradientCSS(cfg.stops);
                    }

                    const labelSpans = {};
                    card.querySelectorAll("[data-role-label-zone]").forEach((span) => {
                    labelSpans[span.dataset.roleLabelZone] = span;
                    });

                    let value = parseInt(card.dataset.value, 10);

                    function render() {
                    const pct = Math.max(0, Math.min(100, value));
                    const color = colorAtStops(cfg.stops, pct);
                    const activeZone = zoneAt4(pct);

                    thumb.style.left = pct + "%";
                    thumb.style.borderColor = color;

                    Object.entries(labelSpans).forEach(([zone, span]) => {
                        if (zone === activeZone) {
                        span.style.color = color;
                        span.classList.add("font-bold");
                        span.classList.remove("text-slate-400");
                        } else {
                        span.style.color = "";
                        span.classList.remove("font-bold");
                        span.classList.add("text-slate-400");
                        }
                    });

                    if (fillWrap && fillGradient) {
                        fillWrap.style.width = pct + "%";
                        fillGradient.style.width = track.getBoundingClientRect().width + "px";
                    }
                    input.value = pct;
                    card.dataset.value = pct;
                    }

                    function pctFromEvent(clientX) {
                    const rect = track.getBoundingClientRect();
                    return Math.max(0, Math.min(100, Math.round(((clientX - rect.left) / rect.width) * 100)));
                    }
                    function onMove(e) {
                    value = pctFromEvent(e.touches ? e.touches[0].clientX : e.clientX);
                    render();
                    }
                    function onUp() {
                    window.removeEventListener("pointermove", onMove);
                    window.removeEventListener("pointerup", onUp);
                    }
                    track.addEventListener("pointerdown", (e) => {
                    onMove(e);
                    window.addEventListener("pointermove", onMove);
                    window.addEventListener("pointerup", onUp);
                    });

                    render();
                }

                document.querySelectorAll(".rating-slider").forEach(setupSlider);
                })();
                </script>
            </div>
        </div>
    </div>
</div>