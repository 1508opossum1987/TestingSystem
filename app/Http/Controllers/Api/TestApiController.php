<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Test;
use App\Models\Question;
use App\Models\UserLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TestApiController extends Controller
{
    const PAGINATE_PER_PAGE=6;

    public function index(): JsonResponse
    {
        $tests = Test::with(['topic', 'question_level'])
            ->orderBy('created_at')
            ->paginate(self::PAGINATE_PER_PAGE);

        return response()->json([
            'success' => true,
            'data' => $tests,
        ]);
    }

    public function show(Test $test): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $test->load(['topic', 'question_level', 'questions']),
        ]);
    }

    public function start(Request $request, Test $test): JsonResponse
    {
        $user = $request->user();

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ваш аккаунт заблокирован.',
            ], 403);
        }

        $questions = $test->questions()->with('question_level')->get();

        if ($questions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'В этом тесте нет вопросов.',
            ], 404);
        }

        $sessionKey = "test_session:{$user->id}:{$test->id}";
        Cache::put($sessionKey, [
            'test_id' => $test->id,
            'user_id' => $user->id,
            'questions' => $questions->map(function ($q) {
                return [
                    'id' => $q->id,
                    'question_text' => $q->question_text,
                    'options' => json_decode($q->options, true),
                    'level' => $q->question_level->question_level ?? null,
                ];
            }),
            'started_at' => now(),
        ], 3600);

        return response()->json([
            'success' => true,
            'data' => [
                'test' => [
                    'id' => $test->id,
                    'topic' => $test->topic->name,
                    'level' => $test->question_level->question_level,
                    'question_count' => $questions->count(),
                ],
                'questions' => $questions->map(function ($q) {
                    return [
                        'id' => $q->id,
                        'text' => $q->question_text,
                        'options' => json_decode($q->options, true),
                    ];
                }),
            ],
        ]);
    }

    public function submit(Request $request, Test $test): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer' => 'required|string',
        ]);

        $sessionKey = "test_session:{$user->id}:{$test->id}";
        $session = Cache::get($sessionKey);

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Сессия теста истекла. Начните тест заново.',
            ], 400);
        }

        $correctCount = 0;
        $totalCount = count($request->answers);
        $answersDetails = [];

        foreach ($request->answers as $answer) {
            $question = Question::find($answer['question_id']);
            $isCorrect = ($question->correct_answer === $answer['answer']);

            if ($isCorrect) {
                $correctCount++;
            }

            $answersDetails[$answer['question_id']] = [
                'user_answer' => $answer['answer'],
                'correct_answer' => $question->correct_answer,
                'is_correct' => $isCorrect,
            ];
        }

        $scorePercent = round(($correctCount / $totalCount) * 100);

        $grade = match(true) {
            $scorePercent >= 90 => 5,
            $scorePercent >= 70 => 4,
            $scorePercent >= 50 => 3,
            default => 2,
        };

        $result = Result::create([
            'user_id' => $user->id,
            'test_id' => $test->id,
            'score_percent' => $scorePercent,
            'grade' => $grade,
            'answers' => json_encode($answersDetails),
        ]);

        Cache::forget($sessionKey);

        UserLog::create([
            'user_id' => $user->id,
            'result_id' => $result->id,
            'content_preview' => "Пользователь {$user->name} завершил тест '{$test->topic->name}' с результатом {$scorePercent}%",
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'result_id' => $result->id,
                'correct_count' => $correctCount,
                'total_count' => $totalCount,
                'score_percent' => $scorePercent,
                'grade' => $grade,
            ],
        ]);
    }

    public function destroy(Test $test): JsonResponse
    {
        if ($test->questions()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Нельзя удалить тест, так как он содержит вопросы.',
            ], 409);
        }

        $test->delete();

        return response()->json([
            'success' => true,
            'message' => 'Тест успешно удален.',
        ]);
    }
}
