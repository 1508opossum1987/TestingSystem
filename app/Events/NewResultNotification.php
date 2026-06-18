<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewResultNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

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

    public function broadcastOn()
    {
        // Канал для администраторов и учителей
        return new Channel('notifications');
    }

    public function broadcastAs()
    {
        return 'new.result';
    }
}
