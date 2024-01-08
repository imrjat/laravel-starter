@component('mail::message')

{{ __('Your trial to') }} {{ config('app.name') }} {{ __('ends in 3 days') }}.

@component('mail::button', ['url' => route('billing-portal')])
    {{ __('Subscribe') }}
@endcomponent

{{ __('Please do get it touch if you have any questions.') }}

{{ __('David Carr') }}
{{ __('Founder of') }} {{ config('app.name') }}
@endcomponent
