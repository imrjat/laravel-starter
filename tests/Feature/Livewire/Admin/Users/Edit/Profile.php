<?php

use App\Livewire\Admin\Users\Edit\Profile;

beforeEach(function () {
    $this->authenticate();
});

test('can update profile', function () {
    Livewire::test(Profile::class, ['user' => auth()->user()])
        ->set('name', 'John Doe')
        ->set('email', 'john@doe.com')
        ->call('update')
        ->assertSet('name', 'John Doe')
        ->assertSet('email', 'john@doe.com')
        ->assertHasNoErrors()
        ->assertDispatched('refreshAdminSettings')
        ->assertDispatched('refreshUserMenu');

    $user = auth()->user()->refresh();

    expect($user->name)->toBe('John Doe')
        ->and($user->slug)->toBe('john-doe')
        ->and($user->email)->toBe('john@doe.com');

    $this->assertDatabaseHas('audit_trails', [
        'title' => 'updated '.$user->name."'s profile",
        'link' => route('admin.users.edit', ['user' => $user->id]),
        'section' => 'Users',
        'type' => 'Update',
    ]);

});

test('name cannot be null', function () {
    Livewire::test(Profile::class, ['user' => auth()->user()])
        ->set('name', '')
        ->call('update')
        ->assertHasErrors('name');
});

test('email cannot be null', function () {
    Livewire::test(Profile::class, ['user' => auth()->user()])
        ->set('email', '')
        ->call('update')
        ->assertHasErrors('email');
});

test('email must be an email', function () {
    Livewire::test(Profile::class, ['user' => auth()->user()])
        ->set('email', 'gibberish')
        ->call('update')
        ->assertHasErrors('email');
});

test('image can be null', function () {
    Livewire::test(Profile::class, ['user' => auth()->user()])
        ->set('image', '')
        ->call('update')
        ->assertHasNoErrors();
});
