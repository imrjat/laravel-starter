<?php

namespace App\Mail\Subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Team;

class SendPaymentReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $team;
    public $file;

    public function __construct(Team $team, $file)
    {
        $this->team = $team;
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('app.name') . " invoice")
            ->markdown('mail.subscription.paymentReceived')
            ->attach($this->file);
    }
}
