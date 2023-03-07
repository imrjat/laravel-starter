<?php

use App\Http\Livewire\ContactForm;

test('can see contact component', function () {
    $this->get('/')->assertSeeLivewire(ContactForm::class);
});