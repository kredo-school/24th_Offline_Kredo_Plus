<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EarthController extends Controller
{
    public function index()
    {
        return view('earth.index');
    }
}
