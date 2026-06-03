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

    public function edit(Topic $topic): View
    {
        return view('topics.edit', ['topic' => $topic]);
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

    public function show(Topic $topic): View
    {
        return view('topics.show', ['topic' => $topic]);
    }

    public function update(TopicStoreRequest $request, Topic $topic): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['name'] !== $topic->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        try {
            $topic->name = $validated['name'];
        } catch (\Exception $exception) {
            abort(500, $exception->getMessage());
        }

        return redirect()
            ->route('topics.index')
            ->with('success', "Тема '{$topic->name}' успешно обновлена!");
    }

    public function destroy(Topic $topic): RedirectResponse
    {
        $topicName = $topic->name;

        if ($topic->questions()->exists()) {
            return redirect()
                ->route('topics.index')
                ->with('error', "Нельзя удалить тему '{$topicName}', так как у нее есть вопросы!");
        }

        $topic->delete();

        return redirect()
            ->route('topics.index')
            ->with('success', "Тема '{$topicName}' успешно удалена!");
    }

    public function restore($id): RedirectResponse
    {
        $topic = Topic::withTrashed()
            ->findOrFail($id);

        $topicName = $topic->name;

        if ($topic->trashed()) {
            $topic->restore();
            return redirect()
                ->route('topics.index')
                ->with('success', "Тема '{$topicName}' успешно восстановлена!");
        }

        return redirect()
            ->route('topics.index')
            ->with('success', "Тема '{$topicName}' не удалялась!");
    }

    public function forceDestroy($id): RedirectResponse
    {
        $topic = Topic::withTrashed()
            ->findOrFail($id);

        $topicName = $topic->name;

        if ($topic->trashed()) {
            $topic->forceDelete();
            return redirect()
                ->route('topics.index')
                ->with('success', "Тема '{$topicName}' успешно удалена из корзины!");
        }

        return redirect()
            ->route('topics.index')
            ->with('success', "Тема '{$topicName}' не находится в корзине!");
    }

    public function trashed(): View
    {
        $topics = Topic::onlyTrashed()->orderBy('name')->get();
        return view('topics.trashed', ['topics' => $topics]);
    }
}
