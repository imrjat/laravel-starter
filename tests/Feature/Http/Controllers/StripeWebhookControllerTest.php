<?php

use App\Http\Controllers\StripeWebhookController;
use App\Mail\Subscription\SendPaymentFailedMail;
use App\Mail\Subscription\SendPaymentReceivedMail;
use App\Mail\Subscription\SendSubscriptionExpiredMail;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;

test('can update subscription', function () {

    $user = User::factory()->create();

    $user->tenant->update([
        'stripe_id' => 'stripe-customer-id',
    ]);

    $payload = [
        'id' => '123456',
        'customer' => 'stripe-customer-id',
        'default_payment_method' => 'card',
        'current_period_end' => 1665754417,
        'status' => 'active',
        'cancel_at_period_end' => false,
        'canceled_at' => null,
    ];

    $controller = new StripeWebhookController();
    $controller->handleSubscriptionUpdated($payload);

    assertDatabaseHas('tenants', [
        'owner_id' => $user->id,
        'stripe_id' => 'stripe-customer-id',
        'stripe_subscription' => '123456',
        'default_payment_method' => 'card',
        'ends_at' => '2022-10-14 13:33:37',
        'stripe_status' => 'active',
        'trial_ends_at' => null,
        'cancel_at_period_end' => 'No',
        'canceled_at' => null,
    ]);

});

test('can delete subscription', function () {

    Mail::fake();

    $user = User::factory()->create();

    $user->tenant->update([
        'stripe_id' => 'stripe-customer-id',
    ]);

    $payload = [
        'id' => '123456',
        'customer' => 'stripe-customer-id',
        'default_payment_method' => 'card',
        'current_period_end' => 1665754417,
        'status' => 'deleted',
        'cancel_at_period_end' => true,
        'canceled_at' => 1665754417,
    ];

    $controller = new StripeWebhookController();
    $controller->handleSubscriptionDeleted($payload);

    assertDatabaseHas('tenants', [
        'owner_id' => $user->id,
        'card_brand' => null,
        'card_last_four' => null,
        'stripe_subscription' => null,
        'default_payment_method' => null,
        'ends_at' => '2022-10-14 13:33:37',
        'stripe_status' => $payload['status'],
        'trial_ends_at' => null,
        'cancel_at_period_end' => 'Yes',
        'canceled_at' => '2022-10-14 13:33:37',
    ]);

    Mail::assertSent(SendSubscriptionExpiredMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->tenant->owner->email);
    });

});

test('can collect payment', function () {

    $user = User::factory()->create();

    $user->tenant->update([
        'stripe_id' => 'stripe-customer-id',
    ]);

    $payload = [
        'id' => '123456',
        'customer' => 'stripe-customer-id',
        'charges' => [
            'data' => [
                [
                    'payment_method_details' => [
                        'card' => [
                            'brand' => 'Visa',
                            'last4' => '1234',
                        ],
                    ],
                ],
            ],
        ],
    ];

    $controller = new StripeWebhookController();
    $controller->handlePaymentIntentSucceeded($payload);

    assertDatabaseHas('tenants', [
        'owner_id' => $user->id,
        'card_brand' => 'Visa',
        'card_last_four' => '1234',
    ]);

});

test('handle invoice payment failed', function () {

    Mail::fake();

    $user = User::factory()->create();

    $user->tenant->update([
        'stripe_id' => 'stripe-customer-id',
    ]);

    $payload = ['customer' => 'stripe-customer-id'];

    $controller = new StripeWebhookController();
    $controller->handleInvoicePaymentFailed($payload);

    Mail::assertSent(SendPaymentFailedMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->tenant->owner->email);
    });
});

test('does not send email when no invoice exists', function () {

    Mail::fake();

    $user = User::factory()->create();

    $user->tenant->update([
        'stripe_id' => 'stripe-customer-id',
    ]);

    $payload = ['customer' => 'stripe-customer-id'];

    $controller = new StripeWebhookController();
    $controller->handleInvoicePaymentSucceeded($payload);

    Mail::assertNotSent(SendPaymentReceivedMail::class);
});

test('does send email when no invoice exists', function () {

    Mail::fake();
    Storage::fake();
    Http::fake([
        'example.com/sample_invoice.pdf' => Http::response('file contents', 200),
    ]);

    $user = User::factory()->create();

    $user->tenant->update([
        'stripe_id' => 'stripe-customer-id',
    ]);

    $payload = [
        'customer' => 'stripe-customer-id',
        'invoice_pdf' => 'http://example.com/sample_invoice.pdf',
    ];

    $controller = new StripeWebhookController();
    $controller->handleInvoicePaymentSucceeded($payload);

    Mail::assertSent(SendPaymentReceivedMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->tenant->owner->email);
    });
});
