<?php

test('welcome page can be rendered', function () {
    $this
        ->post('stripe/webhooks', [
            'HTTP_STRIPE_SIGNATURE' => '??',
            'type' => 'customer.subscription.created',
            'data' => [
                'object' => [
                    'customer' => 'cus_123',
                    'plan' => [
                        'id' => 'plan_123',
                        'nickname' => 'Monthly',
                        'amount' => 1000,
                        'currency' => 'usd',
                        'interval' => 'month',
                        'interval_count' => 1,
                    ],
                ],
            ],
        ])
        ->assertStatus(200);
});
