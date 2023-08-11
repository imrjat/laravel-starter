<?php

use App\Mail\Subscription\SendTrialExpiringSoonMail;
use App\Models\User;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    $this->user = User::factory()->create();
});

test('does send expiring emails X days before trial ends at date', function($days) {

    $trialEndsMinusDays = config('admintw.trail_days') -$days;
    $this->travel($trialEndsMinusDays)->days();

    $this->artisan('subscription:email-tenants-with-expiring-trials '.$days)
        ->expectsOutputToContain("Mailing {$this->user->tenant->owner->email} (tenant {$this->user->tenant->id})")
        ->expectsOutput('Sent 1 trial expiring emails!');

    Mail::assertSent(SendTrialExpiringSoonMail::class);

    $this->travelBack();
})->with([3, 2, 1]);


test('does not send expiring emails 3 days before trial ends at date', function($days) {

    $this->artisan('subscription:email-tenants-with-expiring-trials '.$days)
        ->doesntExpectOutputToContain("Mailing {$this->user->tenant->owner->email} (tenant {$this->user->tenant->id})")
        ->expectsOutput('Sent 0 trial expiring emails!');

    Mail::assertNotSent(SendTrialExpiringSoonMail::class);
})->with([3,2,1]);

test('does not send expiring emails x days before trial ends at date more than once', function($days) {

    $this->user->tenant->rememberHasBeenSentTrialEndingSoonMail();

    $trialEndsMinusDays = config('admintw.trail_days') -$days;
    $this->travel($trialEndsMinusDays)->days();

    $this->artisan('subscription:email-tenants-with-expiring-trials')
        ->doesntExpectOutputToContain("Mailing {$this->user->tenant->owner->email} (tenant {$this->user->tenant->id})")
        ->expectsOutput('Sent 0 trial expiring emails!');

    Mail::assertNotSent(SendTrialExpiringSoonMail::class);

    $this->travelBack();
})->with([3,2,1]);

test('is scheduled', function() {

    $schedule = app()->make(Schedule::class);

    $events = collect($schedule->events())->filter(function (Event $event) {
      return stripos($event->command, 'subscription:email-tenants-with-expiring-trials');
    });

    if ($events->count() == 0) {
        $this->fail('No events found');
    }

    $events->each(function (Event $event) {
        $this->assertEquals('0 5 * * *', $event->expression);
    });
});


