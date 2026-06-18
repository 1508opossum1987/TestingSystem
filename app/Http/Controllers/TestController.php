<?php

namespace App\Http\Controllers;

use App\Events\TestCompletedEvent;
use App\Http\Requests\TestStoreRequest;
use App\Models\QuestionLevel;
use App\Models\Result;
use App\Models\Test;
use App\Models\Topic;
use App\Services\TestSessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;



class TestController extends Controller
{
    const PAGINATE_PER_PAGE = 15;

    public function start(Test $test, TestSessionService $sessionService)
    {
        $questions = $test->questions()->with('question_level')->get();

        if ($questions->isEmpty()) {
            return redirect()->route('tests.show', $test)
                ->with('error', 'В этом тесте нет вопросов.');
        }

        $questionsArray = $questions->map(function ($q) {
            return [
                'id' => $q->id,
                'question_text' => $q->question_text,
                'options' => json_decode($q->options, true),
            ];
        })->toArray();

        $sessionService->startSession(auth()->id(), $test->id, $questionsArray);

        return redirect()->route('tests.answer', $test);
    }

    public function answer(Test $test, TestSessionService $sessionService, Request $request)
    {
        $session = $sessionService->getSession(auth()->id(), $test->id);

        if (!$session) {
            return redirect()->route('tests.show', $test)
                ->with('error', 'Сессия теста истекла. Начните тест заново.');
        }

        $questions = $test->questions()->get();
        $answers = $session['answers'];
        $totalCount = $questions->count();
        $answeredCount = count(array_filter($answers));

        $currentIndex = $request->get('index', $session['current_question_index'] ?? 0);

        if ($currentIndex >= $totalCount) {
            $currentIndex = $totalCount - 1;
        }
        if ($currentIndex < 0) {
            $currentIndex = 0;
        }

        $sessionService->setCurrentQuestionIndex(auth()->id(), $test->id, $currentIndex);

        $currentQuestion = $questions[$currentIndex];
        $options = json_decode($currentQuestion->options, true);
        $currentAnswer = $answers[$currentQuestion->id] ?? null;

        $questionsStatus = [];
        foreach ($questions as $index => $question) {
            $questionsStatus[] = [
                'id' => $question->id,
                'index' => $index,
                'is_answered' => !is_null($answers[$question->id] ?? null),
                'is_current' => $index == $currentIndex,
            ];
        }

        $allAnswered = ($answeredCount == $totalCount);

        return view('tests.answer', [
            'test' => $test,
            'currentQuestion' => $currentQuestion,
            'options' => $options,
            'currentAnswer' => $currentAnswer,
            'currentIndex' => $currentIndex,
            'totalCount' => $totalCount,
            'answeredCount' => $answeredCount,
            'questionsStatus' => $questionsStatus,
            'allAnswered' => $allAnswered,
            'answers' => $answers,
        ]);
    }

    public function saveAnswer(Request $request, Test $test, TestSessionService $sessionService)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|string|in:A,B,C,D',
        ]);

        $sessionService->saveAnswer(
            auth()->id(),
            $test->id,
            (int) $request->question_id,
            $request->answer
        );

        return redirect()->route('tests.answer', ['test' => $test, 'index' => $request->current_index ?? 0])
            ->with('success', 'Ответ сохранён!');
    }
    public function index(Request $request): View
    {
        $query = Test::with(['question_level', 'topic']);

        // Фильтр по теме
        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        // Фильтр по уровню
        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        $tests = $query
            ->orderBy('created_at', 'desc')
            ->paginate(self::PAGINATE_PER_PAGE)
            ->withQueryString();

        $topics = Topic::all();
        $levels = QuestionLevel::all();

        return view('tests.index', [
            'tests' => $tests,
            'topics' => $topics,
            'levels' => $levels,
            'filters' => $request->only(['topic_id', 'level_id']),
        ]);
    }

    public function complete(Test $test, TestSessionService $sessionService)
    {
        $session = $sessionService->getSession(auth()->id(), $test->id);

        if (!$session) {
            return redirect()->route('tests.show', $test)
                ->with('error', 'Сессия теста истекла.');
        }

        $answers = $session['answers'];

        $questions = $test->questions()->get();
        $allAnswered = true;

        foreach ($questions as $question) {
            if (is_null($answers[$question->id] ?? null)) {
                $allAnswered = false;
                break;
            }
        }

        if (!$allAnswered) {
            return redirect()->route('tests.answer', $test)
                ->with('error', 'Ответьте на все вопросы перед завершением.');
        }

        event(new TestCompletedEvent(
            auth()->id(),
            $test->id,
            $answers,
            $session['started_at'],
            now()->toDateTimeString()
        ));

        $sessionService->clearSession(auth()->id(), $test->id);

        return redirect()->route('tests.result', $test)
            ->with('success', 'Тест успешно завершён!');
    }

    public function result(Test $test)
    {
        $result = Result::where('user_id', auth()->id())
            ->where('test_id', $test->id)
            ->latest()
            ->first();

        if (!$result) {
            return redirect()->route('tests.show', $test)
                ->with('error', 'Результат не найден.');
        }

        $details = null;
        if ($result->answers_file_path && Storage::exists($result->answers_file_path)) {
            $details = json_decode(Storage::get($result->answers_file_path), true);
        }

        return view('tests.result', [
            'test' => $test,
            'result' => $result,
            'details' => $details,
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
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Только администратор может удалять тесты');
        }

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

    public function navigate(Request $request, Test $test, TestSessionService $sessionService)
    {
        $request->validate([
            'direction' => 'required|in:prev,next',
            'current_index' => 'required|integer|min:0',
        ]);

        $questions = $test->questions()->get();
        $totalCount = $questions->count();
        $currentIndex = (int) $request->current_index;

        if ($request->direction === 'prev') {
            $newIndex = max(0, $currentIndex - 1);
        } else {
            $newIndex = min($totalCount - 1, $currentIndex + 1);
        }

        return redirect()->route('tests.answer', ['test' => $test, 'index' => $newIndex]);
    }
}
