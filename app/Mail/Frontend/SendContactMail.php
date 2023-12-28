<?php

namespace App\Mail\Frontend;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;

    public string $email;

    public string $message;

    /**
     * Create a new message instance.
     *
     * @param  array<string, string>  $contact
     */
    public function __construct(array $contact)
    {
        $this->name = $contact['name'];
        $this->email = $contact['email'];
        $this->message = $contact['message'];
    }

    public function build(): SendContactMail
    {
        return $this->to(config('mail.from.address'))
            ->replyTo($this->email)
            ->subject('Contact from '.config('app.name'))
            ->markdown('mail.frontend.contact');
    }
}
