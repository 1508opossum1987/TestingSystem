<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $userName;
    public $testName;
    public $scorePercent;

    public function __construct($email, $userName, $testName, $scorePercent)
    {
        $this->email = $email;
        $this->userName = $userName;
        $this->testName = $testName;
        $this->scorePercent = $scorePercent;
    }

    public function handle(): void
    {
        // Пример отправки email (нужно настроить Mail)
        // Mail::raw("Пользователь {$this->userName} завершил тест '{$this->testName}' с результатом {$this->scorePercent}%", function ($message) {
        //     $message->to($this->email)->subject('Новый результат теста');
        // });
    }
}
