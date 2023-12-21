<?php

namespace App\Console\Commands\Subscriptions;

use App\Mail\Subscription\SendTrialExpiringSoonMail;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EmailTenantsWithExpiringTrialsCommand extends Command
{
    protected $signature = 'subscription:email-tenants-with-expiring-trials {days=3}';

    protected $description = 'Email tenants with expiring trials.';

    protected int $mailsSent = 0;

    public function handle(): void
    {
        $days = (int) $this->argument('days');

        Tenant::onTrial()->get()
            ->filter->trailEndsInDays($days)
            ->each(function (Tenant $tenant) {
                $this->sendTrialEndingSoonMail($tenant);
            });

        $this->info("Sent {$this->mailsSent} trial expiring emails!");
    }

    protected function sendTrialEndingSoonMail(Tenant $tenant): void
    {
        if ($tenant->wasAlreadySentTrialEndingSoonMail()) {
            return;
        }

        $this->comment("Mailing {$tenant->owner->email} (tenant {$tenant->id})");
        Mail::to($tenant->owner->email)->send(new SendTrialExpiringSoonMail($tenant));

        $this->mailsSent++;

        $tenant->rememberHasBeenSentTrialEndingSoonMail();
    }
}
