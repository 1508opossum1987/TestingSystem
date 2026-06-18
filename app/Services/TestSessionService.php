<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class TestSessionService
{
    private function getKey(int $userId, int $testId): string
    {
        return "test_session:{$userId}:{$testId}";
    }

    public function startSession(int $userId, int $testId, array $questions): void
    {
        $data = [
            'test_id' => $testId,
            'user_id' => $userId,
            'answers' => [],
            'started_at' => now()->toDateTimeString(),
            'current_question_index' => 0, // Индекс текущего вопроса
        ];

        foreach ($questions as $question) {
            $data['answers'][$question['id']] = null;
        }

        Cache::put($this->getKey($userId, $testId), $data, 3600);
    }

    public function getSession(int $userId, int $testId): ?array
    {
        return Cache::get($this->getKey($userId, $testId));
    }

    public function saveAnswer(int $userId, int $testId, int $questionId, string $answer): void
    {
        $session = $this->getSession($userId, $testId);

        if ($session) {
            $session['answers'][$questionId] = $answer;
            Cache::put($this->getKey($userId, $testId), $session, 3600);
        }
    }

    public function getAnswer(int $userId, int $testId, int $questionId): ?string
    {
        $session = $this->getSession($userId, $testId);

        return $session['answers'][$questionId] ?? null;
    }

    public function getAllAnswers(int $userId, int $testId): array
    {
        $session = $this->getSession($userId, $testId);

        return $session['answers'] ?? [];
    }

    public function getStartedAt(int $userId, int $testId): ?string
    {
        $session = $this->getSession($userId, $testId);

        return $session['started_at'] ?? null;
    }

    public function getCurrentQuestionIndex(int $userId, int $testId): int
    {
        $session = $this->getSession($userId, $testId);

        return $session['current_question_index'] ?? 0;
    }

    public function setCurrentQuestionIndex(int $userId, int $testId, int $index): void
    {
        $session = $this->getSession($userId, $testId);

        if ($session) {
            $session['current_question_index'] = $index;
            Cache::put($this->getKey($userId, $testId), $session, 3600);
        }
    }

    public function clearSession(int $userId, int $testId): void
    {
        Cache::forget($this->getKey($userId, $testId));
    }
}
