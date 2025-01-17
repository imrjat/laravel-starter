<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Tenant;
use App\Services\StripeService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class SubscriptionController extends Controller
{
    /**
     * @throws ApiErrorException
     * @throws Exception
     */
    public function subscribe(SubscriptionRequest $request, StripeService $stripeService): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        $priceId = match ($validated['type']) {
            'monthly' => config('services.stripe.monthly'),
            'annually' => config('services.stripe.annually'),
            default => null
        };

        if ($priceId === null) {
            return redirect(route('admin.billing'));
        }

        $totalUsers = $user->tenant->users()->count();
        $tenantId = $user->tenant_id;

        $tenant = Tenant::findOrFail($tenantId);
        // @phpstan-ignore-next-line
        $tenant->update([
            'quantity' => $totalUsers,
            'stripe_plan' => $priceId,
        ]);

        $customerId = $stripeService->getCustomer()->id;

        $session = Session::create([
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => $priceId,
                    'quantity' => $totalUsers,
                ],
            ],
            'client_reference_id' => $tenantId,
            'mode' => 'subscription',
            'success_url' => url(route('admin.billing')),
            'cancel_url' => url(route('admin.billing')),
        ]);

        //@phpstan-ignore-next-line
        return redirect()->away($session->url);
    }

    public function billingPortal(StripeService $stripeService): RedirectResponse
    {
        return redirect()->away($stripeService->getBillingPortalUrl());
    }
}
