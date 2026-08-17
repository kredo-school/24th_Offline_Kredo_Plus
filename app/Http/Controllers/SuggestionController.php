<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuggestionRequest;
use App\Models\Suggestion;

class SuggestionController extends Controller
{
    public function create()
    {
        return view('suggestion.create');
    }

    public function store(StoreSuggestionRequest $request)
    {
        Suggestion::create([
            'user_id' => $request->user()->id,
            'category' => $request->validated('category'),
            'comment' => $request->validated('comment'),
        ]);

        return back()->with('success', 'ご意見を送信しました。ありがとうございます。');
    }
}
