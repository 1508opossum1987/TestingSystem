<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class TopicController extends Controller
{
    public function index(): View
    {
        $topics = Topic::query()
            ->orderBy('name');

        return view(
            'brands.index', ['brands' => $brands]
        );
    }
}
