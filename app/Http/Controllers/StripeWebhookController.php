<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\Subscription\SendPaymentFailedMail;
use App\Mail\Subscription\SendPaymentReceivedMail;
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
    public function __invoke(Request $request): void
    {
        //@codeCoverageIgnoreStart
        if (! app()->environment('testing')) {

            $secret = config('services.stripe.webhook');
            $payload = file_get_contents('php://input');
            $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

            Stripe::setApiKey(config('services.stripe.secret'));

            try {
                //@phpstan-ignore-next-line
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
                //@phpstan-ignore-next-line
                $this->handleSubscriptionUpdated($event->data->object);
            }

            if ($event->type == 'customer.subscription.deleted') {
                //@phpstan-ignore-next-line
                $this->handleSubscriptionDeleted($event->data->object);
            }

            if ($event->type == 'payment_intent.succeeded') {
                //@phpstan-ignore-next-line
                $this->handlePaymentIntentSucceeded($event->data->object);
            }

            if ($event->type == 'invoice.payment_succeeded') {
                //@phpstan-ignore-next-line
                $this->handleInvoicePaymentSucceeded($event->data->object);
            }

            if ($event->type == 'invoice.payment_failed') {
                //@phpstan-ignore-next-line
                $this->handleInvoicePaymentFailed($event->data->object);
            }
        }

        http_response_code(200);
        //@codeCoverageIgnoreEnd
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function handleSubscriptionUpdated(array $payload): void
    {
        $tenant = Tenant::where('stripe_id', $payload['customer'])->firstOrFail();
        $tenant->stripe_subscription = $payload['id'];
        $tenant->default_payment_method = $payload['default_payment_method'];
        $tenant->ends_at = Carbon::createFromTimestamp($payload['current_period_end']);
        $tenant->stripe_status = $payload['status'];
        $tenant->trial_ends_at = null;
        $tenant->cancel_at_period_end = $payload['cancel_at_period_end'] ? 'Yes' : 'No';
        $tenant->canceled_at = ($payload['canceled_at'] != '') ? Carbon::createFromTimestamp($payload['canceled_at']) : null;
        $tenant->save();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function handleSubscriptionDeleted(array $payload): void
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

    /**
     * @param  array<string, mixed>  $payload
     */
    public function handlePaymentIntentSucceeded(array $payload): void
    {
        $tenant = Tenant::where('stripe_id', $payload['customer'])->firstOrFail();
        $tenant->card_brand = $payload['charges']['data'][0]['payment_method_details']['card']['brand'];
        $tenant->card_last_four = $payload['charges']['data'][0]['payment_method_details']['card']['last4'];
        $tenant->save();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function handleInvoicePaymentSucceeded(array $payload): void
    {
        $tenant = Tenant::where('stripe_id', $payload['customer'])->firstOrFail();

        //@codeCoverageIgnoreStart
        if (isset($payload['invoice_pdf'])) {
            $invoice = file_get_contents($payload['invoice_pdf']);
            $filename = uniqid().'.pdf';
            file_put_contents($filename, $invoice);

            Mail::to($tenant->owner->email)->send(new SendPaymentReceivedMail($tenant, $filename));

            unlink($filename);
            //@codeCoverageIgnoreEnd
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function handleInvoicePaymentFailed(array $payload): void
    {
        $tenant = Tenant::where('stripe_id', $payload['customer'])->firstOrFail();

        Mail::to($tenant->owner->email)->send(new SendPaymentFailedMail($tenant));
    }
}
