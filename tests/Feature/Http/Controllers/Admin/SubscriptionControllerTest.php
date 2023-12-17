<?php

test('get billing portal', function () {
    $this->authenticate();
    $this->get(route('billing-portal'))
        ->assertRedirect();
});
