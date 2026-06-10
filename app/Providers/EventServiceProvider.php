<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\TestCompletedEvent::class => [
            \App\Listeners\CalculateAndSaveResultListener::class,
            \App\Listeners\CreateUserLogListener::class,
        ],
    ];
    public function boot(): void
    {
        //
    }
}
