<?php

use App\Mail\Subscription\SendTrialExpiredMail;
use App\Models\User;

test('Mail', function () {

    $user = User::factory()->create();
    $tenant = $user->tenant;

    $mailable = new SendTrialExpiredMail($tenant);

    $mailable->assertHasSubject('Your '.config('app.name').' trial has ended');
});
