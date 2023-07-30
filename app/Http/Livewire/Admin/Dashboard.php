<?php

namespace App\Http\Livewire\Admin;

use App\Models\Role;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        abort_if_cannot('view_dashboard');

        return view('livewire.admin.dashboard');
    }
}
