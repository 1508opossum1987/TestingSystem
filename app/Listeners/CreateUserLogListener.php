<?php

namespace App\Listeners;

use App\Events\TestCompletedEvent;
use App\Models\UserLog;
use App\Models\Test;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class CreateUserLogListener
{
    public function handle(TestCompletedEvent $event): void
    {
        $user = User::find($event->userId);
        $test = Test::with('topic')->find($event->testId);

        $logContent = [
            'user_id' => $event->userId,
            'user_name' => $user->name ?? 'Неизвестно',
            'user_email' => $user->email ?? 'Неизвестно',
            'test_id' => $event->testId,
            'test_topic' => $test->topic->name ?? 'Неизвестно',
            'started_at' => $event->startedAt,
            'completed_at' => $event->completedAt,
            'result_id' => $event->resultId ?? null,
        ];

        $json = json_encode($logContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $fileName = 'logs/user_log_' . $event->userId . '_' . time() . '.json';

        Storage::disk('local')->put($fileName, $json);

        UserLog::create([
            'user_id' => $event->userId,
            'result_id' => $event->resultId ?? null,
            'action' => 'completed_test',
            'file_path' => $fileName,
            'content_preview' => "Пользователь {$user->name} завершил тест '{$test->topic->name}'",
        ]);
    }
}
