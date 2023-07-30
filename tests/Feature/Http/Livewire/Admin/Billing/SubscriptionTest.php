<?php

use App\Models\Tenant;
use App\Models\User;

test('can see subscription page when tenant owner', function () {
    $this->authenticate();
    $this->get(route('admin.billing.subscription'))
        ->assertOk();
});

test('cannot see subscription page when not tenant owner', function () {
    $this->authenticate();

    $user = User::factory()->create();
    $tenant = Tenant::create([
        'owner_id' => $user->id,
    ]);

    $secondUser = User::create([
        'tenant_id' => $tenant->id,
        'name' => 'Test User',
        'slug' => 'test-user',
        'email' => 'user@domain.com',
        'email_verified_at' => now(),
        'is_active' => 1
    ]);

    $this->actingAs($secondUser);

    $this->get(route('admin.billing.subscription'))
        ->assertRedirect(route('dashboard'));
});
