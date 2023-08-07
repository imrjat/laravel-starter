<?php

namespace App\Console\Commands\Subscriptions;

use App\Mail\Subscription\SendTrialExpiredMail;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Exception;

class EmailTenantsWithExpiredTrialsCommand extends Command
{
    protected $name         = 'subscription:email-tenants-with-expired-trials';
    protected $description  = 'Email tenants with expired trials.';
    protected $mailsSent    = 0;
    protected $mailFailures = 0;

    public function handle()
    {
        Tenant::onTrial()->get()
            ->filter->onExpiredTrial()
            ->each(function (Tenant $tenant) {
                $this->sendTrialExpiredMail($tenant);
            });

        if ($this->mailFailures > 0) {
           $this->error("Failed to send {$this->mailFailures} trial expired mails!");
        }

        $this->info("Sent {$this->mailsSent} trial expired emails!");
    }

    protected function sendTrialExpiredMail(Tenant $tenant)
    {
        try {
            if ($tenant->wasAlreadySentTrialEndedMail()) {
                return;
            }

            $this->comment("Mailing {$tenant->owner->email} (tenant {$tenant->id})");
            Mail::to($tenant->owner->email)->send(new SendTrialExpiredMail($tenant));

            $tenant->setTrialEndedStatus();

            $this->mailsSent++;

            $tenant->rememberHasBeenSentTrialEndingSoonMail();
        } catch (Exception $exception) {
            $this->error("exception when sending mail to tenant {$tenant->id}", $exception);
            report($exception);
            $this->mailFailures++;
        }
    }
}
