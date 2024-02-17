<?php

declare(strict_types=1);

namespace App\Mail\Subscription;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPaymentReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tenant $tenant;

    public string $file;

    public function __construct(Tenant $tenant, string $file)
    {
        $this->tenant = $tenant;
        $this->file = $file;
    }

    public function build(): SendPaymentReceivedMail
    {
        return $this->subject(config('app.name').' invoice')
            ->markdown('mail.subscription.paymentReceived')
            ->attach($this->file);
    }
}
