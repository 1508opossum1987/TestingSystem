<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $tests = Test::with(['topic', 'question_level'])->get();

        return view('home', ['tests' => $tests]);
    }
}
