<?php

namespace App\Console\Commands\Subscriptions;

use App\Mail\Subscription\SendTrialExpiredMail;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EmailTenantsWithExpiredTrialsCommand extends Command
{
    protected $name = 'subscription:email-tenants-with-expired-trials';

    protected $description = 'Email tenants with expired trials.';

    protected int $mailsSent = 0;

    public function handle(): void
    {
        $tenants = Tenant::onTrial()->trialExpiredToday()->get();
        foreach ($tenants as $tenant) {
            $this->sendTrialExpiredMail($tenant);
        }

        $this->info("Sent {$this->mailsSent} trial expired emails!");
    }

    protected function sendTrialExpiredMail(Tenant $tenant): void
    {

        if ($tenant->wasAlreadySentTrialExpiredMail()) {
            return;
        }

        $this->comment("Mailing {$tenant->owner->email} (tenant {$tenant->id})");
        Mail::to($tenant->owner->email)->send(new SendTrialExpiredMail($tenant));

        $this->mailsSent++;

        $tenant->rememberHasBeenSentTrialEndedMail();
    }
}
