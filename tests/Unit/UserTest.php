<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

test('can get route', function () {
    $user = User::factory()->create();

    $expected = url(route('admin.users.show', $user->id));
    expect($expected)->toEqual($user->route($user->id));
});

test('isOwner returns true for tenant owner', function () {
    $this->authenticate();
    $user = auth()->user();

    expect($user->isOwner())->toEqual(true);
});

test('isOwner returns false for tenant owner', function () {

    $user = User::factory()->create();
    $tenant = Tenant::create([
        'owner_id' => $user->id
    ]);
    expect($user->isOwner())->toEqual(true);

    $secondUser = User::create([
        'tenant_id' => $tenant->id,
        'name' => 'Test User',
        'slug' => 'test-user',
        'email' => 'user@domain.com'
    ]);

    expect($secondUser->isOwner())->toEqual(false);
});
