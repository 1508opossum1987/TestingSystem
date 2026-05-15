<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionStoreRequest;
use App\Http\Requests\TopicStoreRequest;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function index(): View
    {
        $questions = Question::query()
            ->orderBy('created_at')
            ->with(['topic', 'question_level'])
            ->get();

        return view('questions.index', [
            'questions' => $questions
        ]);
    }

    public function create(): View
    {
        return view('questions.create');
    }

    public function store(QuestionStoreRequest $questionStoreRequest): RedirectResponse
    {
        $validated = $questionStoreRequest->validated();

        $question = Question::query()->create($validated);

        return redirect()
            ->route('questions.index')
            ->with('success', "Вопрос '{$question->name}' успешно создан!");

    }

    public function show(Question $question): View
    {
        return view('questions.show', ['question' => $question]);
    }

    public function update(QuestionStoreRequest $request, Question $question): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $question->name = $validated['name'];
        } catch (\Exception $exception) {
            abort(500, $exception->getMessage());
        }

        return redirect()
            ->route('questions.index')
            ->with('success', "Вопрос '{$question->name}' успешно обновлен!");
    }

    public function destroy(Question $question): RedirectResponse
    {
        $questionName = $question->name;

        if ($question->tests()->exists()) {
            return redirect()
                ->route('questions.index')
                ->with('error', "Нельзя удалить вопрос '{$questionName}', так как он входит в тесты!");
        }

        $question->delete();

        return redirect()
            ->route('questions.index')
            ->with('success', "Вопрос '{$questionName}' успешно удален!");
    }

    public function restore($id): RedirectResponse
    {
        $question = Question::withTrashed()
            ->findOrFail($id);

        $questionName = $question->name;

        if ($question->trashed()) {
            $question->restore();
            return redirect()
                ->route('questions.index')
                ->with('success', "Вопрос '{$questionName}' успешно восстановлен!");
        }

        return redirect()
            ->route('questions.index')
            ->with('success', "Вопрос '{$questionName}' не удалялся!");
    }

    public function forceDetroy($id): RedirectResponse
    {
        $question = Question::withTrashed()
            ->findOrFail($id);

        $questionName = $question->name;

        if ($question->trashed()) {
            $question->forceDelete();
            return redirect()
                ->route('questions.index')
                ->with('success', "Вопрос '{$questionName}' успешно удален из корзины!");
        }

        return redirect()
            ->route('questions.index')
            ->with('success', "Вопрос '{$questionName}' не находится в корзине!");
    }

    public function trashed(): View
    {
        $questions = Question::onlyTrashed()->orderBy('question_text')->get();
        return view('questions.trashed', ['questions' => $questions]);
    }
}
