<?php

test('can see subscription page when tenant owner', function () {
    $this->authenticate();
    $this->get(route('admin.billing.subscription'))
        ->assertOk();
});

test('cannot see subscription page when not tenant owner', function () {
    $this->noneTenantOwner();

    $this->get(route('admin.billing.subscription'))
        ->assertRedirect(route('dashboard'));
});

test('redirects back to billing when hitting a none existent plan', function () {
    $this->authenticate();
    $this->get(route('subscribe', 'wrong'))
        ->assertRedirect(route('admin.billing.subscription'));
});

test('with valid plan redirect to LS', function (string $plan) {
    $this->authenticate();
    $this->get(route('subscribe', $plan))
        ->assertRedirectContains('lemonsqueezy.com/checkout');
})->with([
    'monthly',
    'annually'
]);

test('only tenant owner can attempt subscription', function () {
    $this->noneTenantOwner();

    $this->get(route('subscribe', 'monthly'))
        ->assertRedirect(route('dashboard'));
});
