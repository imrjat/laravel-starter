<?php

namespace App\Mail\Subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Team;

class SendPaymentFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Your " . config('app.name') . " payment failed")
            ->markdown('mail.subscription.paymentFailed');
    }
}
