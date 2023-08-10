<?php

namespace App\Console\Commands\Subscriptions;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscription\SendTrialExpiringSoonMail;
use Exception;

class EmailTenantsWithExpiringTrialsCommand extends Command
{
    protected $signature    = 'subscription:email-tenants-with-expiring-trials {days=3}';
    protected $description  = 'Email tenants with expiring trials.';
    protected int $mailsSent    = 0;
    protected int $mailFailures = 0;

    public function handle(): void
    {
        $days = $this->argument('days');

        Tenant::onTrial()->get()
            ->filter->trailEndsInDays(days: $days)
            ->each(function (Tenant $tenant) {
                $this->sendTrialEndingSoonMail($tenant);
            });

        if ($this->mailFailures > 0) {
           $this->error("Failed to send {$this->mailFailures} trial expiring mails!");
        }

        $this->info("Sent {$this->mailsSent} trial expiring emails!");
    }

    protected function sendTrialEndingSoonMail(Tenant $tenant): void
    {
        try {
            if ($tenant->wasAlreadySentTrialEndingSoonMail()) {
                return;
            }

            $this->comment("Mailing {$tenant->owner->email} (tenant {$tenant->id})");
            Mail::to($tenant->owner->email)->send(new SendTrialExpiringSoonMail($tenant));

            $this->mailsSent++;

            $tenant->rememberHasBeenSentTrialEndingSoonMail();
        } catch (Exception $exception) {
            $this->error("exception when sending mail to team {$tenant->id}", $exception);
            report($exception);
            $this->mailFailures++;
        }
    }
}
