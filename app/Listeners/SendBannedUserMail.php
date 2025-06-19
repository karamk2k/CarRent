<?php

namespace App\Listeners;

use App\Events\BannedUserDetected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBannedUserMail implements ShouldQueue

{     use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct(private readonly ActivityLogger $logger )
    {
        \Log::info('SendBannedUserMail listener initialized');
    }

    /**
     * Handle the event.
     */
    public function handle(BannedUserDetected $event): void
    {
        $bannedUser = $event->user;
        $this->logger->log(
            userId: $bannedUser->id,
            type: 'banned_user',
            details: 'Banned user detected: ' . $bannedUser->email
        );
        Mail::to($bannedUser->email)->queue(new \App\Mail\BannedUserMail($bannedUser));


    }
}
