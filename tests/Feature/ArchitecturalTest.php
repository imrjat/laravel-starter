<?php

use App\Http\Controllers\Controller;

test('globals')
    ->expect(['dd', 'dump', 'ray', 'env'])
    ->not->toBeUsed();

test('traits')
    ->expect('App\Models\Traits')
    ->toBeTraits();

test('strict types')
    ->expect('App')
    ->toUseStrictTypes();

test('controllers')
    ->expect('App\Http\Controllers')
    ->toHaveSuffix('Controller')
    ->toBeClasses()
    ->classes->not->toBeFinal()
    ->classes->toExtend(Controller::class);
