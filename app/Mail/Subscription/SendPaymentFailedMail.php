<?php

namespace App\Mail\Subscription;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPaymentFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function build(): SendPaymentFailedMail
    {
        return $this->subject("Your " . config('app.name') . " payment failed")
            ->markdown('mail.subscription.paymentFailed');
    }
}
