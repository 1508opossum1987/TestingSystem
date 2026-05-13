<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicStoreRequest;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

    public function create(): View
    {
        return view('topics.create');
    }

    public function store(TopicStoreRequest $topicStoreRequest): RedirectResponse
    {
        $validated = $topicStoreRequest->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $topic = Topic::query()->create($validated);

        return redirect()
            ->route('topics.index')
            ->with('success', "Тема '{$topic->name}' успешно создана!");

    }



}
