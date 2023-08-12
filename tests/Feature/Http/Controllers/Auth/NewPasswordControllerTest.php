<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Event;

test('can view forgotten password page', function() {
   $this->assertGuest();

   $this->get(route('password.reset', 'token'))->assertOk();
});

test('cannot view forgotten password page when logged in', function() {
   $this->authenticate();
   $this->get(route('password.reset', 'token'))->assertRedirect(route('dashboard'));
});

test('can reset password', function() {

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $this->post(route('password.email'), [
         'email' => $user->email,
    ])->assertRedirect('/');

    $token = DB::table('password_reset_tokens')->first();

    $password = 'ght73A3!$^DS';

    $this->post(route('password.store'), [
         'token' => $token->token,
         'email' => $user->email,
         'password' => $password,
         'password_confirmation' => $password,
    ])->assertRedirect('/');

});
