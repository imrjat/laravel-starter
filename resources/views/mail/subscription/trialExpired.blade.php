@component('mail::message')

{{ __('Your trial to') }} {{ config('app.name') }} {{ __('has ended') }}.

{{ __('If you would like to continue, you can subscribe by clicking the button below') }}.

@component('mail::button', ['url' => url(route('admin.billing'))])
    {{ __('Subscribe') }}
@endcomponent

{{ __('Please do get it touch if you have any questions.') }}

{{ __('David Carr') }}
{{ __('Founder of') }} {{ config('app.name') }}
@endcomponent
