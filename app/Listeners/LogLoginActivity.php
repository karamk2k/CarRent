<?php

namespace App\Listeners;

use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Login;

class LogLoginActivity
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly ActivityLogger $logger
    ) {}

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $this->logger->log(
            userId: $event->user->id,
            type: 'login',
            details: 'User logged in successfully'
        );
    }
}
