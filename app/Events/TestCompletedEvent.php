<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestCompletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $testId;
    public $answers;
    public $startedAt;
    public $completedAt;

    public function __construct($userId, $testId, $answers, $startedAt, $completedAt)
    {
        $this->userId = $userId;
        $this->testId = $testId;
        $this->answers = $answers;
        $this->startedAt = $startedAt;
        $this->completedAt = $completedAt;
        $this->resultId = null;
        $this->scorePercent = null;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
