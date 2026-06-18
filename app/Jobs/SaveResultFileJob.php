<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SaveResultFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $resultId;
    public $answersDetail;
    public $scorePercent;
    public $grade;
    public $userId;
    public $testId;

    public function __construct($resultId, $answersDetail, $scorePercent, $grade, $userId, $testId)
    {
        $this->resultId = $resultId;
        $this->answersDetail = $answersDetail;
        $this->scorePercent = $scorePercent;
        $this->grade = $grade;
        $this->userId = $userId;
        $this->testId = $testId;
    }

    public function handle(): void
    {
        $data = [
            'result_id' => $this->resultId,
            'user_id' => $this->userId,
            'test_id' => $this->testId,
            'score_percent' => $this->scorePercent,
            'grade' => $this->grade,
            'answers' => $this->answersDetail,
        ];

        $fileName = 'results/result_' . $this->userId . '_' . $this->testId . '_' . time() . '.json';
        Storage::disk('local')->put($fileName, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
