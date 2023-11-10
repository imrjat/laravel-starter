<?php

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendSubscriptionExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function build(): SendSubscriptionExpiredMail
    {
        return $this->subject('Your '.config('app.name').' subscription has expired')
            ->markdown('mail.subscription.subscriptionExpired');
    }
}
