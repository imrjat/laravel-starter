<?php

use App\Mail\Subscription\SendPaymentReceivedMail;
use App\Models\User;

test('Mail', function () {

    $user = User::factory()->create();
    $tenant = $user->tenant;

    $mailable = new SendPaymentReceivedMail($tenant, 'file.pdf');

    $mailable->assertHasSubject(config('app.name').' invoice');
});
