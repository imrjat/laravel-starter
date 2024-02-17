<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Billing;

use App\Models\Tenant;
use App\Services\StripeService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Subscription')]
class Subscription extends Component
{
    public string $plan = '';

    public Tenant $tenant;

    public function mount(StripeService $stripeService): void
    {
        //@phpstan-ignore-next-line
        $this->tenant = auth()->user()->tenant;
        $this->plan = $stripeService->getPlan();
    }

    public function render(): View
    {
        return view('livewire.admin.billing.subscription');
    }
}
