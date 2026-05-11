<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $query = Test::query();

        return view('home', ['tests' => $query
            ->with(['topic', 'question_level'])
            ->get()
        ]);
    }
}
