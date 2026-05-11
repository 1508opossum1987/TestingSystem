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
        $questionLevels = QuestionLevel::query()
            ->orderBy('level')
            ->get();

        return view('questionLevels.index', [
            'questionLevels' => $questionLevels
        ]);
    }
}
