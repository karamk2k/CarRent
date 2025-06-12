<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
     

    public function __construct(public User $user,public int $otp,public $expiryTime)
    {
        
    }
    /**
     * Get the message envelope.
     */
 
    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->subject('Your OTP Code')
                    ->view('emails.otp');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
   
}
