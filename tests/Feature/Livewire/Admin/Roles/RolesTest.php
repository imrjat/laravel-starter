<?php

use App\Models\Role;

beforeEach(function () {
    $this->authenticate();
});

test('can see roles page with admin role', function () {
    $this->get(route('admin.settings.roles.index'))->assertOk();
});
