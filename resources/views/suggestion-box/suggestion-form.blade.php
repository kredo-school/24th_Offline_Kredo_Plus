@extends('layouts.app')
 
@section('title', 'suggestion form')
 
@section('content')
    <div class="min-h-screen bg-brand-red/10 py-12">
        <div class="relative bg-white rounded-[24px] shadow-card p-8 mx-auto max-w-lg overflow-hidden">
                {{-- 上部のグラデーションライン --}}
                <div class="absolute top-0 inset-x-0 h-1.5 kredo-bar"></div>

                
                <form method="POST" action="#">
                    @csrf
                    <h2 class="font-display font-bold text-4xl text-slate-700 text-center">目安箱</h2>
                    <p class="text-sm text-slate-600 text-center mt-2"><span class="wordmark-kredo font-bold">Kredo</span> <span class="wordmark-plus font-bold">Plus</span> はみなさんのご意見をお待ちしています。</p>
    
                    
                    {{-- カテゴリー --}}
                    <div class="mt-8 mb-6">
                        <label
                            class="block mb-2 text-sm font-bold text-blue-950"
                            for="inline-category"
                        >
                            カテゴリー
                        </label>

                        <div class="relative">
                            <select
                                id="inline-category"
                                name="category"
                                class="block w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-10 text-sm text-slate-700 shadow-sm outline-none transition-all focus:border-brand-blue focus:bg-white focus:ring-2 focus:ring-brand-blue/10"
                            >
                                <option value="problem">お困りごと</option>
                                <option value="request">ご要望</option>
                                <option value="other">その他</option>
                            </select>
                        </div>
                    </div>


                    {{-- コメント --}}
                    <div class="my-6">
                        <label
                            class="block mb-2 text-sm font-bold text-blue-950"
                            for="comment"
                        >
                            コメント
                        </label>

                        <textarea
                            name="comment"
                            id="comment"
                            rows="5"
                            placeholder="ご意見やご要望の内容をご入力ください。"
                            class="block w-full resize-none rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 shadow-sm outline-none transition-all focus:border-brand-blue focus:bg-white focus:ring-2 focus:ring-brand-blue/10"
                        ></textarea>
                    </div>


                    {{-- ボタン --}}
                    <div class="mt-8 flex justify-center gap-3">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-full bg-brand-blue px-6 py-2.5 text-sm font-semibold text-white shadow-soft transition-all hover:bg-indigo-700 hover:shadow-md"
                        >
                            <span class="material-symbols-outlined text-[18px]">
                                send
                            </span>
                            送信する
                        </button>
                    </div>
                </form>
            </div>
    </div>
@endsection
 