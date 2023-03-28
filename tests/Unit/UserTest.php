<?php

use App\Models\User;

test('can get route', function () {
    $user = User::factory()->create();

    $expected = url(route('admin.users.show', $user->id));
    expect($expected)->toEqual($user->route($user->id));
});
