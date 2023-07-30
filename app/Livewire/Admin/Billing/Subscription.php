<?php

namespace App\Livewire\Admin\Billing;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Subscription')]
class Subscription extends Component
{
    public string $status = 'trial';
    public bool $cancelled = false;

    public function mount()
    {
        $this->status = auth()->user()->tenant->status;
    }

    public function render()
    {
        if (/*$this->tenant->cancel_at_period_end == 'Yes' ||*/ auth()->user()->tenant->isOnGracePeriod()) {
            $this->status = 'Cancel Scheduled for '.auth()->user()->tenant->ends_at->format('jS M Y H:iA');
            $this->cancelled = true;
        } elseif ($this->status === 'canceled') {
            $this->cancelled = true;
        }

        return view('livewire.admin.billing.subscription');
    }
}
