<?php

namespace App\Mail\Subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SendWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build(): SendWelcomeMail
    {
        return $this->to($this->user->email)
            ->subject('Welcome to '.config('app.name'))
            ->markdown('mail.subscription.welcome');
    }
}
