<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $secret = config('services.stripe.webhook');
        $payload = file_get_contents("php://input");
        $sig_header = $_SERVER["HTTP_STRIPE_SIGNATURE"];
        $event = null;

        Stripe::setApiKey(ENV('STRIPE_SECRET'));

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

    public function handle_subscription_updated($payload)
    {
        $tenant = Tenant::where('stripe_id', $payload['customer'])->firstOrFail();
        $tenant->stripe_subscription = $payload['id'];
        $tenant->default_payment_method = $payload['default_payment_method'];
        $tenant->ends_at = date('Y-m-d H:i:s', $payload['current_period_end']);
        $tenant->stripe_status = $payload['status'];
        $tenant->trial_ends_at = null;
        $tenant->cancel_at_period_end = $payload['cancel_at_period_end'] ? 'Yes' : 'No';
        $tenant->canceled_at = $payload['canceled_at'] ? date('Y-m-d H:i:s', $payload['canceled_at']) : null;
        $tenant->save();
    }

    public function handle_subscription_deleted($payload)
    {
        $tenant = Tenant::where('stripe_id', $payload['customer'])->firstOrFail();
        $tenant->card_brand = null;
        $tenant->card_last_four = null;
        $tenant->stripe_subscription = null;
        $tenant->default_payment_method = null;
        $tenant->ends_at = date('Y-m-d H:i:s', $payload['current_period_end']);
        $tenant->stripe_status = $payload['status'];
        $tenant->trial_ends_at = null;
        $tenant->cancel_at_period_end = $payload['cancel_at_period_end'] ? 'Yes' : 'No';
        $tenant->canceled_at = $payload['canceled_at'] ? date('Y-m-d H:i:s', $payload['canceled_at']) : null;
        $tenant->save();

        /*$invoiceEmails = InvoiceEmail::where('tenant_id', $tenant->id)->get();

        if ($invoiceEmails === null) {
            Mail::to($tenant->owner->email)->send(new SendSubscriptionExpiredMail($tenant));
        } else {
            foreach ($invoiceEmails as $invoiceEmail) {
                Mail::to($invoiceEmail->email)->send(new SendSubscriptionExpiredMail($tenant));
            }
        }*/
    }

    public function handle_payment_intent_succeeded($payload)
    {
        $tenant = Tenant::where('stripe_id', $payload['customer'])->firstOrFail();
        $tenant->card_brand = $payload['charges']['data'][0]['payment_method_details']['card']['brand'];
        $tenant->card_last_four = $payload['charges']['data'][0]['payment_method_details']['card']['last4'];
        $tenant->save();
    }

    public function handle_invoice_payment_succeeded($payload)
    {
        $tenant = Tenant::where('stripe_id', $payload['customer'])->firstOrFail();
        /*$invoiceEmails = InvoiceEmail::where('tenant_id', $tenant->id)->get();

        $invoice = file_get_contents($payload['invoice_pdf']);
        $filename = uniqid().'.pdf';
        file_put_contents($filename, $invoice);

        if ($invoiceEmails === null) {
            Mail::to($team->owner->email)->send(new SendPaymentReceivedMail($team, $filename));
        } else {
            foreach ($invoiceEmails as $invoiceEmail) {
                Mail::to($invoiceEmail->email)->send(new SendPaymentReceivedMail($team, $filename));
            }
        }

        unlink($filename);*/
    }

    public function handle_invoice_payment_failed($payload)
    {
        $tenant = Tenant::where('stripe_id', $payload['customer'])->firstOrFail();
        /*$invoiceEmails = InvoiceEmail::where('tenant_id', $tenant->id)->get();

        if ($invoiceEmails === null) {
            Mail::to($tenant->owner->email)->send(new SendPaymentFailedMail($tenant));
        } else {
            foreach($invoiceEmails as $invoiceEmail) {
                Mail::to($invoiceEmail->email)->send(new SendPaymentFailedMail($tenant));
            }
        }*/
    }
}
