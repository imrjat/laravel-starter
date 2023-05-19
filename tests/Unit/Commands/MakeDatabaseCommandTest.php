<?php

test('can run db:build on local', function () {
    config(['app.env' => 'local']);

    $this->artisan('db:build')
        ->expectsOutput('This command is disabled on production.');
});

test('cannot run db:build on production', function () {
    config(['app.env' => 'production']);

    $this->artisan('db:build')
        ->assertSuccessful();
});
