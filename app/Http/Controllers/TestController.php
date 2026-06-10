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

    public function answer(Test $test, TestSessionService $sessionService)
    {
        $session = $sessionService->getSession(auth()->id(), $test->id);

        if (!$session) {
            return redirect()->route('tests.show', $test)
                ->with('error', 'Сессия теста истекла. Начните тест заново.');
        }

        $questions = $test->questions()->get();
        $answers = $session['answers'];

        $currentQuestion = null;
        $currentQuestionId = null;

        foreach ($questions as $question) {
            if (is_null($answers[$question->id] ?? null)) {
                $currentQuestion = $question;
                $currentQuestionId = $question->id;
                break;
            }
        }

        $allAnswered = ($currentQuestion === null);

        if ($allAnswered) {
            return view('tests.complete', [
                'test' => $test,
                'answers' => $answers,
            ]);
        }

        $options = json_decode($currentQuestion->options, true);

        return view('tests.answer', [
            'test' => $test,
            'question' => $currentQuestion,
            'options' => $options,
            'currentAnswer' => $answers[$currentQuestionId] ?? null,
            'answeredCount' => count(array_filter($answers)),
            'totalCount' => $questions->count(),
        ]);
    }

    public function saveAnswer(Request $request, Test $test, TestSessionService $sessionService)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|string|in:A,B,C,D',
            'next' => 'nullable|boolean',
        ]);

        $sessionService->saveAnswer(
            auth()->id(),
            $test->id,
            $request->question_id,
            $request->answer
        );

        if ($request->has('next') && $request->next == 1) {
            return redirect()->route('tests.answer', $test);
        }

        return redirect()->route('tests.answer', $test);
    }
    public function index(): View
    {
        $tests = Test::query()
            ->orderBy('created_at')
            ->with(['question_level', 'topic'])
            ->paginate(self::PAGINATE_PER_PAGE);

        return view('tests.index', [
            'tests' => $tests
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
}
