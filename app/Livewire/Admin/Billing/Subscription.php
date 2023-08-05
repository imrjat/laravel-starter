<?php

namespace App\Livewire\Admin\Billing;

use App\Services\StripeService;
use Illuminate\Http\Request;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Subscription')]
class Subscription extends Component
{
    public string $plan = '';
    public $tenant;

    public function mount(StripeService $stripeService): void
    {
        $this->tenant = auth()->user()->tenant;
        $this->plan = $stripeService->getPlan();
    }

    public function render()
    {
        return view('livewire.admin.billing.subscription');
    }
}
