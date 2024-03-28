<?php

use App\Console\Commands\Subscriptions\EmailTenantsWithExpiredTrialsCommand;
use App\Console\Commands\Subscriptions\EmailTenantsWithExpiringTrialsCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(EmailTenantsWithExpiringTrialsCommand::class)->dailyAt('05:00')->withoutOverlapping();
Schedule::command(EmailTenantsWithExpiredTrialsCommand::class)->dailyAt('06:00')->withoutOverlapping();
