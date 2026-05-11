<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestController extends Controller
{
    public function index(): View
    {
        $tests = Test::query()
            ->orderBy('created_at')
            ->with(['question_level', 'topic'])
            ->get();

        return view('tests.index', [
            'tests' => $tests
        ]);
    }
}
