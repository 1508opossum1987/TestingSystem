<?php

namespace App\Jobs;

use App\Events\NewResultNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWebSocketNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message;
    public $resultId;
    public $userId;
    public $userName;
    public $score;

    public function __construct($message, $resultId, $userId, $userName, $score)
    {
        $this->message = $message;
        $this->resultId = $resultId;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->score = $score;
    }

    public function handle(): void
    {
        broadcast(new NewResultNotification(
            $this->message,
            $this->resultId,
            $this->userId,
            $this->userName,
            $this->score
        ));
    }
}
