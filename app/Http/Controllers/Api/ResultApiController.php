<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\JsonResponse;

class ResultApiController extends Controller
{
    const PAGINATE_PER_PAGE = 5;

    public function index(): JsonResponse
    {
        if (auth()->user()->role === 'admin' || auth()->user()->role === 'teacher') {
            $results = Result::with(['user', 'test'])->paginate(15);
        } else {
            $results = Result::where('user_id', auth()->id())
                ->with(['test'])
                ->paginate(self::PAGINATE_PER_PAGE);
        }

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }

    public function show(Result $result): JsonResponse
    {
        if (auth()->user()->role !== 'admin' &&
            auth()->user()->role !== 'teacher' &&
            auth()->user()->id !== $result->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Нет доступа к этому результату.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $result->load(['user', 'test.topic', 'test.question_level']),
        ]);
    }

    public function myResults(): JsonResponse
    {
        $results = Result::where('user_id', auth()->id())
            ->with(['test.topic', 'test.question_level'])
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }

    public function destroy(Result $result): JsonResponse
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Только администратор может удалять результаты.',
            ], 403);
        }

        $result->delete();

        return response()->json([
            'success' => true,
            'message' => 'Результат успешно удален.',
        ]);
    }
}
