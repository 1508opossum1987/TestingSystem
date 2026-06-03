<?php

namespace App\Http\Controllers;

use App\Models\QuestionLevel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionLevelController extends Controller
{
    //Возможно не пригодится
    public function index(): View
    {
        $levels = QuestionLevel::query()
            ->orderBy('question_level')
            ->get();

        return view('question_levels.index', [
            'levels' => $levels
        ]);
    }
}
