<?php

use function Pest\Laravel\get;

get('/')->assertOk();

describe('authenticated', function () {

    beforeEach(function () {
        $this->authenticate();
    });

    test('can see dashboard text on welcome page when logged in', function () {
        $this->app['config']->set(['admintw.is_live' => true]);

        get('/')->assertSeeText('Dashboard');
    });

    test('cannot see dashboard text on welcome page when logged in but not in live mode', function () {
        $this->app['config']->set(['admintw.is_live' => false]);

        get('/')->assertDontSeeText('Dashboard');
    });

});

describe('guest', function () {

    test('can see login text on welcome page when in live mode', function () {
        $this->app['config']->set(['admintw.is_live' => true]);

        get('/')
            ->assertSeeText('Login')
            ->assertSeeText('Start free trial');
    });

    test('cannot see login text on welcome page when not in live mode', function () {
        $this->app['config']->set(['admintw.is_live' => false]);

        get('/')
            ->assertDontSeeText('Login')
            ->assertDontSeeText('Start free trial');
    });

    test('can see free trail text on welcome page when in live mode', function () {
        $this->app['config']->set(['admintw.is_live' => true]);

        get('/')
            ->assertSeeText('free '.config('admintw.trail_days').' days trial')
            ->assertSeeText('Start your free '.config('admintw.trail_days').' days trial');
    });

    test('cannot see free trail text on welcome page when not in live mode', function () {
        $this->app['config']->set(['admintw.is_live' => false]);

        get('/')
            ->assertDontSeeText('Start free '.config('admintw.trail_days').' days trial')
            ->assertDontSeeText('Start your free '.config('admintw.trail_days').' days trial');
    });

});
