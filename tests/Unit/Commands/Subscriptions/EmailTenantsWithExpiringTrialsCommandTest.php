<?php

use App\Mail\Subscription\SendTrialExpiringSoonMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    $this->user = User::factory()->create();
});

test('does not send expiring emails 3 days before trial ends at date', function(){

    $this->artisan('subscription:email-tenants-with-expiring-trials')
        ->doesntExpectOutputToContain("Mailing {$this->user->tenant->owner->email} (tenant {$this->user->tenant->id})")
        ->expectsOutput('Sent 0 trial expiring emails!');

    Mail::assertNotSent(SendTrialExpiringSoonMail::class);
});

test('does send expiring emails 3 days before trial ends at date', function(){

    $trialEndsMinus3Days = config('admintw.trail_days') -3;
    $this->travel($trialEndsMinus3Days)->days();

    $this->artisan('subscription:email-tenants-with-expiring-trials')
        ->expectsOutputToContain("Mailing {$this->user->tenant->owner->email} (tenant {$this->user->tenant->id})")
        ->expectsOutput('Sent 1 trial expiring emails!');

    Mail::assertSent(SendTrialExpiringSoonMail::class);

    $this->travelBack();
});

test('does not send expiring emails 3 days before trial ends at date more than once', function(){

    $this->user->tenant->rememberHasBeenSentTrialEndingSoonMail();

    $trialEndsMinus3Days = config('admintw.trail_days') -3;
    $this->travel($trialEndsMinus3Days)->days();

    $this->artisan('subscription:email-tenants-with-expiring-trials')
        ->doesntExpectOutputToContain("Mailing {$this->user->tenant->owner->email} (tenant {$this->user->tenant->id})")
        ->expectsOutput('Sent 0 trial expiring emails!');

    Mail::assertNotSent(SendTrialExpiringSoonMail::class);

    $this->travelBack();
});


