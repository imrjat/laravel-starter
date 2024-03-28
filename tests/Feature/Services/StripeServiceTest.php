<?php

use App\Services\StripeService;
use Stripe\Stripe;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

beforeEach(function () {
    $this->authenticate();
});

test('getCustomer created a stripe customer when one does not exist', function () {

    $stripeService = new StripeService();
    $customer = $stripeService->getCustomer();

    expect($customer['object'])->toBe('customer');
});

test('if stripe customer is not found, throw an exception', function () {

    auth()->user()->tenant()->update([
        'stripe_id' => 123,
    ]);

    $stripeService = new StripeService();
    $stripeService->getCustomer();
})->throws(Exception::class);

test('stripe customer returns null when customer has been deleted', function () {

    $stripeService = new StripeService();
    $customer = $stripeService->getCustomer();
    $customer->delete();

    $customer = $stripeService->getCustomer();

    expect($customer)->toBeNull();
});

test('gets existing stripe customer', function () {

    $stripeService = new StripeService();
    // create a stripe customer
    $stripeService->getCustomer();

    // get the customer again
    $customer = $stripeService->getCustomer();

    expect($customer)->not->toBeNull();
});

test('has monthly plan', function () {

    auth()->user()->tenant()->update([
        'stripe_plan' => config('services.stripe.monthly'),
    ]);

    $plan = (new StripeService)->getPlan();

    assertEquals('Monthly', $plan);
});

test('has annually plan', function () {

    auth()->user()->tenant()->update([
        'stripe_plan' => config('services.stripe.annually'),
    ]);

    $plan = (new StripeService)->getPlan();

    assertEquals('Annually', $plan);
});

test('has no plan', function () {
    auth()->user()->tenant()->update([
        'stripe_plan' => null,
    ]);

    $plan = (new StripeService)->getPlan();

    assertEquals('', $plan);
});

test('get billing portal', function () {
    $url = (new StripeService)->getBillingPortalUrl();

    assertNotNull($url);
})->skip();

test('setSubscriptionQty returns null', function () {
    $result = (new StripeService)->setSubscriptionQty();

    expect($result)->toBeNull();
});
