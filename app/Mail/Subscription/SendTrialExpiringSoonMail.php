<?php

namespace App\Mail\Subscription;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTrialExpiringSoonMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function build()
    {
        return $this->subject("Your ".config('app.name')." trial will expire soon")
            ->markdown('mail.subscription.trialExpiringSoon');
    }
}
