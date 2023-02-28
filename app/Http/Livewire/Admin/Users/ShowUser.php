<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;

use Livewire\Component;

class ShowUser extends Component
{
    public User $user;

    public function render(): View
    {
        abort_if_cannot('view_users_profiles');

        return view('livewire.admin.users.show');
    }
}
