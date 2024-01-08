<?php

use App\Mail\Subscription\SendTrialExpiringSoonMail;
use App\Models\User;

test('Mail', function () {

    $user = User::factory()->create();
    $tenant = $user->tenant;

    $mailable = new SendTrialExpiringSoonMail($tenant);

    $mailable->assertHasSubject('Your '.config('app.name').' trial will expire soon');
});