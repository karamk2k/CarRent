<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PaymentMade;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use App\Mail\PaymentConfirmationMail;
class LogPayment implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct( private readonly ActivityLogger $logger)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentMade $event): void
    {
        $email = $event->user->email;
        $rentalId = $event->rental->id;
        $amount = $event->rental->total_price;
        \Log::info("Payment made by user: $email for rental ID: $rentalId, Amount: $amount");
        $this->logger->log($event->user->id, 'payment', "Payment made for rental ID: $rentalId, Amount: $amount");
        Mail::to($event->user->email)->queue(new PaymentConfirmationMail(
            $event->user,
             (float) $event->rental->total_price,
            $event->rental->car->name,
            now()
        ));

    }
}
