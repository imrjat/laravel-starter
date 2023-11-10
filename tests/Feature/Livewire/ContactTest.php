<?php

use App\Livewire\ContactForm;
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

    $name = fake()->name();
    $email = fake()->email();
    $message = fake()->paragraph();

    Livewire::test(ContactForm::class)
        ->set('name', $name)
        ->set('email', $email)
        ->set('message', $message)
        ->call('submitForm')
        ->assertSet('successMessage', 'Email Sent')
        ->assertOk();

    Mail::assertSent(SendContactMail::class, function (SendContactMail $mail) use ($email) {
        $mail->build();
        return $mail->hasTo(config('mail.from.address')) &&
            $mail->hasReplyTo($email) &&
            $mail->hasSubject('Contact from '.config('app.name'));
    });
});
