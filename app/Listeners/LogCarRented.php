<?php

namespace App\Listeners;

use App\Events\CarRented;
use App\Services\ActivityLogger;

class LogCarRented
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
    public function handle(CarRented $event): void
    {
        $this->logger->log(
            userId: $event->user->id,
            type: 'rental',
            details: sprintf(
                'Rented car: %s (ID: %d) for %d days',
                $event->car->name,
                $event->car->id,
                $event->rentalDetails['duration'] ?? 0
            )
        );
    }
}
