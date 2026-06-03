<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestStoreRequest;
use App\Models\QuestionLevel;
use App\Models\Test;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
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

    public function create(): View
    {
        $topics = Topic::all();
        $levels = QuestionLevel::all();

        return view('tests.create', [
            'topics' => $topics,
            'levels' => $levels
        ]);
    }

    public function edit(Test $test): View
    {
        $topics = Topic::all();
        $levels = QuestionLevel::all();

        return view('tests.edit', [
            'test' => $test,
            'topics' => $topics,
            'levels' => $levels
        ]);
    }

    public function store(TestStoreRequest $testStoreRequest): RedirectResponse
    {
        $validated = $testStoreRequest->validated();

        $test = Test::query()->create($validated);

        return redirect()
            ->route('tests.index')
            ->with('success', "Тест успешно создан!");

    }

    public function show(Test $test): View
    {
        return view('tests.show', ['test' => $test]);
    }

    public function update(TestStoreRequest $request, Test $test): RedirectResponse
    {
        $validated = $request->validated();

        $test->update($validated);

        return redirect()
            ->route('tests.index')
            ->with('success', "Тест '{$test->id}' успешно обновлен!");
    }

    public function destroy(Test $test): RedirectResponse
    {
        $testId = $test->id;

        if ($test->questions()->exists()) {
            return redirect()
                ->route('tests.index')
                ->with('error', "Нельзя удалить тест '{$testId}', так как он содержит вопросы!");
        }

        $test->delete();

        return redirect()
            ->route('tests.index')
            ->with('success', "Тест '{$testId}' успешно удален!");
    }

    public function restore($id): RedirectResponse
    {
        $test = Test::withTrashed()
            ->findOrFail($id);

        $testId = $test->id;

        if ($test->trashed()) {
            $test->restore();
            return redirect()
                ->route('tests.index')
                ->with('success', "Тест '{$testId}' успешно восстановлен!");
        }

        return redirect()
            ->route('tests.index')
            ->with('success', "Тест '{$testId}' не удалялся!");
    }

    public function forceDestroy($id): RedirectResponse
    {
        $test = Test::withTrashed()
            ->findOrFail($id);

        $testId = $test->id;

        if ($test->trashed()) {
            $test->forceDelete();
            return redirect()
                ->route('tests.index')
                ->with('success', "Тест '{$testId}' успешно удален из корзины!");
        }

        return redirect()
            ->route('tests.index')
            ->with('success', "Тест '{$testId}' не находится в корзине!");
    }

    public function trashed(): View
    {
        $tests = Test::onlyTrashed()->orderBy('id')->get();
        return view('tests.trashed', ['tests' => $tests]);
    }
}
