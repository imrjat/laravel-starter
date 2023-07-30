<?php

namespace App\Livewire;

use App\Mail\Frontend\SendContactMail;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name           = '';
    public string $email          = '';
    public string $message        = '';
    public string $successMessage = '';

    protected array $rules = [
        'name'    => 'required|string',
        'email'   => 'required|string|email',
        'message' => 'required|string',
    ];

    public function render(): View
    {
        return view('livewire.contact-form');
    }

    /**
     * @throws ValidationException
     */
    public function updated($name): void
    {
        $this->validateOnly($name);
    }

    public function submitForm(): void
    {
        $contact = $this->validate();

        Mail::send(new SendContactMail($contact));

        $this->successMessage = 'Email Sent';

        $this->reset(['name', 'email', 'message']);
    }
}
