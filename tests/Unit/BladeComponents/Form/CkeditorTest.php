<?php

test('can render checkbox', function () {
    $this
        ->blade('<x-form.checkbox />')
        ->assertSee("type='checkbox'", false);
});
