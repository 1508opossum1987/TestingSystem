<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SaveLogFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userId;
    public $resultId;
    public $userName;
    public $testName;
    public $scorePercent;

    public function __construct($userId, $resultId, $userName, $testName, $scorePercent)
    {
        $this->userId = $userId;
        $this->resultId = $resultId;
        $this->userName = $userName;
        $this->testName = $testName;
        $this->scorePercent = $scorePercent;
    }

    public function handle(): void
    {
        $data = [
            'user_id' => $this->userId,
            'result_id' => $this->resultId,
            'user_name' => $this->userName,
            'test_name' => $this->testName,
            'score_percent' => $this->scorePercent,
            'timestamp' => now()->toDateTimeString(),
        ];

        $fileName = 'logs/user_log_' . $this->userId . '_' . time() . '.json';
        Storage::disk('local')->put($fileName, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
