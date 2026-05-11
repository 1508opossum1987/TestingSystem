<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function index(): View
    {
        $questions = Question::query()
            ->orderBy('created_at')
            ->with(['topic', 'questionLevel'])
            ->get();

        return view('questions.index', [
            'questions' => $questions
        ]);
    }
}
