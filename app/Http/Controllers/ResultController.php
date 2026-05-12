<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResultController extends Controller
{
    public function index(): View
    {
        $results = Result::query()
            ->orderBy('created_at')
            ->with(['user', 'test'])
            ->get();

        return view('results.index', [
            'results' => $results
        ]);
    }
}
