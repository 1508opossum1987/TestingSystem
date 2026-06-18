<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionStoreRequest;
use App\Http\Requests\TopicStoreRequest;
use App\Models\Question;
use App\Models\QuestionLevel;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QuestionController extends Controller
{
    const PAGINATE_PER_PAGE = 15;
    public function index(Request $request): View
    {
        $query = Question::with(['topic', 'question_level']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('question_text', 'LIKE', "%{$search}%");
        }

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        $questions = $query
            ->orderBy('created_at', 'desc')
            ->paginate(self::PAGINATE_PER_PAGE)
            ->withQueryString(); // сохраняет параметры в пагинации

        $topics = Topic::all();
        $levels = QuestionLevel::all();

        return view('questions.index', [
            'questions' => $questions,
            'topics' => $topics,
            'levels' => $levels,
            'filters' => $request->only(['search', 'topic_id', 'level_id']),
        ]);
    }

    public function create(): View
    {
        $topics = Topic::all();
        $levels = QuestionLevel::all();
        return view('questions.create', [
            'topics' => $topics,
            'levels' => $levels
        ]);
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

    public function edit(Question $question): View
    {
        $topics = Topic::all();
        $levels = QuestionLevel::all();

        return view('questions.edit', [
            'question' => $question,
            'topics' => $topics,
            'question_levels' => $levels
        ]);
    }

    public function update(QuestionStoreRequest $request, Question $question): RedirectResponse
    {
        $validated = $request->validated();

        if (isset($validated['options']) && is_array($validated['options'])) {
            $validated['options'] = json_encode($validated['options']);
        }

        $question->update($validated);

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

    public function forceDestroy($id): RedirectResponse
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
