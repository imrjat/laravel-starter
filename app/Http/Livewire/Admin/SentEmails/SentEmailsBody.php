<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\SentEmails;

use App\Models\SentEmail;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use function view;

class SentEmailsBody extends Component
{
    public $body;

    public function mount($id = null)
    {
        $email = SentEmail::findOrFail($id);
        $this->body = $email->body;
    }

    public function render(): View
    {
        return view('livewire.admin.sent-emails.body')->layout('layouts.plain');
    }
}
