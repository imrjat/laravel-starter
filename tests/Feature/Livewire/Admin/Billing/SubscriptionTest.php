<?php

test('can see subscription page when tenant owner', function () {
    $this->authenticate();
    $this->get(route('admin.billing'))
        ->assertOk();
});

test('cannot see subscription page when not tenant owner', function () {
    $this->noneTenantOwner();

    $this->get(route('admin.billing'))
        ->assertRedirect(route('dashboard'));
});

test('redirects back to billing when hitting a none existent plan', function () {
    $this->authenticate();
    $this->post(route('admin.billing.subscribe'), ['type' => 'wrong'])
        ->assertRedirect(route('admin.billing'));
});

test('with valid plan redirect to Stripe', function (string $plan) {
    $this->authenticate();
    $this->post(route('admin.billing.subscribe'), ['type' => $plan])
        ->assertRedirectContains('checkout.stripe.com');
})->with([
    'monthly',
    'annually',
]);

test('only tenant owner can attempt subscription', function () {
    $this->noneTenantOwner();

    $this->post(route('admin.billing.subscribe'), ['type' => 'monthly'])
        ->assertRedirect(route('dashboard'));
});

test('can access billing portal', function () {
    $this->authenticate();
    $this->get(route('billing-portal'))
        ->assertRedirectContains('billing.stripe.com');
});
