<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        if (app()->environment('testing') === false) {
            $secret = config('services.stripe.webhook');
            $payload = file_get_contents('php://input');
            $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
            $event = null;

            Stripe::setApiKey(config('services.stripe.secret'));

            $event = Webhook::constructEvent($payload, $sig_header, $secret);

            try {
                $event = Webhook::constructEvent($payload, $sig_header, $secret);
            } catch (UnexpectedValueException $e) {
                // Invalid payload
                http_response_code(400);
                exit();
            } catch (SignatureVerificationException $e) {
                // Invalid signature
                http_response_code(400);
                exit();
            }
        } else {
            $event = json_decode(json_encode($request->all()));
        }

        if ($event->type == 'customer.subscription.updated') {
            $this->handle_subscription_updated($event->data->object);
        }

        if ($event->type == 'customer.subscription.deleted') {
            $this->handle_subscription_deleted($event->data->object);
        }

        if ($event->type == 'payment_intent.succeeded') {
            $this->handle_payment_intent_succeeded($event->data->object);
        }

        if ($event->type == 'invoice.payment_succeeded') {
            $this->handle_invoice_payment_succeeded($event->data->object);
        }

        if ($event->type == 'invoice.payment_failed') {
            $this->handle_invoice_payment_failed($event->data->object);
        }

        http_response_code(200);
    }

    public function handle_subscription_updated($payload): void
    {
        $tenant = Tenant::where('stripe_id', $payload['customer'])->firstOrFail();
        $tenant->stripe_subscription = $payload['id'];
        $tenant->default_payment_method = $payload['default_payment_method'];
        $tenant->ends_at = Carbon::createFromTimestamp($payload['current_period_end']);
        $tenant->stripe_status = $payload['status'];
        $tenant->trial_ends_at = null;
        $tenant->cancel_at_period_end = $payload['cancel_at_period_end'] ? 'Yes' : 'No';
        $tenant->canceled_at = Carbon::createFromTimestamp($payload['canceled_at']);
        $tenant->save();
    }

    public function handle_subscription_deleted($payload): void
    {
        $tenant = Tenant::where('stripe_id', $payload['customer'])->firstOrFail();
        $tenant->card_brand = null;
        $tenant->card_last_four = null;
        $tenant->stripe_subscription = null;
        $tenant->default_payment_method = null;
        $tenant->ends_at = Carbon::createFromTimestamp($payload['current_period_end']);
        $tenant->stripe_status = $payload['status'];
        $tenant->trial_ends_at = null;
        $tenant->cancel_at_period_end = $payload['cancel_at_period_end'] ? 'Yes' : 'No';
        $tenant->canceled_at = Carbon::createFromTimestamp($payload['canceled_at']);
        $tenant->save();

        //Mail::to($tenant->owner->email)->send(new SendSubscriptionExpiredMail($tenant));
    }

    public function handle_payment_intent_succeeded($payload): void
    {
        $tenant = Tenant::where('stripe_id', $payload['customer'])->firstOrFail();
        $tenant->card_brand = $payload['charges']['data'][0]['payment_method_details']['card']['brand'];
        $tenant->card_last_four = $payload['charges']['data'][0]['payment_method_details']['card']['last4'];
        $tenant->save();
    }

    public function handle_invoice_payment_succeeded($payload): void
    {
        $tenant = Tenant::where('stripe_id', $payload['customer'])->firstOrFail();
        /*
        $invoice = file_get_contents($payload['invoice_pdf']);
        $filename = uniqid().'.pdf';
        file_put_contents($filename, $invoice);

        Mail::to($team->owner->email)->send(new SendPaymentReceivedMail($team, $filename));

        unlink($filename);*/
    }

    public function handle_invoice_payment_failed($payload): void
    {
        $tenant = Tenant::where('stripe_id', $payload['customer'])->firstOrFail();

        //Mail::to($tenant->owner->email)->send(new SendPaymentFailedMail($tenant));
    }
}
