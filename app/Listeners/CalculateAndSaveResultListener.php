<?php

namespace App\Listeners;

use App\Events\TestCompletedEvent;
use App\Jobs\SaveResultFileJob;
use App\Jobs\SendWebSocketNotificationJob;
use App\Models\Question;
use App\Models\Result;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class CalculateAndSaveResultListener
{
    public function handle(TestCompletedEvent $event): void
    {
        $questions = Question::whereIn('id', array_keys($event->answers))->get()->keyBy('id');

        $correctCount = 0;
        $answersDetail = [];

        foreach ($event->answers as $questionId => $userAnswer) {
            $question = $questions->get($questionId);
            $isCorrect = ($question && $question->correct_answer === $userAnswer);

            if ($isCorrect) {
                $correctCount++;
            }

            $answersDetail[$questionId] = [
                'user_answer' => $userAnswer,
                'correct_answer' => $question->correct_answer ?? null,
                'is_correct' => $isCorrect,
                'question_text' => $question->question_text ?? null,
            ];
        }

        $totalCount = count($event->answers);
        $scorePercent = ($totalCount > 0) ? round(($correctCount / $totalCount) * 100) : 0;

        $grade = match(true) {
            $scorePercent >= 90 => 5,
            $scorePercent >= 70 => 4,
            $scorePercent >= 50 => 3,
            default => 2,
        };

        // Получаем имя пользователя
        $user = User::find($event->userId);
        $userName = $user->name ?? 'Неизвестно';

        $filePath = $this->saveDetailsToFile($event, $answersDetail, $scorePercent, $grade);

        $result = Result::create([
            'user_id' => $event->userId,
            'test_id' => $event->testId,
            'score_percent' => $scorePercent,
            'grade' => $grade,
            'answers_file_path' => $filePath,
        ]);

        SaveResultFileJob::dispatch($result->id, $answersDetail, $scorePercent, $grade, $event->userId, $event->testId);

        $event->resultId = $result->id;
        $event->scorePercent = $scorePercent;

        SendWebSocketNotificationJob::dispatch(
            "Пользователь {$userName} завершил тест",
            $result->id,
            $event->userId,
            $userName,
            $scorePercent
        );
    }

    private function saveDetailsToFile($event, $answersDetail, $scorePercent, $grade): string
    {
        $data = [
            'user_id' => $event->userId,
            'test_id' => $event->testId,
            'started_at' => $event->startedAt,
            'completed_at' => $event->completedAt,
            'score_percent' => $scorePercent,
            'grade' => $grade,
            'answers' => $answersDetail,
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $fileName = 'results/result_' . $event->userId . '_' . $event->testId . '_' . time() . '.json';

        Storage::disk('local')->put($fileName, $json);

        return $fileName;
    }
}
