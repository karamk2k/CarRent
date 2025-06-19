<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PendingRentalCleared;
use App\Services\ActivityLogger;
use App\Mail\PendingRentalClearedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
class LogPendingRentalClear implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct(private readonly ActivityLogger $logger)
    {
        \Log::info('LogPendingRentalClear listener initialized');
    }

    /**
     * Handle the event.
     */
    public function handle(PendingRentalCleared $event): void
    {
        //
        \Log::info('Handling PendingRentalCleared event for rental ID: ' . $event->rental->id);
        $this->logger->log(
            userId: $event->user->id,
            type: 'rental_clear',
            details: sprintf(
                'Pending rental cleared for rental ID: %d',
                $event->rental->id
            )
        );
        //
        \Log::info('Pending rental cleared for rental ID: ' . $event->rental->id);
         Mail::to($event->rental->user->email)->queue(
        new PendingRentalClearedMail($event->rental)
    );

    }
}
