<?php

use App\Mail\Subscription\SendTrialExpiredMail;
use App\Models\User;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    $this->user = User::factory()->create();
});

test('does send expired emails for trial that ends today', function(){

    $trialEndsToday = config('admintw.trail_days');
    $this->travel($trialEndsToday)->days();

    $this->artisan('subscription:email-tenants-with-expired-trials')
        ->expectsOutputToContain("Mailing {$this->user->tenant->owner->email} (tenant {$this->user->tenant->id})")
        ->expectsOutput('Sent 1 trial expired emails!');

    Mail::assertSent(SendTrialExpiredMail::class);

    $this->travelBack();
});

test('does not send expired emails for trial that have not ended', function(){

    $trialEndsToday = config('admintw.trail_days')-1;
    $this->travel($trialEndsToday)->days();

    $this->artisan('subscription:email-tenants-with-expired-trials')
        ->doesntExpectOutputToContain("Mailing {$this->user->tenant->owner->email} (tenant {$this->user->tenant->id})")
        ->expectsOutput('Sent 0 trial expired emails!');

    Mail::assertNotSent(SendTrialExpiredMail::class);

    $this->travelBack();
});

test('does not send expired emails when sent already', function(){

    $trialEndsToday = config('admintw.trail_days');
    $this->travel($trialEndsToday)->days();

    $this->user->tenant->rememberHasBeenSentTrialEndedMail();

    $this->artisan('subscription:email-tenants-with-expired-trials')
        ->doesntExpectOutputToContain("Mailing {$this->user->tenant->owner->email} (tenant {$this->user->tenant->id})")
        ->expectsOutput('Sent 0 trial expired emails!');

    Mail::assertNotSent(SendTrialExpiredMail::class);

    $this->travelBack();
});

test('is scheduled', function() {

    $schedule = app()->make(Schedule::class);

    $events = collect($schedule->events())->filter(function (Event $event) {
      return stripos($event->command, 'subscription:email-tenants-with-expired-trials');
    });

    if ($events->count() == 0) {
        $this->fail('No events found');
    }

    $events->each(function (Event $event) {
        $this->assertEquals('0 6 * * *', $event->expression);
    });
});

