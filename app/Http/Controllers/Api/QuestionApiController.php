<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionApiController extends Controller
{
    const PAGINATE_PER_PAGE=13;

    public function index(): JsonResponse
    {
        $questions = Question::with(['topic', 'question_level'])
            ->orderBy('created_at')
            ->paginate(self::PAGINATE_PER_PAGE);

        return response()->json([
            'success' => true,
            'data' => $questions,
        ]);
    }

    public function show(Question $question): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $question->load(['topic', 'question_level']),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'level_id' => 'required|exists:question_levels,id',
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'correct_answer' => 'required|string|in:A,B,C,D',
            'type' => 'required|in:single_choice,multiple_choice',
        ]);

        $data = $request->all();
        $data['options'] = json_encode($data['options']);

        $question = Question::create($data);

        return response()->json([
            'success' => true,
            'data' => $question,
        ], 201);
    }

    public function update(Request $request, Question $question): JsonResponse
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'level_id' => 'required|exists:question_levels,id',
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'correct_answer' => 'required|string|in:A,B,C,D',
            'type' => 'required|in:single_choice,multiple_choice',
        ]);

        $data = $request->all();
        $data['options'] = json_encode($data['options']);

        $question->update($data);

        return response()->json([
            'success' => true,
            'data' => $question,
        ]);
    }

    public function destroy(Question $question): JsonResponse
    {
        if ($question->tests()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Нельзя удалить вопрос, так как он входит в тесты.',
            ], 409);
        }

        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Вопрос успешно удален.',
        ]);
    }
}
