<?php

namespace App\Http\Livewire;

use App\Mail\Frontend\SendContactMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactForm extends Component
{
    public $name;

    public $email;

    public $message;

    public $successMessage;

    protected $rules = [
        'name'    => 'required|string',
        'email'   => 'required|string|email',
        'message' => 'required|string',
    ];

    public function render()
    {
        return view('livewire.contact-form');
    }

    public function updated($name)
    {
        $this->validateOnly($name);
    }

    public function submitForm()
    {
        $contact = $this->validate();

        Mail::send(new SendContactMail($contact));

        $this->successMessage = 'Email Sent';

        $this->reset(['name', 'email', 'message']);
    }
}
