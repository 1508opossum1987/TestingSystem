<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopicApiController extends Controller
{
    const PAGINATE_PER_PAGE=10;

    public function index(): JsonResponse
    {
        $topics = Topic::orderBy('name')->paginate(self::PAGINATE_PER_PAGE);

        return response()->json([
            'success' => true,
            'data' => $topics,
        ]);
    }

    public function show(Topic $topic): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $topic->load('questions'),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:topics',
        ]);

        $topic = Topic::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json([
            'success' => true,
            'data' => $topic,
        ], 201);
    }

    public function update(Request $request, Topic $topic): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:topics,name,' . $topic->id,
        ]);

        $topic->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json([
            'success' => true,
            'data' => $topic,
        ]);
    }

    public function destroy(Topic $topic): JsonResponse
    {
        if ($topic->questions()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Нельзя удалить тему, так как у нее есть вопросы.',
            ], 409);
        }

        $topic->delete();

        return response()->json([
            'success' => true,
            'message' => 'Тема успешно удалена.',
        ]);
    }
}
