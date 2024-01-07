<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;

test('login screen can be rendered', function () {
    $this
        ->get(route('login'))
        ->assertOk();
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $this
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])
        ->assertInvalid();

    $this->assertGuest();
});

test('can logout', function () {
    $this->authenticate();

    $this
        ->post(route('logout'))
        ->assertRedirect('/');

    $this->assertGuest();
});

test('creates a session called 2fa-login on login when 2fa is forced', function () {

    $user = User::factory()->create([
        'two_fa_active' => true,
        'two_fa_secret_key' => '123456',
    ]);

    Setting::create([
        'tenant_id' => $user->tenant_id,
        'key' => 'is_forced_2fa',
        'value' => true,
    ]);

    $this
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('2fa-login');

    $this->assertAuthenticated();
});

test('creates a session called 2fa-setup on login when 2fa is forced and 2fa not setup for user', function () {

    $user = User::factory()->create([
        'two_fa_active' => false,
        'two_fa_secret_key' => null,
    ]);

    Setting::create([
        'tenant_id' => $user->tenant_id,
        'key' => 'is_forced_2fa',
        'value' => true,
    ]);

    $this
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('2fa-setup');

    $this->assertAuthenticated();
});

test('creates a session called 2fa-login on login when 2fa is setup on user', function () {

    $user = User::factory()->create([
        'two_fa_active' => true,
        'two_fa_secret_key' => '123456',
    ]);

    $this
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('2fa-login');

    $this->assertAuthenticated();
});

test('too many login attempts', function () {

    $user = User::factory()->create();

    Event::fake();

    $this
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])
        ->assertInvalid();

    $this
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])
        ->assertInvalid();

    $this
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])
        ->assertInvalid();

    $this
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])
        ->assertInvalid();

    $this
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])
        ->assertInvalid();

    $this
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])
        ->assertInvalid()
        ->assertSessionHasErrors('email', 'auth.throttle');

    Event::assertDispatched(Lockout::class);

});
