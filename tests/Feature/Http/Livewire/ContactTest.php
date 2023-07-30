<?php

use App\Http\Livewire\ContactForm;
use App\Mail\Frontend\SendContactMail;
use Illuminate\Support\Facades\Mail;

use Livewire\Livewire;

test('can see contact component', function () {
    $this
        ->get('/')
        ->assertSeeLivewire(ContactForm::class);
});

test('does validate fields', function () {
    Livewire::test(ContactForm::class)
        ->call('submitForm')
        ->assertHasErrors(['name', 'email', 'message']);
});

test('can send email', function () {
    Mail::fake();

    Livewire::test(ContactForm::class)
        ->set('name', fake()->name())
        ->set('email', fake()->email())
        ->set('message', fake()->paragraph())
        ->call('submitForm')
        ->assertValid();

    Mail::assertSent(SendContactMail::class);
});
