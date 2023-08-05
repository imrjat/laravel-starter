@component('mail::message')

{{ __('Your payment failed for your') }} {{ config('app.name') }} {{ __('subscription') }}.

{{ __('You can update your card details in your') }} <a href='{{ route('admin.billing.card') }}'>{{ __('account') }}</a>.

{{ __('Please do get it touch if you have any questions.') }}

{{ __('David Carr') }}
{{ __('Founder of') }} {{ config('app.name') }}

@endcomponent
