@component('mail::message')

{{ __("Payment successfully received.") }}

{{ __('Attached is a copy of the invoice.') }}

{{ __('You can see all invoices in your') }} <a href='{{ route('billing-portal') }}'>{{ __('account') }}</a>.

{{ __('Please do get it touch if you have any questions.') }}

{{ __('David Carr') }}
{{ __('Founder of') }} {{ config('app.name') }}

@endcomponent
