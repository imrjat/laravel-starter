<?php

use App\Mail\Subscription\SendPaymentFailedMail;
use App\Models\User;

test('Mail', function () {

    $user = User::factory()->create();
    $tenant = $user->tenant;

    $mailable = new SendPaymentFailedMail($tenant);

    $mailable->assertHasSubject('Your '.config('app.name').' payment failed');

});
