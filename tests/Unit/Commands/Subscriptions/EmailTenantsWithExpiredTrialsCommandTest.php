<?php

use App\Mail\Subscription\SendTrialExpiringSoonMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    $this->user = User::factory()->create();
});

test('does send expired emails when trial ends at is today', function(){

//    $trialEndsMinus3Days = config('admintw.trail_days') -3;
//    $this->travel($trialEndsMinus3Days)->days();
//
//    $this->artisan('subscription:email-tenants-with-expired-trials')
//        ->expectsOutputToContain("Mailing {$this->user->tenant->owner->email} (tenant {$this->user->tenant->id})")
//        ->expectsOutput('Sent 1 trial expiring emails!');
//
//    Mail::assertSent(SendTrialExpiringSoonMail::class);
//
//    $this->travelBack();
})->todo();

