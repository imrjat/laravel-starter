<?php

use App\Http\Livewire\ContactForm;
use App\Mail\Frontend\SendContactMail;
use Illuminate\Support\Facades\Mail;

use function Pest\Livewire\livewire;

test('can see contact component', function () {
    $this
        ->get('/')
        ->assertSeeLivewire(ContactForm::class);
});

test('does validate fields', function () {
    livewire(ContactForm::class)
        ->call('submitForm')
        ->assertHasErrors(['name', 'email', 'message']);
});

test('can send email', function () {
    Mail::fake();

    livewire(ContactForm::class)
        ->set('name', $this->faker->name())
        ->set('email', $this->faker->email())
        ->set('message', $this->faker->paragraph())
        ->call('submitForm')
        ->assertValid();

    Mail::assertSent(SendContactMail::class);
});