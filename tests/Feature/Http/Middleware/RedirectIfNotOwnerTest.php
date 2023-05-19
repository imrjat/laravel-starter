<?php

use App\Http\Middleware\RedirectIfNotOwner;
use App\Models\User;
use Illuminate\Http\Request;

test('does not redirect when logged in as the owner', function () {
    $this->authenticate();

    $request = Request::create(route('dashboard'));

    $response = (new RedirectIfNotOwner())->handle($request, function () {
    });

    expect($response)->toBe(null);
});

test('redirects when logged in user is not the owner', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $request = Request::create(route('dashboard'));

    $response = (new RedirectIfNotOwner())->handle($request, function () {
    });

    expect($response->getStatusCode())->toBe(302);
});