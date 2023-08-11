<?php

use App\Services\StripeService;

beforeEach(function () {
    $this->authenticate();
});

test('getCustomer created a stripe customer when one does not exist', function () {
    $customer = (new StripeService)->getCustomer();
    expect($customer['object'])->toBe('customer');
});

test('has monthly plan', function () {

    auth()->user()->tenant()->update([
        'stripe_plan' => config('services.stripe.monthly')
    ]);

    $plan = (new StripeService)->getPlan();
    $this->assertEquals('Monthly', $plan);
});

test('has annually plan', function () {

    auth()->user()->tenant()->update([
        'stripe_plan' => config('services.stripe.annually')
    ]);

    $plan = (new StripeService)->getPlan();
    $this->assertEquals('Annually', $plan);
});

test('has no plan', function () {

    auth()->user()->tenant()->update([
        'stripe_plan' => null
    ]);

    $plan = (new StripeService)->getPlan();
    $this->assertEquals('', $plan);
});

test('get billing portal', function () {

    $url = (new StripeService)->getBillingPortalUrl();
    $this->assertNotNull($url);
});
