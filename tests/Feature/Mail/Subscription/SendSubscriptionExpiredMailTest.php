<?php

use App\Mail\Subscription\SendSubscriptionExpiredMail;
use App\Models\User;

test('Mail', function () {

    $user = User::factory()->create();
    $tenant = $user->tenant;

    $mailable = new SendSubscriptionExpiredMail($tenant);

    $mailable->assertHasSubject('Your '.config('app.name').' subscription has expired');
});
