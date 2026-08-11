<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuggestionBoxController extends Controller
{
    public function suggestion() 
    {
       return view('suggestion-box.suggestion-form');
    }
}
