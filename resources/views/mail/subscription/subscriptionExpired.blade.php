@component('mail::message')

{{ __('Your subscription to') }} {{ config('app.name') }} {{ __('has expired! You can renew your subscription by click the button below') }}.

@component('mail::button', ['url' => route('billing-portal')])
    {{ __('Renew my subscription') }}
@endcomponent

{{ __('Please do get it touch if you have any questions') }}.

{{ __('David Carr') }}
{{ __('Founder of') }} {{ config('app.name') }}

@endcomponent
