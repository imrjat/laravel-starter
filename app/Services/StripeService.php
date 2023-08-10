<?php

namespace App\Services;

use App\Models\Tenant;
use Exception;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\BillingPortal\Session as BillingSession;

class StripeService
{
    protected Tenant $tenant;
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->tenant = auth()->user()->tenant;
        $this->stripe = new StripeClient(config('services.stripe.secret'));
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * @throws Exception
     */
    public function getCustomer(): ?Customer
    {
        $stripe_customer_id = $this->tenant->stripe_id;

        if ($stripe_customer_id === null) {
            return $this->createStripeCustomer();
        }

        try {
            $customer = Customer::retrieve($stripe_customer_id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        if ($customer->deleted === true) {
            $this->tenant->removeStripeId();

            return null;
        }

        return $customer;
    }

    public function getPlan(): string
    {
        if ($this->tenant->stripe_plan === config('services.stripe.monthly')) {
            return 'Monthly';
        }

        if ($this->tenant->stripe_plan === config('services.stripe.annually')) {
            return 'Annually';
        }

        return '';
    }

    public function getBillingPortalUrl(): string
    {
        $session = BillingSession::create([
          'customer' => $this->getCustomer()->id,
          'return_url' => url(route('admin.billing'))
        ]);

        return $session->url;
    }

    public function setSubscriptionQty(): void
    {
        $id = $this->tenant->stripe_subscription;
        $qty = $this->tenant->users()->count();

        $this->stripe->subscriptions->update($id, [
            'quantity' => $qty,
            'proration_behavior' => 'always_invoice'
        ]);
    }

    protected function createStripeCustomer(): Customer
    {
        $customer = Customer::create([
            'email' => $this->tenant->owner->email,
            'name' => $this->tenant->owner->name,
            'metadata' => [
                'tenant_id' => $this->tenant->id
            ]
        ]);

        $this->tenant->setStripeId($customer->id);

        return $customer;
    }
}
