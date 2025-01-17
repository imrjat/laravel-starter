<?php

use App\Models\Tenant;
use Database\Factories\TenantFactory;

test('is on trial', function () {
    $this->authenticate();
    $user = auth()->user();

    expect($user->tenant->isOnTrial())->toBeTrue();
});

test('has tenant factory', function () {
    $tenantFactory = Tenant::factory();
    expect($tenantFactory)->toBeInstanceOf(TenantFactory::class);
});

test('is not on trial', function () {
    $this->authenticate();
    $user = auth()->user();
    $user->tenant->trial_ends_at = now()->subDay();

    expect($user->tenant->isOnTrial())->toBeFalse();
});

test('is on grace period', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->stripe_status = 'cancelled';
    $user->tenant->ends_at = now()->addDay();
    $user->tenant->save();

    expect($user->tenant->isOnGracePeriod())->toBeTrue();
});

test('grace period has finished', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->stripe_status = 'cancelled';
    $user->tenant->ends_at = null;
    $user->tenant->save();

    expect($user->tenant->isOnGracePeriod())->toBeFalse();
});

test('is not on grace period', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->stripe_status = 'cancelled';
    $user->tenant->ends_at = now()->subDay()->toDateTimeString();
    $user->tenant->save();

    expect($user->tenant->isOnGracePeriod())->toBeFalse();
});

test('is on lifetime', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->stripe_status = 'lifetime';
    $user->tenant->save();

    expect($user->tenant->isOnLifetime())->toBeTrue();
});

test('is not on lifetime', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->stripe_status = 'cancelled';
    $user->tenant->save();

    expect($user->tenant->isOnLifetime())->toBeFalse();
});

test('is active', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->stripe_status = 'active';
    $user->tenant->save();

    expect($user->tenant->isActive())->toBeTrue();
});

test('is active when on lifetime', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->stripe_status = 'lifetime';
    $user->tenant->save();

    expect($user->tenant->isActive())->toBeTrue();
});

test('is active when on grace period', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->stripe_status = 'cancelled';
    $user->tenant->ends_at = now()->addDay();
    $user->tenant->save();

    expect($user->tenant->isActive())->toBeTrue();
});

test('is not active when cancelled', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->stripe_status = 'cancelled';
    $user->tenant->ends_at = now()->subDay()->format('Y-m-d H:i:s');
    $user->tenant->save();

    expect($user->tenant->isActive())->toBeFalse();
});

test('trail is expiring in 3 days', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->stripe_status = 'trial';
    $user->tenant->trial_ends_at = now()->addDays(3);
    $user->tenant->save();

    expect($user->tenant->trailEndsInDays(3))->toBeTrue();
});

test('trail is expiring in 4 days', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->stripe_status = 'trial';
    $user->tenant->trial_ends_at = now()->addDays(4);
    $user->tenant->save();

    expect($user->tenant->trailEndsInDays(4))->toBeTrue();
});

test('trailEndsInDays returns false when not on trial', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->stripe_status = 'active';
    $user->tenant->trial_ends_at = now()->subDays(30);
    $user->tenant->save();

    expect($user->tenant->trailEndsInDays(4))->toBeFalse();
});

test('trail ending emails already sent', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->trial_ending_mail_sent_at = now()->subDay();
    $user->tenant->save();

    expect($user->tenant->wasAlreadySentTrialEndingSoonMail())->toBeTrue();
});

test('trail ending emails is not be sent', function () {
    $this->authenticate();
    $user = auth()->user();

    expect($user->tenant->wasAlreadySentTrialEndingSoonMail())->toBeFalse();
});

test('trail ended emails already sent', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->trial_ending_mail_sent_at = now()->subDay();
    $user->tenant->save();

    expect($user->tenant->wasAlreadySentTrialEndingSoonMail())->toBeTrue();
});

test('trail ended emails is not be sent', function () {
    $this->authenticate();
    $user = auth()->user();

    expect($user->tenant->wasAlreadySentTrialEndingSoonMail())->toBeFalse();
});

test('can set trail ending soon sent mail', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->rememberHasBeenSentTrialEndingSoonMail();

    expect($user->tenant->wasAlreadySentTrialEndingSoonMail())->toBeTrue();
});

test('can set trail ended status', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->setTrialEndedStatus();

    expect($user->tenant->stripe_status)->toBe('Trial Ended');
});

test('can remove stripe id', function () {
    $this->authenticate();
    $user = auth()->user();

    $user->tenant->setStripeId('1234');
    $user->tenant->removeStripeId();

    expect($user->tenant->stripe_id)->toBeNull();
});
