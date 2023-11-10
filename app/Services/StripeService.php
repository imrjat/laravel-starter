<?php

namespace App\Services;

use Exception;
use Stripe\BillingPortal\Session as BillingSession;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripeService
{
    protected StripeClient $stripe;

    public function setKey(): void
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * @throws Exception
     */
    public function getCustomer(): ?Customer
    {
        $this->setKey();

        $stripe_customer_id = auth()->user()->tenant->stripe_id;

        if ($stripe_customer_id === '') {
            return $this->createStripeCustomer();
        }

        try {
            $customer = Customer::retrieve($stripe_customer_id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        if ($customer->deleted === true) {
            auth()->user()->tenant->removeStripeId();

            return null;
        }

        return $customer;
    }

    public function getPlan(): string
    {
        if (auth()->user()->tenant->stripe_plan === config('services.stripe.monthly')) {
            return 'Monthly';
        }

        if (auth()->user()->tenant->stripe_plan === config('services.stripe.annually')) {
            return 'Annually';
        }

        return '';
    }

    public function getBillingPortalUrl(): string
    {
        $session = BillingSession::create([
            'customer' => $this->getCustomer()->id,
            'return_url' => url(route('admin.billing')),
        ]);

        return $session->url;
    }

    public function setSubscriptionQty(): void
    {
        $this->setKey();

        if (auth()->user()->tenant->stripe_subscription === null) {
            return;
        }

        $id = auth()->user()->tenant->stripe_subscription;
        $qty = auth()->user()->tenant->users()->count();

        $this->stripe->subscriptions->update($id, [
            'quantity' => $qty,
            'proration_behavior' => 'always_invoice',
        ]);
    }

    protected function createStripeCustomer(): Customer
    {
        $this->setKey();

        $customer = Customer::create([
            'email' => auth()->user()->tenant->owner->email,
            'name' => auth()->user()->tenant->owner->name,
            'metadata' => [
                'tenant_id' => auth()->user()->tenant->id,
            ],
        ]);

        auth()->user()->tenant->setStripeId($customer->id);

        return $customer;
    }
}
