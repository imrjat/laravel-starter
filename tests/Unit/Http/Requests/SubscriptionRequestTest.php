<?php

use App\Http\Requests\SubscriptionRequest;

beforeEach(function () {
    $this->subscriptionData = new SubscriptionRequest();
});

test('rules', function () {
    $this->assertEquals([
            'type' => [
                'required',
                'string'
            ]
        ],
        $this->subscriptionData->rules()
    );
});

test('authenticate', function () {
    $this->assertTrue($this->subscriptionData->authorize());
});
