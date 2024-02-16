<?php

use function Pest\Laravel\get;

test('get billing portal', function () {
    $this->authenticate();

    get(route('billing-portal'))
        ->assertRedirect();
});
