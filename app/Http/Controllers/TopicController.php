<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TopicController extends Controller
{
    public function index(): View
    {
        $topics = Topic::query()
            ->orderBy('name')
            ->get();

        return view(
            'topics.index', ['topics' => $topics]
        );
    }
}
