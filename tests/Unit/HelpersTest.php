<?php

test('can does return false for user role with no permissions set', function () {
    $this->authenticate('user');
    expect(can('view_dashboard'))->toBeFalse;
});

test('can does return true for admin', function () {
    $this->authenticate();
    expect(can('view_dashboard'))->toBeTrue();
});

test('cannot does return false for user role with no permissions set', function () {
    $this->authenticate('user');
    expect(cannot('view_dashboard'))->toBeTrue;
});

test('cannot does return true for admin', function () {
    $this->authenticate();
    expect(cannot('view_dashboard'))->toBeFalse();
});