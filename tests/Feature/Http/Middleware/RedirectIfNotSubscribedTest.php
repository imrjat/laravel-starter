<?php

use App\Http\Middleware\RedirectIfNotSubscribed;

test('returns 302', function () {
    $this->authenticate();

    auth()->user()->tenant()->update([
        'stripe_status' => 'incomplete',
        'trial_ends_at' => now()->subDays(30),
    ]);

    $request = Request::create(route('dashboard'));

    $response = (new RedirectIfNotSubscribed())->handle($request, function () {
    });

    expect($response->getStatusCode())->toBe(302);
});

test('returns 200', function () {
    $this->authenticate();

    $request = Request::create(route('dashboard'));

    $response = (new RedirectIfNotSubscribed())->handle($request, function () {
    });

    expect($response)->toBeNull();
});
