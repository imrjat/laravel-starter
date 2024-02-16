<?php

use App\Http\Requests\SubscriptionRequest;

use function PHPUnit\Framework\assertEquals;

beforeEach(function () {
    $this->subscriptionData = new SubscriptionRequest();
});

test('rules', function () {
    assertEquals([
        'type' => [
            'required',
            'string',
        ],
    ],
        $this->subscriptionData->rules()
    );
});

test('authenticate', function () {
    $this->assertTrue($this->subscriptionData->authorize());
});
