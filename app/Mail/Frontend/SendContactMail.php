<?php

namespace App\Mail\Frontend;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;

    public $email;

    public $message;

    public function __construct($contact)
    {
        $this->name    = $contact['name'];
        $this->email   = $contact['email'];
        $this->message = $contact['message'];
    }

    public function build()
    {
        return $this->to(config('mail.from.address'))
            ->replyTo($this->email)
            ->subject('Contact from '.config('app.name'))
            ->markdown('mail.frontend.contact');
    }
}
